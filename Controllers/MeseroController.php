<?php
require_once __DIR__ . '/../Models/MesaModel.php';
require_once __DIR__ . '/../Models/PedidoModel.php';
require_once __DIR__ . '/../Models/SesionModel.php';
session_start();

class MeseroController {
    // Generar un QR para una mesa específica
    public static function generarQR($mesaId) {
        if (!isset($_SESSION['empleado_id']) || $_SESSION['rol'] != 1) {
            // No autorizado (solo meseros pueden)
            header("Location: ../Views/login.php");
            exit;
        }
        $meseroId = $_SESSION['empleado_id'];
        // Usar el modelo para generar token y guardarlo
        $token = Models\MesaModel::generarTokenQR($mesaId, $meseroId);
        // (No creamos sesión/pedido aquí; se hará cuando cliente se registre)
        // Redirigir de nuevo a la vista de mesas con el token generado en query param
        header("Location: ../Views/mesero/mesas.php?token=$token&mesa=$mesaId");
        exit;
    }

    // Confirmar el pedido (enviar a cocina)
    public static function confirmarPedido($pedidoId) {
        Models\PedidoModel::actualizarEstado($pedidoId, 'en_cocina');
        // (Aquí podríamos notificar a cocina, ej. vía actualización visible en pantalla de cocina)
        // Redirigir de regreso a la vista del pedido
        header("Location: ../Views/mesero/pedido.php?pedido=$pedidoId");
        exit;
    }

    // Marcar el pedido como entregado al cliente
    public static function entregarPedido($pedidoId) {
        Models\PedidoModel::actualizarEstado($pedidoId, 'entregado');
        // Redirigir de regreso a la vista del pedido
        header("Location: ../Views/mesero/pedido.php?pedido=$pedidoId");
        exit;
    }
}

// Detectar llamadas a acciones a través de GET (por simplicidad usamos GET para acciones rápidas)
if (isset($_GET['accion'])) {
    $accion = $_GET['accion'];
    if ($accion == 'generar_qr' && isset($_GET['mesa'])) {
        MeseroController::generarQR(intval($_GET['mesa']));
    }
    if ($accion == 'confirmar' && isset($_GET['pedido'])) {
        MeseroController::confirmarPedido(intval($_GET['pedido']));
    }
    if ($accion == 'entregar' && isset($_GET['pedido'])) {
        MeseroController::entregarPedido(intval($_GET['pedido']));
    }
}
