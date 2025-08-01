<?php
require_once __DIR__ . '/../Models/ClienteModel.php';
require_once __DIR__ . '/../Models/MesaModel.php';
require_once __DIR__ . '/../Models/SesionModel.php';
require_once __DIR__ . '/../Models/PedidoModel.php';
session_start();

class ClienteController {
    public static function registrarIngreso($token, $nombre, $email) {
        // Buscar el token en la BD
        $tokenData = Models\MesaModel::obtenerToken($token);
        if (!$tokenData) {
            // Token inválido o expirado
             header("Location: ../Views/cliente/loginCliente.php?token=$token&error=token");
            exit;
        }
        $mesaId   = $tokenData['mesa_id'];
        $qrTokenId = $tokenData['id'];
        // Obtener id del mesero desde el formulario (parámetro oculto). La tabla qr_tokens
        // no tiene una columna mesero_id. Si no se envía el parámetro o es 0, el QR es inválido.
        $meseroId = isset($_POST['mesero']) ? intval($_POST['mesero']) : 0;
        if ($meseroId <= 0) {
            header("Location: ../Views/cliente/loginCliente.php?token=$token&error=mesero");
            exit;
        }

        // Registrar cliente en la BD
        $clienteId = Models\ClienteModel::crearCliente($nombre, $email);
        $_SESSION['cliente_id'] = $clienteId;

        // Crear sesión de mesa para este cliente
        $sesionId = Models\SesionModel::crearSesion($qrTokenId, $clienteId);
        $_SESSION['sesion_mesa_id'] = $sesionId;

        // Asignar mesero a la sesión (registrar en asignacion_mesero) solo si hay un mesero válido
        if ($meseroId > 0) {
            Models\Database::execute(
                "INSERT INTO asignacion_mesero (sesion_id, empleado_id, asignado_desde) VALUES (?, ?, NOW())",
                [ $sesionId, $meseroId ]
            );
        }

        // Crear un pedido asociado a esta sesión, utilizando el id del mesero si está disponible
        $pedidoId = Models\PedidoModel::crearPedido($sesionId, $meseroId);
        $_SESSION['pedido_id'] = $pedidoId;

        // Marcar la mesa como ocupada
        Models\MesaModel::actualizarEstadoMesa($mesaId, 'ocupada');

        // Redirigir al menú de productos para que el cliente haga pedidos
        header("Location: ../Views/cliente/menu.php?mesa=$mesaId");
        exit;
    }

    // Procesar pedido de un producto por el cliente (agregar item al pedido en curso)
    public static function agregarProducto($productoId) {
        if (!isset($_SESSION['pedido_id']) || !isset($_SESSION['sesion_mesa_id'])) {
            // Sesión no válida
            header("Location: ../Views/cliente/loginCliente.php");
            exit;
        }
        // Obtener precio actual del producto
        $prod = Models\Database::queryOne("SELECT precio_unitario FROM productos WHERE id = ?", [ $productoId ]);
        $precioUnit = $prod ? floatval($prod['precio_unitario']) : 0;
        // Añadir el producto con cantidad 1 (asumiendo un pedido por clic)
        Models\DetallePedidoModel::agregarItem($_SESSION['pedido_id'], $productoId, 1, $precioUnit);
        // Recalcular total (opcional, se puede hacer al cerrar pedido también)
        Models\PedidoModel::calcularTotal($_SESSION['pedido_id']);
        // Permanecer en la página del menú (quizás con mensaje de éxito o actualizando la lista)
        header("Location: ../Views/cliente/menu.php?mesa=continua");
        exit;
    }

    // El cliente indica que desea cerrar el pedido (solicitar pago)
    public static function solicitarCuenta() {
        if (isset($_SESSION['pedido_id'])) {
            // Opcional: cambiar estado del pedido a 'entregado' para indicar que está listo para pago
            Models\PedidoModel::actualizarEstado($_SESSION['pedido_id'], 'entregado');
        }
        // Redirigir a una vista de confirmación
        header("Location: ../Views/cliente/solicitud_cuenta.php");
        exit;
    }
}

// Manejo de peticiones
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Si viene del formulario de registro de cliente (ingreso nombre/email)
    if (isset($_POST['token'], $_POST['nombre'], $_POST['email'])) {
        ClienteController::registrarIngreso($_POST['token'], trim($_POST['nombre']), trim($_POST['email']));
    }
    // Si viene de agregar producto (formulario oculto al dar clic en "Pedir")
    if (isset($_POST['add_product_id'])) {
        ClienteController::agregarProducto($_POST['add_product_id']);
    }
    // Si el cliente pulsa "Cerrar Pedido" para solicitar la cuenta
    if (isset($_POST['solicitar_cuenta'])) {
        ClienteController::solicitarCuenta();
    }
}
