<?php
// Desactivar la salida de errores de PHP
error_reporting(0);
ini_set('display_errors', 0);

// Asegurar que siempre se envíe JSON
header('Content-Type: application/json');

require_once 'MySQL.php';

class Inventario
{
    private $mysql;

    public function __construct($mysql)
    {
        $this->mysql = $mysql;
    }

    public function getCantidadProductos()
    {
        $query_ingredientes = "SELECT count(*) as total FROM inventario_ingrediente";
        $result_ingredientes = $this->mysql->efectuarConsulta($query_ingredientes);
        $row_ingredientes = $result_ingredientes ? mysqli_fetch_assoc($result_ingredientes) : ["total" => 0];

        $query_productos = "SELECT count(*) as total FROM inventario_producto";
        $result_productos = $this->mysql->efectuarConsulta($query_productos);
        $row_productos = $result_productos ? mysqli_fetch_assoc($result_productos) : ["total" => 0];

        $total = (int)$row_ingredientes["total"] + (int)$row_productos["total"];
        return ["total_productos" => $total];
    }
    
    public function getProductosStockNormal()
    {
        $query_ingredientes = "SELECT count(*) as total FROM inventario_ingrediente WHERE stock>=stock_minimo";
        $result_ingredientes = $this->mysql->efectuarConsulta($query_ingredientes);
        $row_ingredientes = $result_ingredientes ? mysqli_fetch_assoc($result_ingredientes) : ["total" => 0];

        $query_productos = "SELECT count(*) as total FROM inventario_producto WHERE stock>=stock_minimo";
        $result_productos = $this->mysql->efectuarConsulta($query_productos);
        $row_productos = $result_productos ? mysqli_fetch_assoc($result_productos) : ["total" => 0];
        
        $total = (int)$row_ingredientes["total"] + (int)$row_productos["total"];
        return ["stock_normal" => $total];
    }
    
    public function getProductosStockBajo()
    {
        $query_ingredientes = "SELECT count(*) as total FROM inventario_ingrediente WHERE stock<stock_minimo and stock>stock_minimo/2";
        $result_ingredientes = $this->mysql->efectuarConsulta($query_ingredientes);
        $row_ingredientes = $result_ingredientes ? mysqli_fetch_assoc($result_ingredientes) : ["total" => 0];

        $query_productos = "SELECT count(*) as total FROM inventario_producto WHERE stock<stock_minimo and stock>stock_minimo/2";
        $result_productos = $this->mysql->efectuarConsulta($query_productos);
        $row_productos = $result_productos ? mysqli_fetch_assoc($result_productos) : ["total" => 0];
        
        $total = (int)$row_ingredientes["total"] + (int)$row_productos["total"];
        return ["stock_bajo" => $total];
    }
    
    public function getProductosStockCritico()
    {
        $query_ingredientes = "SELECT count(*) as total FROM inventario_ingrediente WHERE stock <= stock_minimo/2";
        $result_ingredientes = $this->mysql->efectuarConsulta($query_ingredientes);
        $row_ingredientes = $result_ingredientes ? mysqli_fetch_assoc($result_ingredientes) : ["total" => 0];

        $query_productos = "SELECT count(*) as total FROM inventario_producto WHERE stock <= stock_minimo/2";
        $result_productos = $this->mysql->efectuarConsulta($query_productos);
        $row_productos = $result_productos ? mysqli_fetch_assoc($result_productos) : ["total" => 0];

        $total = (int)$row_ingredientes["total"] + (int)$row_productos["total"];
        return ["stock_critico" => $total];
    }

    public function getIngredientes($estado_stock = null, $search = null)
    {
        $where = [];

        if ($estado_stock !== null && $estado_stock !== '') {
            if ($estado_stock === 'normal') {
                $where[] = "ii.stock >= ii.stock_minimo";
            } elseif ($estado_stock === 'bajo') {
                $where[] = "ii.stock < ii.stock_minimo AND ii.stock > ii.stock_minimo/2";
            } elseif ($estado_stock === 'critico') {
                $where[] = "ii.stock <= ii.stock_minimo/2";
            }
        }

        if ($search !== null && $search !== '') {
            $escaped_search = $this->mysql->escape_string($search);
            $where[] = "i.nombre LIKE '%{$escaped_search}%'";
        }

        $where_sql = '';
        if (count($where) > 0) {
            $where_sql = 'WHERE ' . implode(' AND ', $where);
        }
        
        $query = "SELECT i.id, i.nombre, u.abreviatura AS unidad, ii.stock AS stock, ii.stock_minimo AS stock_minimo,
        ii.actualizado AS actualizado FROM ingredientes i INNER JOIN inventario_ingrediente ii ON i.id = ii.ingrediente_id
        INNER JOIN unidades u ON i.unidad_id = u.id " . $where_sql . ";";
        $result = $this->mysql->efectuarConsulta($query);
        $ingredientes = [];
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $ingredientes[] = $row;
            }
        }
        return $ingredientes;
    }

    public function getInventarioPorCategoriayStock($categoria_id = null, $estado_stock = null)
    {
        $where = [];
        if ($categoria_id !== null) {
            $where[] = "i.categoria_id = " . intval($categoria_id);
        }
        if ($estado_stock !== null) {
            if ($estado_stock === 'normal') {
                $where[] = "ii.stock >= ii.stock_minimo";
            } elseif ($estado_stock === 'bajo') {
                $where[] = "ii.stock < ii.stock_minimo AND ii.stock > ii.stock_minimo/2";
            } elseif ($estado_stock === 'critico') {
                $where[] = "ii.stock <= ii.stock_minimo - ii.stock_minimo/2";
            }
        }
        $where_sql = '';
        if (count($where) > 0) {
            $where_sql = 'WHERE ' . implode(' AND ', $where);
        }
        $query = "SELECT i.id AS id, i.nombre AS nombre, u.abreviatura AS unidad, ii.stock AS stock, ii.stock_minimo AS stock_minimo, ii.actualizado AS actualizado FROM ingredientes i INNER JOIN inventario_ingrediente ii ON i.id = ii.ingrediente_id INNER JOIN unidades u ON i.unidad_id = u.id " . $where_sql . ";";
        $result = $this->mysql->efectuarConsulta($query);
        $ingredientes = [];
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $ingredientes[] = $row;
            }
        }
        return $ingredientes;
    }  
}