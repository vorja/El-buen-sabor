<?php


session_start();

require_once __DIR__ . '/../Models/Database.php';
require_once __DIR__ . '/../Models/EmpleadoModel.php';


$correo   = $_POST['correo'] ?? '';
$password = $_POST['password'] ?? '';

$empleado = \Models\EmpleadoModel::verificarLogin($correo, $password);
if ($empleado) {
    
    $_SESSION['empleado_id']     = $empleado['id'];
    $_SESSION['nombre_empleado'] = $empleado['nombre'];
    $_SESSION['rol']             = $empleado['rol_id'];

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
