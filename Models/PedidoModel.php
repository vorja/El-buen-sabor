<?php
// repository/Models/PedidoModel.php
// Modelo para gestionar los pedidos que realizan los clientes. Cada
// sesión de mesa tiene un único pedido activo a la vez. Este modelo
// permite crear pedidos, actualizar su estado y calcular su total.

namespace Models;

require_once __DIR__ . '/Database.php';
use Models\Database;

class PedidoModel {
    /**
     * Crea un nuevo pedido para una sesión dada. El pedido inicia en
     * estado 'en_progreso'. Si se proporciona un mesero válido se
     * almacena en la columna mesero_id, de lo contrario puede ser NULL.
     *
     * @param int $sesionId ID de la sesión de mesa asociada.
     * @param int $meseroId ID del mesero asignado o 0.
     * @return int ID del pedido creado.
     */
    public static function crearPedido(int $sesionId, int $meseroId): int {
        $sql = "INSERT INTO pedidos (sesion_id, mesero_id, estado, creado) VALUES (?, ?, 'en_progreso', NOW())";
        Database::execute($sql, [ $sesionId, $meseroId ]);
        return (int)Database::getConnection()->lastInsertId();
    }

    /**
     * Cambia el estado de un pedido. Estados posibles: en_progreso,
     * en_cocina, entregado, cerrado.
     *
     * @param int $pedidoId ID del pedido.
     * @param string $nuevoEstado Nuevo estado.
     * @return bool
     */
    public static function actualizarEstado(int $pedidoId, string $nuevoEstado): bool {
        $sql = "UPDATE pedidos SET estado = ? WHERE id = ?";
        return Database::execute($sql, [ $nuevoEstado, $pedidoId ]);
    }

    /**
     * Calcula el total del pedido sumando las líneas de su detalle e
     * actualiza la columna total en la tabla pedidos.
     *
     * @param int $pedidoId ID del pedido.
     * @return float Importe total calculado.
     */
    public static function calcularTotal(int $pedidoId): float {
        $result = Database::queryOne(
            "SELECT SUM(cantidad * precio_unit) AS total FROM detalles_pedido WHERE pedido_id = ?",
            [ $pedidoId ]
        );
        $total = $result ? floatval($result['total']) : 0;
        Database::execute("UPDATE pedidos SET total = ? WHERE id = ?", [ $total, $pedidoId ]);
        return $total;
    }

    /**
     * Obtiene el pedido activo (en progreso, en cocina o entregado) de una
     * sesión determinada. Devuelve null si no hay un pedido activo.
     *
     * @param int $sesionId ID de la sesión.
     * @return array|null Fila del pedido o null si no existe.
     */
    public static function obtenerPedidoActivoPorSesion(int $sesionId): ?array {
        $sql = "SELECT * FROM pedidos WHERE sesion_id = ? AND estado IN ('en_progreso', 'en_cocina', 'entregado')";
        return Database::queryOne($sql, [ $sesionId ]);
    }

    /**
     * Obtiene un pedido por su ID.
     *
     * @param int $pedidoId ID del pedido.
     * @return array|null Fila del pedido.
     */
    public static function obtenerPedido(int $pedidoId): ?array {
        return Database::queryOne("SELECT * FROM pedidos WHERE id = ?", [ $pedidoId ]);
    }
}