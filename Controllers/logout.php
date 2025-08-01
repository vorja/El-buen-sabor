<?php
// repository/Controllers/logout.php
// Controlador simple para cerrar la sesión de cualquier usuario.  Este
// script elimina todas las variables de sesión, destruye la sesión
// actual y redirige al formulario de inicio de sesión de empleados.

session_start();
// Borrar todas las variables de sesión
$_SESSION = [];
// Destruir la cookie de sesión
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
}
// Destruir la sesión
session_destroy();
// Redirigir al login de empleados (o página principal según la lógica de la app)
header('Location: ../Views/login.php');
exit;