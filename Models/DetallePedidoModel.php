<?php
namespace Models;
require_once __DIR__ . '/Database.php';
use Models\Database;

class DetallePedidoModel {
    // Agregar un producto al pedido (devuelve true/false según éxito)
    public static function agregarItem($pedidoId, $productoId, $cantidad, $precioUnit) {
        $sql = "INSERT INTO detalles_pedido (pedido_id, producto_id, cantidad, precio_unit) 
                VALUES (?, ?, ?, ?)";
        return Database::execute($sql, [ $pedidoId, $productoId, $cantidad, $precioUnit ]);
    }

    // Listar todos los ítems de un pedido (para mostrar al mesero o cliente)
    public static function obtenerDetalles($pedidoId) {
        $sql = "SELECT dp.id, dp.cantidad, dp.precio_unit, p.nombre 
                FROM detalles_pedido dp
                JOIN productos p ON dp.producto_id = p.id
                WHERE dp.pedido_id = ?";
        return Database::queryAll($sql, [ $pedidoId ]);
    }

    // (Opcional) Eliminar un ítem del pedido (por ejemplo, si se anula)
    public static function eliminarItem($detalleId) {
        $sql = "DELETE FROM detalles_pedido WHERE id = ?";
        return Database::execute($sql, [ $detalleId ]);
    }
}
