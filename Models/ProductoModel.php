<?php
// repository/Models/ProductoModel.php
// Este modelo encapsula las operaciones de lectura y escritura de
// productos.  Además de los métodos de creación y actualización
// existentes en la base de código original, aquí añadimos
// utilidades de consulta que devuelven los productos agrupados por
// categoría y opcionalmente filtrados por disponibilidad.

namespace Models;

require_once __DIR__ . '/Database.php';

class ProductoModel {
    /**
     * Obtiene todos los productos activos.  Cuando
     * $soloDisponibles es true, sólo se devuelven aquellos
     * productos cuya columna `disponibilidad` sea "disponible".
     * El resultado se ordena por nombre para que el menú tenga un
     * aspecto consistente.
     *
     * @param bool $soloDisponibles
     * @return array
     */
    public static function obtenerProductos(bool $soloDisponibles = false): array {
        $sql = "SELECT p.*, c.nombre AS categoria_nombre
                  FROM productos p
                  LEFT JOIN categorias c ON c.id = p.categoria_id
                 WHERE p.activo = 1";
        $params = [];
        if ($soloDisponibles) {
            $sql .= " AND p.disponibilidad = 'disponible'";
        }
        $sql .= " ORDER BY c.nombre, p.nombre";
        return Database::queryAll($sql, $params);
    }

    /**
     * Devuelve los productos agrupados por categoría.  Cada entrada
     * del array devuelto contiene el nombre de la categoría y la
     * lista de productos pertenecientes a la misma.  Este método es
     * útil para construir menús de navegación basados en pestañas.
     *
     * @param bool $soloDisponibles
     * @return array
     */
    public static function obtenerProductosPorCategoria(bool $soloDisponibles = false): array {
        $productos = self::obtenerProductos($soloDisponibles);
        $agrupados = [];
        foreach ($productos as $prod) {
            $cat = $prod['categoria_nombre'] ?? 'Sin categoría';
            if (!isset($agrupados[$cat])) {
                $agrupados[$cat] = [];
            }
            $agrupados[$cat][] = $prod;
        }
        return $agrupados;
    }

    /**
     * Crea un nuevo producto en la base de datos.  Este método
     * implementa la misma lógica que el código original, pero aquí
     * se documenta con parámetros nombrados para mejorar la
     * legibilidad.  Devuelve void porque se confía en exceptions
     * lanzadas por PDO para el control de errores.
     */
    public static function crear(array $d): void {
        $sql = "INSERT INTO productos
      (categoria_id,nombre,descripcion,imagen,activo,
       tipo_inventario,stock,disponibilidad,precio_unitario)
     VALUES (?,?,?,?,1,?,?,?,?)";
        Database::execute($sql, [
            $d['categoria_id'],
            $d['nombre'],
            $d['descripcion'],
            $d['imagen'],
            $d['tipo_inventario'],
            $d['stock'],
            $d['disponibilidad'],
            $d['precio_unitario'],
        ]);
    }

    /**
     * Actualiza un producto existente.  Las claves de $d deben
     * coincidir con las columnas de la tabla productos.
     */
    public static function actualizar(int $id, array $d): void {
        $sql = "UPDATE productos SET
        categoria_id=?, nombre=?, descripcion=?, imagen=?,
        tipo_inventario=?, stock=?, disponibilidad=?, precio_unitario=?
      WHERE id=?";
        Database::execute($sql, [
            $d['categoria_id'],
            $d['nombre'],
            $d['descripcion'],
            $d['imagen'],
            $d['tipo_inventario'],
            $d['stock'],
            $d['disponibilidad'],
            $d['precio_unitario'],
            $id,
        ]);
    }

    /**
     * Elimina un producto por id.  Se usa en panel de administración.
     */
    public static function borrar(int $id): void {
        Database::execute("DELETE FROM productos WHERE id=?", [$id]);
    }
}