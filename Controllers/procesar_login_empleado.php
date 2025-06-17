<?php	
require_once '../models/MySQL.php';
session_start();

echo '<pre>'; print_r($_POST); echo '</pre>'; 

$correo     = $_POST['correo']   ?? '';
$contrasena = $_POST['password'] ?? '';

if ($correo === '' || $contrasena === '') {
    header('Location: ../views/login.php?error=no_data'); exit;
}

$mysql = new MySQL();
$query = "SELECT id, nombre, email, `password`, rol_id
          FROM empleados
          WHERE email = '".$mysql->escape_string($correo)."'";
$res = $mysql->efectuarConsulta($query);

if ($res && mysqli_num_rows($res) === 1) {
    $u = mysqli_fetch_assoc($res);
    if (password_verify($contrasena, $u['password'])) {
        $_SESSION['id_usuario'] = $u['id'];
        $_SESSION['nombre']     = $u['nombre'];
        $_SESSION['correo']     = $u['email'];
        $_SESSION['rol']        = $u['rol_id'];
        header('Location: ../views/dashboard.php'); exit;
    }
}

header('Location: ../views/login.php?estado=invalid_credentials');
exit;
?>