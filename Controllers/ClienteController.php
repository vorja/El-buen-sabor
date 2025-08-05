<?php
// repository/Controllers/ClienteController.php
// Controlador principal para el flujo de clientes. Gestiona el
// registro de clientes cuando se escanea un código QR, la creación
// de la sesión y del pedido, el agregado de productos al pedido y
// la solicitud de cuenta.

require_once __DIR__ . '/../Models/ClienteModel.php';
require_once __DIR__ . '/../Models/MesaModel.php';
require_once __DIR__ . '/../Models/SesionModel.php';
require_once __DIR__ . '/../Models/PedidoModel.php';
require_once __DIR__ . '/../Models/DetallePedidoModel.php';
require_once __DIR__ . '/../Models/Database.php';

use Models\ClienteModel;
use Models\MesaModel;
use Models\SesionModel;
use Models\PedidoModel;
use Models\DetallePedidoModel;
use Models\Database;

// Configurar la cookie de sesión para que dure 4 horas. Esto
// garantiza que la sesión del cliente persista incluso si el
// navegador se cierra y se vuelve a abrir dentro de ese periodo.
session_set_cookie_params([
    'lifetime' => 4 * 60 * 60,
    'path'     => '/',
    'httponly' => true,
]);
session_start();

class ClienteController {
    /**
     * Procesa el ingreso de un cliente a partir de un token QR. Valida
     * el token, registra al cliente y crea la sesión de mesa y el
     * pedido correspondiente. Asocia la mesa con el mesero si se
     * proporciona su ID (a través de un campo oculto en el formulario).
     *
     * @param string $token  Token QR de la mesa.
     * @param string $nombre Nombre del cliente.
     * @param string $email  Correo del cliente.
     */
    public static function registrarIngreso(string $token, string $nombre, string $email): void {
        // Buscar el token en la base de datos
        $tokenData = MesaModel::obtenerToken($token);
        if (!$tokenData) {
            // Token inválido o expirado
            header("Location: ../Views/cliente/loginCliente.php?token=$token&error=token");
            exit;
        }
        $mesaId    = (int)$tokenData['mesa_id'];
        $qrTokenId = (int)$tokenData['id'];
        // Obtener id del mesero desde el formulario (campo oculto). La tabla
        // qr_tokens no almacena mesero_id, por lo que este valor solo viaja en
        // la URL. Si no se envía o es <= 0, se considera inválido.
        $meseroId = isset($_POST['mesero']) ? (int)$_POST['mesero'] : 0;
        if ($meseroId <= 0) {
            header("Location: ../Views/cliente/loginCliente.php?token=$token&error=mesero");
            exit;
        }
        // Validar datos de entrada. Aunque el formulario en loginCliente.php tiene
        // atributos `required` en los campos de nombre y email, es posible que
        // un usuario eluda esa validación o que surja algún problema en el envío.
        // Verificamos en el servidor que ambos valores no estén vacíos antes de
        // continuar. Si falta alguno, redirigimos con el error `no_data` para
        // que se muestre el mensaje correspondiente en la vista de ingreso.
        if (trim($nombre) === '' || trim($email) === '') {
            header("Location: ../Views/cliente/loginCliente.php?token=$token&error=no_data");
            exit;
        }
        // Registrar cliente
        $clienteId = ClienteModel::crearCliente($nombre, $email);
        $_SESSION['cliente_id'] = $clienteId;
        // Crear sesión de mesa
        $sesionId = SesionModel::crearSesion($qrTokenId, $clienteId);
        $_SESSION['sesion_mesa_id'] = $sesionId;
        // Asignar mesero a la sesión
        if ($meseroId > 0) {
            Database::execute(
                "INSERT INTO asignacion_mesero (sesion_id, empleado_id, asignado_desde) VALUES (?, ?, NOW())",
                [ $sesionId, $meseroId ]
            );
        }
        // Crear pedido asociado a la sesión
        $pedidoId = PedidoModel::crearPedido($sesionId, $meseroId);
        $_SESSION['pedido_id'] = $pedidoId;
        // Marcar la mesa como ocupada
        MesaModel::actualizarEstadoMesa($mesaId, 'ocupada');
        // Redirigir al menú de productos con parámetro de mesa
        header("Location: ../Views/cliente/menu.php?mesa=$mesaId");
        exit;
    }

    /**
     * Agrega un producto al pedido en curso. Valida que haya un pedido y
     * sesión activos antes de insertar el detalle. Luego recalcula el
     * total del pedido y retorna al menú.
     *
     * @param int $productoId ID del producto a agregar.
     */
    public static function agregarProducto(int $productoId): void {
        if (!isset($_SESSION['pedido_id']) || !isset($_SESSION['sesion_mesa_id'])) {
            // Sesión no válida
            header("Location: ../Views/cliente/loginCliente.php");
            exit;
        }
        // Obtener precio del producto
        $prod = Database::queryOne("SELECT precio_unitario FROM productos WHERE id = ?", [ $productoId ]);
        $precioUnit = $prod ? (float)$prod['precio_unitario'] : 0.0;
        // Insertar detalle (por defecto cantidad 1)
        DetallePedidoModel::agregarItem($_SESSION['pedido_id'], $productoId, 1, $precioUnit);
        // Recalcular total
        PedidoModel::calcularTotal($_SESSION['pedido_id']);
        // Regresar al menú
        header("Location: ../Views/cliente/menu.php?mesa=continua");
        exit;
    }

    /**
     * Marca el pedido como entregado para indicar que el cliente ha solicitado
     * la cuenta. Posteriormente la mesa se liberará en la zona
     * administrativa.
     */
    public static function solicitarCuenta(): void {
        if (isset($_SESSION['pedido_id'])) {
            // Cambiar estado a 'entregado'
            PedidoModel::actualizarEstado($_SESSION['pedido_id'], 'entregado');
        }
        header('Location: ../Views/cliente/solicitud_cuenta.php');
        exit;
    }
}

// Manejo de peticiones POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Registro inicial de cliente (nombre/email)
    if (isset($_POST['token'], $_POST['nombre'], $_POST['email'])) {
        ClienteController::registrarIngreso($_POST['token'], trim($_POST['nombre']), trim($_POST['email']));
    }
    // Agregar producto al pedido
    if (isset($_POST['add_product_id'])) {
        ClienteController::agregarProducto((int)$_POST['add_product_id']);
    }
    // Solicitar cuenta (cerrar pedido)
    if (isset($_POST['solicitar_cuenta'])) {
        ClienteController::solicitarCuenta();
    }
}