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
        $query = "SELECT count(*) as total FROM inventario_ingrediente";
        $result = $this->mysql->efectuarConsulta($query);
        $row = $result ? mysqli_fetch_assoc($result) : ["total" => 0];
        return ["total_productos" => (int)$row["total"]];
    }
    
    public function getProductosStockNormal()
    {
        $query = "SELECT count(*) as total FROM inventario_ingrediente WHERE stock>=stock_minimo";
        $result = $this->mysql->efectuarConsulta($query);
        $row = $result ? mysqli_fetch_assoc($result) : ["total" => 0];
        return ["stock_normal" => (int)$row["total"]];
    }
    
    public function getProductosStockBajo()
    {
        $query = "SELECT count(*) as total FROM inventario_ingrediente WHERE stock<stock_minimo and stock>stock_minimo/2";
        $result = $this->mysql->efectuarConsulta($query);
        $row = $result ? mysqli_fetch_assoc($result) : ["total" => 0];
        return ["stock_bajo" => (int)$row["total"]];
    }
    
    public function getProductosStockCritico()
    {
        $query = "SELECT count(*) as total FROM inventario_ingrediente WHERE stock<=stock_minimo-stock_minimo/2";
        $result = $this->mysql->efectuarConsulta($query);
        $row = $result ? mysqli_fetch_assoc($result) : ["total" => 0];
        return ["stock_critico" => (int)$row["total"]];
    }

    public function getIngredientes()
    {
        $query = "SELECT i.id AS id, i.nombre AS nombre, u.abreviatura AS unidad, ii.stock AS stock, ii.stock_minimo AS stock_minimo,
        ii.actualizado AS actualizado FROM ingredientes i INNER JOIN inventario_ingrediente ii ON i.id = ii.ingrediente_id
        INNER JOIN unidades u ON i.unidad_id = u.id;";
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