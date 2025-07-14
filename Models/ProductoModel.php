<?php
// Models/ProductoModel.php
namespace Models;
require_once __DIR__ . '/Database.php';

use Models\Database;

class ProductoModel {
  public static function crear(array $d): void {
    $sql = "INSERT INTO productos
      (categoria_id,nombre,descripcion,imagen,activo,tipo_inventario,stock,disponibilidad,precio_unitario)
     VALUES (?,?,?,?,1,?,?,?,?)";
    Database::execute(
      $sql,
      [
        $d['categoria_id'],
        $d['nombre'],
        $d['descripcion'] ?? null,
        $d['imagen']      ?? null,
        $d['tipo_inventario'],
        $d['stock'],
        $d['disponibilidad'],
        $d['precio_unitario']
      ]
    );
  }

  public static function actualizar(int $id, array $d): void {
    $sql = "UPDATE productos SET
        categoria_id=?, nombre=?, descripcion=?, imagen=?, tipo_inventario=?, stock=?, disponibilidad=?, precio_unitario=?
      WHERE id=?";
    Database::execute(
      $sql,
      [
        $d['categoria_id'],
        $d['nombre'],
        $d['descripcion'] ?? null,
        $d['imagen']      ?? null,
        $d['tipo_inventario'],
        $d['stock'],
        $d['disponibilidad'],
        $d['precio_unitario'],
        $id
      ]
    );
  }

  public static function borrar(int $id): void {
    Database::execute("DELETE FROM productos WHERE id=?", [$id]);
  }
}
