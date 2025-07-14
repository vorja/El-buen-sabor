<?php
// Controllers/procesar_login_empleado.php

session_start();

// Ajusta estas rutas a donde tengas tus archivos
require_once __DIR__ . '/../Models/Database.php';
require_once __DIR__ . '/../Models/EmpleadoModel.php';

// Capturamos credenciales del formulario
$correo   = $_POST['correo'] ?? '';
$password = $_POST['password'] ?? '';

// Usamos el modelo para verificar login
$empleado = \Models\EmpleadoModel::verificarLogin($correo, $password);
if ($empleado) {
    // Guardar datos en sesión
    $_SESSION['empleado_id']     = $empleado['id'];
    $_SESSION['nombre_empleado'] = $empleado['nombre'];
    $_SESSION['rol']             = $empleado['rol_id'];

    // Redirigir según rol
    if ($empleado['rol_id'] == 2) {
        header("Location: ../Views/admin/dashboard.php");
    } else {
        header("Location: ../Views/mesero/mesas.php");
    }
    exit;
} else {
    header("Location: ../Views/login.php?error=1");
    exit;
}
