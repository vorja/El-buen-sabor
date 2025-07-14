<?php
require_once __DIR__ . '/../Models/EmpleadoModel.php';
session_start();

class LoginController {
    public static function loginEmpleado($correo, $contrasena) {
        $empleado = Models\EmpleadoModel::verificarLogin($correo, $contrasena);
        if ($empleado) {
            // Inicio de sesión exitoso: guardar datos en sesión
            $_SESSION['empleado_id'] = $empleado['id'];
            $_SESSION['nombre_empleado'] = $empleado['nombre'];
            $_SESSION['rol'] = $empleado['rol_id'];
            // Redirigir según rol: admin (2) al dashboard admin, mesero (1) a panel mesero
            if ($empleado['rol_id'] == 2) {
                header("Location: ../Views/admin/dashboard.php");
            } else {
                header("Location: ../Views/mesero/mesas.php");
            }
            exit;
        } else {
            // Credenciales inválidas
            header("Location: ../Views/login.php?error=1");
            exit;
        }
    }

    public static function logout() {
        session_start();
        session_destroy();
        header("Location: ../Views/login.php");
        exit;
    }
}

// Procesar la solicitud de login si se llamó este script directamente vía formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['correo'], $_POST['password'])) {
    LoginController::loginEmpleado($_POST['correo'], $_POST['password']);
}
