<?php
// repository/Controllers/MeseroController.php
// Controlador con acciones específicas para el rol de mesero: generar
// códigos QR para las mesas, confirmar pedidos (enviar a cocina) y
// marcar un pedido como entregado/librar la mesa.

require_once __DIR__ . '/../Models/MesaModel.php';
require_once __DIR__ . '/../Models/PedidoModel.php';
require_once __DIR__ . '/../Models/SesionModel.php';
require_once __DIR__ . '/../Models/Database.php';

use Models\MesaModel;
use Models\PedidoModel;
use Models\SesionModel;
use Models\Database;

session_start();

class MeseroController {
    /**
     * Genera un token QR para la mesa seleccionada y marca la mesa como
     * ocupada. Recibe el ID de la mesa como parámetro y utiliza el ID
     * del mesero almacenado en la sesión. Redirige a la vista de
     * mesas incluyendo el token generado para mostrar el modal.
     *
     * @param int $mesaId ID de la mesa a asignar.
     */
    public static function generarQR(int $mesaId): void {
        if (!isset($_SESSION['empleado_id']) || ($_SESSION['rol'] ?? 0) != 1) {
            // Solo los meseros pueden generar QR
            header('Location: ../Views/login.php');
            exit;
        }
        $meseroId = (int)$_SESSION['empleado_id'];
        // Generar el token y registrar en DB
        $token = MesaModel::generarTokenQR($mesaId, $meseroId);
        // Marcar la mesa como ocupada
        MesaModel::actualizarEstadoMesa($mesaId, 'ocupada');
        // Redirigir a la vista de mesas con los parámetros para mostrar el QR
        header('Location: ../Views/mesero/mesas.php?token=' . urlencode($token) . '&mesa=' . $mesaId);
        exit;
    }

    /**
     * Confirma un pedido, cambiando su estado a 'en_cocina'. Redirige
     * nuevamente a la lista de pedidos para meseros.
     *
     * @param int $pedidoId ID del pedido a confirmar.
     */
    public static function confirmarPedido(int $pedidoId): void {
        PedidoModel::actualizarEstado($pedidoId, 'en_cocina');
        header('Location: ../Views/mesero/pedidos.php');
        exit;
    }

    /**
     * Marca un pedido como entregado y libera la mesa asociada.
     *
     * @param int $pedidoId ID del pedido que se entrega.
     */
    public static function entregarPedido(int $pedidoId): void {
        // Cambiar el estado del pedido a entregado
        PedidoModel::actualizarEstado($pedidoId, 'entregado');
        // Obtener la mesa asociada al pedido
        $pedido = PedidoModel::obtenerPedido($pedidoId);
        if ($pedido) {
            $sesionId = (int)$pedido['sesion_id'];
            $row = Database::queryOne(
                "SELECT m.id AS mesa_id FROM sesiones_mesa sm
                 JOIN qr_tokens qt ON qt.id = sm.qr_token_id
                 JOIN mesas m ON m.id = qt.mesa_id
                 WHERE sm.id = ?",
                [ $sesionId ]
            );
            if ($row && isset($row['mesa_id'])) {
                MesaModel::actualizarEstadoMesa((int)$row['mesa_id'], 'libre');
            }
        }
        header('Location: ../Views/mesero/pedidos.php');
        exit;
    }
}

// Manejar acciones vía parámetros GET para comodidad. Se usa GET
// porque estas acciones son accesibles mediante enlaces en la UI.
if (isset($_GET['accion'])) {
    $accion = $_GET['accion'];
    if ($accion === 'generar_qr' && isset($_GET['mesa'])) {
        MeseroController::generarQR((int)$_GET['mesa']);
    }
    if ($accion === 'confirmar' && isset($_GET['pedido'])) {
        MeseroController::confirmarPedido((int)$_GET['pedido']);
    }
    if ($accion === 'entregar' && isset($_GET['pedido'])) {
        MeseroController::entregarPedido((int)$_GET['pedido']);
    }
}