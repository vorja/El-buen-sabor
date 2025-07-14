<?php
namespace Models;
require_once __DIR__ . '/Database.php';
use Models\Database;

class PedidoModel {
    // Crear un nuevo pedido (en estado 'en_progreso') para una sesión de mesa
    public static function crearPedido($sesionId, $meseroId) {
        $sql = "INSERT INTO pedidos (sesion_id, mesero_id, estado, creado) VALUES (?, ?, 'en_progreso', NOW())";
        Database::execute($sql, [ $sesionId, $meseroId ]);
        return Database::getConnection()->lastInsertId();
    }

    // Cambiar el estado de un pedido (en_progreso -> en_cocina -> entregado -> cerrado)
    public static function actualizarEstado($pedidoId, $nuevoEstado) {
        $sql = "UPDATE pedidos SET estado = ? WHERE id = ?";
        return Database::execute($sql, [ $nuevoEstado, $pedidoId ]);
    }

    // Calcular total de un pedido sumando sus detalles
    public static function calcularTotal($pedidoId) {
        $sql = "SELECT SUM(cantidad * precio_unit) as total FROM detalles_pedido WHERE pedido_id = ?";
        $result = Database::queryOne($sql, [ $pedidoId ]);
        $total = $result ? floatval($result['total']) : 0;
        // Actualizar el campo total en pedidos
        Database::execute("UPDATE pedidos SET total = ? WHERE id = ?", [ $total, $pedidoId ]);
        return $total;
    }

    // Obtener pedido activo de una sesión (debería haber solo uno por sesión abierta)
    public static function obtenerPedidoActivoPorSesion($sesionId) {
        $sql = "SELECT * FROM pedidos WHERE sesion_id = ? AND estado IN ('en_progreso','en_cocina','entregado')";
        return Database::queryOne($sql, [ $sesionId ]);
    }

    // Obtener un pedido por ID (por ejemplo, para ver detalles antes de cobrar)
    public static function obtenerPedido($pedidoId) {
        $sql = "SELECT * FROM pedidos WHERE id = ?";
        return Database::queryOne($sql, [ $pedidoId ]);
    }
}
