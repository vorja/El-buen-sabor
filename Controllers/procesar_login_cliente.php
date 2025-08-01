<?php
// repository/Controllers/procesar_login_cliente.php
// Este controlador procesa el formulario de acceso de clientes que se
// utiliza cuando un visitante escanea el código QR de una mesa.  Su
// cometido es registrar al cliente en la tabla `clientes`, crear una
// sesión PHP de larga duración y redirigir al usuario hacia el menú.

use Models\ClienteModel;

require_once __DIR__ . '/../Models/ClienteModel.php';

// extiende la duración de la cookie de sesión a cuatro horas para que
// el pedido permanezca activo incluso si el cliente cierra el
// navegador y vuelve más tarde.  Debe llamarse antes de
// session_start().
session_set_cookie_params([
    'lifetime' => 4 * 60 * 60,
    'path' => '/',
    'httponly' => true,
]);

session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../Views/cliente/loginCliente.php');
    exit;
}

$nombre = trim($_POST['nombre'] ?? '');
$email  = trim($_POST['email'] ?? '');

// Validar entradas.  Si falta cualquiera de los campos se redirige
// nuevamente al formulario con un código de error.  Se pasa el
// token para que no se pierda el contexto de la mesa.
if ($nombre === '' || $email === '') {
    $tok = urlencode($_POST['token'] ?? '');
    header("Location: ../Views/cliente/loginCliente.php?token={$tok}&error=no_data");
    exit;
}

// Registrar el cliente usando el modelo.  Esta operación devuelve
// el ID generado.
$clienteId = ClienteModel::crearCliente($nombre, $email);

// Guardar en la sesión para que otros controladores conozcan al
// cliente logado.  El id de sesión de mesa y pedido se crearán en
// ClienteController::registrarIngreso cuando se procese el token QR.
$_SESSION['cliente_id'] = $clienteId;

// Redirige al controlador principal de cliente para que procese el
// token QR y cree la sesión de mesa.  Se delega la lógica allí
// porque se requiere vincular la mesa, el mesero y crear el pedido.
// El parámetro token y mesero se leen desde el formulario hidden.
header('Location: ../Controllers/ClienteController.php');
exit;