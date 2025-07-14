<?php
require_once __DIR__ . '/../Models/Database.php';
session_start();

class StatsController {
    public static function obtenerEstadisticasPrincipales() {
        // Asegurar que solo admin pueda obtener (opcional, según necesidad)
        if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 2) {
            http_response_code(403);
            echo json_encode([ 'success' => false, 'message' => 'Forbidden' ]);
            exit;
        }
        // Consultar las métricas:
        $hoy = date('Y-m-d');
        $stats = [];
        // Ventas de Hoy (suma de montos de pedidos cerrados hoy)
        $row = Models\Database::queryOne(
            "SELECT SUM(total) as total_hoy, COUNT(*) as pedidos_hoy 
             FROM pedidos 
             WHERE estado = 'cerrado' AND DATE(creado) = ?", [ $hoy ]
        );
        $stats['ventas_hoy'] = $row ? floatval($row['total_hoy']) : 0;
        $stats['pedidos_hoy'] = $row ? intval($row['pedidos_hoy']) : 0;
        // Pedidos Activos (en progreso + en cocina + entregado actualmente)
        $row2 = Models\Database::queryOne(
            "SELECT COUNT(*) as activos 
             FROM pedidos 
             WHERE estado IN ('en_progreso','en_cocina','entregado')"
        );
        $stats['pedidos_activos'] = $row2 ? intval($row2['activos']) : 0;
        // Clientes atendidos hoy (número de sesiones cerradas hoy, es decir, mesas atendidas)
        $row3 = Models\Database::queryOne(
            "SELECT COUNT(*) as clientes_hoy 
             FROM sesiones_mesa 
             WHERE cerrada = 1 AND DATE(expira) = ?", [ $hoy ]
        );
        $stats['clientes_atendidos'] = $row3 ? intval($row3['clientes_hoy']) : 0;
        // Producto más vendido hoy (mayor cantidad total en detalles_pedido hoy)
        $row4 = Models\Database::queryOne(
            "SELECT p.nombre, SUM(dp.cantidad) as cant 
             FROM detalles_pedido dp
             JOIN pedidos pd ON dp.pedido_id = pd.id
             JOIN productos p ON dp.producto_id = p.id
             WHERE pd.estado = 'cerrado' AND DATE(pd.creado) = ?
             GROUP BY dp.producto_id 
             ORDER BY cant DESC 
             LIMIT 1", [ $hoy ]
        );
        if ($row4) {
            $stats['producto_popular'] = $row4['nombre'];
            $stats['cantidad_vendida'] = intval($row4['cant']);
        } else {
            $stats['producto_popular'] = 'Ninguno';
            $stats['cantidad_vendida'] = 0;
        }
        // Variación porcentual vs ayer (ventas)
        $ayer = date('Y-m-d', strtotime('-1 day'));
        $row5 = Models\Database::queryOne(
            "SELECT SUM(total) as total_ayer 
             FROM pedidos 
             WHERE estado = 'cerrado' AND DATE(creado) = ?", [ $ayer ]
        );
        $totalAyer = $row5 && $row5['total_ayer'] ? floatval($row5['total_ayer']) : 0;
        $stats['variacion_porcentual'] = $totalAyer > 0 
            ? round((($stats['ventas_hoy'] - $totalAyer) / $totalAyer) * 100, 1) 
            : 0;
        // Responder en JSON
        header('Content-Type: application/json');
        echo json_encode([ 'success' => true, 'data' => $stats ]);
        exit;
    }
}

// Si se llama mediante ?action=getStats, ejecutamos
if (isset($_GET['action']) && $_GET['action'] === 'getStats') {
    StatsController::obtenerEstadisticasPrincipales();
}
