<?php
namespace Models;
require_once __DIR__ . '/Database.php';
use Models\Database;

class ProductoModel {
    public static function obtenerProductos(bool $soloActivos = true): array {
        $sql = "
          SELECT 
            pr.id,
            pr.nombre,
            pr.descripcion,
            pr.precio_unitario,
            pr.stock,
            pr.disponibilidad,
            pr.tipo_inventario,
            pr.stock_minimo,
            c.nombre AS categoria
          FROM productos pr
          LEFT JOIN categorias c ON pr.categoria_id = c.id
        ";
        if ($soloActivos) {
            $sql .= " WHERE pr.activo = 1";
        }
        $sql .= " ORDER BY pr.nombre";
        return Database::queryAll($sql);
    }
}
