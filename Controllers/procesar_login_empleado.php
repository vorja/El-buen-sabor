<?php
require_once '../models/MySQL.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = $_POST['correo'] ?? '';
    $contrasena = $_POST['password'] ?? '';

    if (empty($correo) || empty($contrasena)) {
        header('Location: ../views/login.php?error=no_data');
        exit();
    }

    $mysql = new MySQL();
    $mysql->connect();

    // Intentar autenticar en la tabla 'usuarios'
    $query_user = "SELECT id, nombre, correo, password, rol_id FROM empleados WHERE correo = '" . $mysql->escape_string($correo) . "'";
    $result_user = $mysql->efectuarConsulta($query_user);

    if ($result_user && mysqli_num_rows($result_user) > 0) {
        $user_data = mysqli_fetch_assoc($result_user);

        if (password_verify($contrasena, $user_data['password'])) {
            // Login exitoso como usuario o admin
            $_SESSION['id_usuario'] = $user_data['id']; // Guarda el ID de usuario
            $_SESSION['nombre'] = $user_data['nombre'];
            $_SESSION['correo'] = $user_data['email'];
            $_SESSION['rol'] = $user_data['rol_id']; 

            // Redireccionar según el tipo de usuario
            if ($user_data['tipo'] === 'admin') {
                header('Location: ../views/admin_pedidos.php'); // O a donde vaya tu panel de admin principal
            } 
            if ($user_data['tipo'] === 'user') { 
                header('Location: ../index.php'); 
            }
            exit();
        }
    }

 

    // Si ninguna de las autenticaciones fue exitosa
    header('Location: ../views/login.php?estado=invalid_credentials');
    exit();

} else {
    header('Location: ../views/login.php');
    exit();
}
?>