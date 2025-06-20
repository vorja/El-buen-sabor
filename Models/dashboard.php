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


     public function getPedidosHoy()
    {
        $query = "SELECT count(*) as total FROM pagos WHERE DATE(creado) = CURDATE()";
        $result = $this->mysql->efectuarConsulta($query);
        $row = $result ? mysqli_fetch_assoc($result) : ["total" => 0];
        return ["pedidos_hoy" => (int)$row["total"]];
    }

    public function getVentasHoyComparado()
{
    $queryHoy = "SELECT SUM(monto) AS total FROM pagos WHERE DATE(creado) = CURDATE()";
    $resultHoy = $this->mysql->efectuarConsulta($queryHoy);
    $rowHoy = $resultHoy ? mysqli_fetch_assoc($resultHoy) : ["total" => 0];
    $ventasHoy = (float)$rowHoy["total"];

    $queryAyer = "SELECT SUM(monto) AS total FROM pagos WHERE DATE(creado) = CURDATE() - INTERVAL 1 DAY";
    $resultAyer = $this->mysql->efectuarConsulta($queryAyer);
    $rowAyer = $resultAyer ? mysqli_fetch_assoc($resultAyer) : ["total" => 0];
    $ventasAyer = (float)$rowAyer["total"];

    if ($ventasAyer > 0) {
        $porcentaje = (($ventasHoy - $ventasAyer) / $ventasAyer) * 100;
    } elseif ($ventasHoy > 0) {
        $porcentaje = 100;
    } else {
        $porcentaje = 0;
    }

    return [
        "ventas_hoy" => $ventasHoy,
        "ventas_ayer" => $ventasAyer,
        "variacion_porcentual" => round($porcentaje, 2)
    ];
}




}

?>
