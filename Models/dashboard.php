<?php
// Desactivar la salida de errores de PHP
error_reporting(0);
ini_set('display_errors', 0);

// Asegurar que siempre se envíe JSON
header('Content-Type: application/json');

require_once 'MySQL.php';

class Stats
{
    private $mysql;

    public function __construct($mysql)
    {
        $this->mysql = $mysql;
    }

    public function getVentasHoy()
    {
        $query = "SELECT sum(monto) as total FROM pagos WHERE DATE(creado) = CURDATE()";
        $result = $this->mysql->efectuarConsulta($query);
        $row = $result ? mysqli_fetch_assoc($result) : ["total" => 0];
        return ["ventas_hoy" => (int)$row["total"]];
    }

    public function getPedidosActivos()
    {
        $query = "SELECT COUNT(*) as total FROM pedidos WHERE estado = 'en_progreso' or estado='en_cocina'";
        $result = $this->mysql->efectuarConsulta($query);
        $row = $result ? mysqli_fetch_assoc($result) : ["total" => 0];
        return ["pedidos_activos" => (int)$row["total"]];
    }

    public function getClientesAtendidos()
    {
        $query = "SELECT COUNT(*) as total FROM pagos";
        $result = $this->mysql->efectuarConsulta($query);
        $row = $result ? mysqli_fetch_assoc($result) : ["total" => 0];
        return ["clientes_atendidos" => (int)$row["total"]];
    }

    public function getProductoPopular()
    {
        $query = "SELECT p.nombre AS producto, SUM(dp.cantidad) AS total FROM productos p
                  JOIN detalles_pedido dp ON p.id = dp.producto_id
                  GROUP BY p.id, p.nombre
                  ORDER BY total DESC LIMIT 1";
        $result = $this->mysql->efectuarConsulta($query);
        $row = $result ? mysqli_fetch_assoc($result) : ["producto" => "", "total" => 0];
        return ["producto_popular" => $row["producto"], "cantidad_vendida" => (int)$row["total"]];
    }
}

?>
