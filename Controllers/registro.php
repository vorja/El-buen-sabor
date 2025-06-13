<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../Models/mysql.php';

class RegistroController {
    private $conn;
    private $errors = [];

    public function __construct() {
        try {
            $this->conn = new MySQL();
        } catch (Exception $e) {
            $this->sendResponse([
                'error' => 'Error de conexión a la base de datos',
                'message' => $e->getMessage()
            ], 500);
            exit;
        }
    }

    public function registrar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->sendResponse(['error' => 'Método no permitido'], 405);
            return;
        }

        // Verificar que todos los campos requeridos estén presentes
        $required_fields = ['email', 'fullname', 'phone', 'cedula', 'contraseña'];
        foreach ($required_fields as $field) {
            if (!isset($_POST[$field]) || empty($_POST[$field])) {
                $this->errors[$field] = 'Este campo es requerido';
            }
        }

        if (!empty($this->errors)) {
            $this->sendResponse(['errors' => $this->errors], 400);
            return;
        }

        $email = $this->sanitizeInput($_POST['email']);
        $fullname = $this->sanitizeInput($_POST['fullname']);
        $phone = $this->sanitizeInput($_POST['phone']);
        $cedula = $this->sanitizeInput($_POST['cedula']);
        $password = $_POST['contraseña'];

        if (!$this->validateEmail($email)) {
            $this->errors['email'] = 'Correo electrónico inválido';
        }

        if (!$this->validateFullname($fullname)) {
            $this->errors['fullname'] = 'Nombre completo inválido';
        }

        if (!$this->validatePhone($phone)) {
            $this->errors['phone'] = 'Número de teléfono inválido';
        }

        if (!$this->validateCedula($cedula)) {
            $this->errors['cedula'] = 'Cédula inválida';
        }

        if (!$this->validatePassword($password)) {
            $this->errors['password'] = 'La contraseña debe tener al menos 8 caracteres';
        }

        if ($this->emailExists($email)) {
            $this->errors['email'] = 'Este correo electrónico ya está registrado';
        }

        if ($this->cedulaExists($cedula)) {
            $this->errors['cedula'] = 'Esta cédula ya está registrada';
        }

        if (!empty($this->errors)) {
            $this->sendResponse(['errors' => $this->errors], 400);
            return;
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        try {
            $query = "INSERT INTO usuario (correo, nombre, telefono, cedula, password, fecha_registro) 
                     VALUES (?, ?, ?, ?, ?, NOW())";
            
            $params = [$email, $fullname, $phone, $cedula, $hashedPassword];
            
            if ($this->conn->insert($query, $params)) {
                $_SESSION['user_id'] = $this->conn->getLastInsertId();
                $_SESSION['user_email'] = $email;
                
                $this->sendResponse([
                    'success' => true,
                    'message' => 'Registro exitoso',
                    'redirect' => '../Views/login.php'
                ], 201);
            } else {
                throw new Exception("Error al registrar el usuario");
            }
        } catch (Exception $e) {
            $this->sendResponse([
                'error' => 'Error en el servidor',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    private function sanitizeInput($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    private function validateEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) && strlen($email) <= 100;
    }

    private function validateFullname($fullname) {
        return preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]{2,100}$/', $fullname);
    }

    private function validatePhone($phone) {
        return preg_match('/^[0-9]{10}$/', $phone);
    }

    private function validateCedula($cedula) {
        return preg_match('/^[0-9]{8,10}$/', $cedula);
    }

    private function validatePassword($password) {
        return strlen($password) >= 8;
    }

    private function emailExists($email) {
        $query = "SELECT id FROM usuario WHERE correo = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0;
    }

    private function cedulaExists($cedula) {
        $query = "SELECT id FROM usuario WHERE cedula = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $cedula);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0;
    }

    private function sendResponse($data, $statusCode = 200) {
        if (ob_get_length()) ob_clean();
        
        http_response_code($statusCode);
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: POST');
        header('Access-Control-Allow-Headers: Content-Type');
        
        echo json_encode($data);
        exit;
    }
}

// Manejar errores de PHP
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});

try {
    $controller = new RegistroController();
    $controller->registrar();
} catch (Exception $e) {
    header('Content-Type: application/json');
    http_response_code(500);
    echo json_encode([
        'error' => 'Error en el servidor',
        'message' => $e->getMessage()
    ]);
}
