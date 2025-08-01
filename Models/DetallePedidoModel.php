<?php
// repository/Models/DetallePedidoModel.php
// Modelo para gestionar las líneas de detalle de cada pedido. Cada
// línea indica un producto, la cantidad y el precio unitario al
// momento de realizar la orden.

namespace Models;

require_once __DIR__ . '/Database.php';
use Models\Database;

class DetallePedidoModel {
    /**
     * Inserta un nuevo detalle en el pedido. Devuelve true/false según
     * la ejecución de la sentencia. Asume que la integridad referencial
     * (pedido y producto) ya está asegurada en la base de datos.
     *
     * @param int $pedidoId ID del pedido.
     * @param int $productoId ID del producto.
     * @param int $cantidad Cantidad solicitada.
     * @param float $precioUnit Precio unitario del producto al momento.
     * @return bool
     */
    public static function agregarItem(int $pedidoId, int $productoId, int $cantidad, float $precioUnit): bool {
        $sql = "INSERT INTO detalles_pedido (pedido_id, producto_id, cantidad, precio_unit) VALUES (?, ?, ?, ?)";
        return Database::execute($sql, [ $pedidoId, $productoId, $cantidad, $precioUnit ]);
    }

    /**
     * Devuelve todos los detalles de un pedido. Incluye el nombre del producto
     * mediante una unión con la tabla productos para facilitar la
     * presentación.
     *
     * @param int $pedidoId ID del pedido.
     * @return array Listado de detalles.
     */
    public static function obtenerDetalles(int $pedidoId): array {
        $sql = "SELECT dp.id, dp.cantidad, dp.precio_unit, p.nombre
                FROM detalles_pedido dp
                JOIN productos p ON dp.producto_id = p.id
                WHERE dp.pedido_id = ?";
        return Database::queryAll($sql, [ $pedidoId ]);
    }

    /**
     * Elimina un detalle del pedido. Útil si se anula un producto.
     *
     * @param int $detalleId ID del detalle.
     * @return bool
     */
    public static function eliminarItem(int $detalleId): bool {
        return Database::execute("DELETE FROM detalles_pedido WHERE id = ?", [ $detalleId ]);
    }
}