<?php
require_once __DIR__ . '/../Models/MesaModel.php';
require_once __DIR__ . '/../Models/PedidoModel.php';
require_once __DIR__ . '/../Models/SesionModel.php';
require_once __DIR__ . '/../Models/Database.php';
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
        // Marcar la mesa como ocupada mientras el pedido esté activo
        Models\MesaModel::actualizarEstadoMesa($mesaId, 'ocupada');
        // Redirigir de nuevo a la vista de mesas con el token generado en query param
        // Construimos una ruta absoluta para evitar ambigüedad en la resolución de URLs.
        // Este proyecto se despliega bajo la carpeta "el-buen-sabor" (en minúsculas),
        // por lo que la URL absoluta debe comenzar con "/el-buen-sabor/".
        header("Location: /el-buen-sabor/Views/mesero/mesas.php?token=$token&mesa=$mesaId");
        exit;
    }

    // Confirmar el pedido (enviar a cocina)
    public static function confirmarPedido($pedidoId) {
        Models\PedidoModel::actualizarEstado($pedidoId, 'en_cocina');
        // (Aquí podríamos notificar a cocina, ej. vía actualización visible en pantalla de cocina)
        // Redirigir de regreso a la vista de pedidos del mesero utilizando ruta absoluta
        header("Location: /el-buen-sabor/Views/mesero/pedido.php");
        exit;
    }

    // Marcar el pedido como entregado al cliente
    public static function entregarPedido($pedidoId) {
        // Cambiar el estado del pedido a entregado
        Models\PedidoModel::actualizarEstado($pedidoId, 'entregado');
        // Liberar la mesa asociada al pedido
        // Obtener la sesión y el token para encontrar la mesa
        $pedido = Models\PedidoModel::obtenerPedido($pedidoId);
        if ($pedido) {
            $sesionId = $pedido['sesion_id'];
            // Consultar la mesa asociada a la sesión
            $row = Models\Database::queryOne(
                "SELECT m.id AS mesa_id FROM sesiones_mesa sm
                 JOIN qr_tokens qt ON qt.id = sm.qr_token_id
                 JOIN mesas m ON m.id = qt.mesa_id
                 WHERE sm.id = ?",
                [ $sesionId ]
            );
            if ($row && isset($row['mesa_id'])) {
                Models\MesaModel::actualizarEstadoMesa((int)$row['mesa_id'], 'libre');
            }
        }
        // Redirigir de regreso a la vista de pedidos del mesero utilizando ruta absoluta
        header("Location: /el-buen-sabor/Views/mesero/pedido.php");
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
