<?php
// Controllers/ReportesController.php

require_once __DIR__ . '/../Models/Database.php';
// Cargar el autoloader de Composer (para Dompdf)
require_once __DIR__ . '/../lib/vendor/autoload.php';
use Dompdf\Dompdf;
use Models\Database;

class ReportesController {
    public static function index() {
        session_start();
        if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 2) {
            header('Location: ../Views/login.php');
            exit;
        }

        $mysql = new MySQL();
        $consulta = $mysql->efectuarConsulta(
            "SELECT m.numero AS mesa, c.nombre AS cliente, p.total, p.estado, p.creado
             FROM pedidos p
             JOIN sesiones_mesa sm ON sm.id = p.sesion_id
             JOIN qr_tokens qt ON qt.id = sm.qr_token_id
             JOIN mesas m ON m.id = qt.mesa_id
             JOIN clientes c ON c.id = sm.cliente_id
             WHERE p.estado = 'cerrado'
             ORDER BY p.creado DESC"
        );

        $ventas = [];
        while ($fila = $consulta->fetch_assoc()) {
            $ventas[] = $fila;
        }
        $mysql->cerrarConexion();

        // Renderizar la vista
        require_once __DIR__ . '/../Views/admin/reportes.php';
    }

    /**
     * Genera un reporte de ventas entre fechas en PDF.
     * @param string $desde Fecha inicio (YYYY-MM-DD)
     * @param string $hasta Fecha fin (YYYY-MM-DD)
     */
    public static function generarVentasPDF(string $desde, string $hasta): void {
        // Obtener pedidos cerrados entre las fechas
        $rows = Database::queryAll(
            "SELECT p.id, p.creado, p.total, e.nombre AS mesero
             FROM pedidos p
             JOIN empleados e ON e.id = p.mesero_id
             WHERE p.estado = 'cerrado' AND DATE(p.creado) BETWEEN ? AND ?
             ORDER BY p.creado",
            [ $desde, $hasta ]
        );
        // Construir HTML
        $html = "<h2 style='text-align:center;'>Reporte de ventas del {$desde} al {$hasta}</h2>";
        $html .= "<table width='100%' border='1' cellpadding='5' cellspacing='0'>";
        $html .= "<thead><tr><th>Fecha/Hora</th><th>ID Pedido</th><th>Mesero</th><th>Total</th></tr></thead><tbody>";
        $sumaTotal = 0;
        foreach ($rows as $row) {
            $sumaTotal += $row['total'];
            $html .= "<tr>".
                     "<td>{$row['creado']}</td>".
                     "<td>{$row['id']}</td>".
                     "<td>".htmlspecialchars($row['mesero'])."</td>".
                     "<td style='text-align:right'>$".number_format($row['total'],2)."</td>".
                     "</tr>";
        }
        $html .= "<tr><td colspan='3' align='right'><strong>Total general:</strong></td><td align='right'><strong>$".number_format($sumaTotal,2)."</strong></td></tr>";
        $html .= "</tbody></table>";
        // Generar PDF
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        // Enviar PDF al navegador para descarga
        $dompdf->stream("reporte_ventas_{$desde}_{$hasta}.pdf", ["Attachment" => true]);
        exit;
    }

    /**
     * Genera un reporte de desempeño de un empleado en PDF.
     * @param int $empleadoId ID del empleado/mesero
     */
    public static function generarEmpleadoPDF(int $empleadoId): void {
        // Obtener datos del empleado y sus pedidos cerrados
        $empleado = Database::queryOne("SELECT nombre FROM empleados WHERE id = ?", [ $empleadoId ]);
        $rows = Database::queryAll(
            "SELECT id, creado, total
             FROM pedidos
             WHERE estado = 'cerrado' AND mesero_id = ?
             ORDER BY creado",
            [ $empleadoId ]
        );
        $html = "<h2 style='text-align:center;'>Reporte de desempeño del empleado: ".htmlspecialchars($empleado['nombre'])."</h2>";
        $html .= "<table width='100%' border='1' cellpadding='5' cellspacing='0'>";
        $html .= "<thead><tr><th>Fecha/Hora</th><th>ID Pedido</th><th>Total</th></tr></thead><tbody>";
        $total = 0;
        foreach ($rows as $r) {
            $total += $r['total'];
            $html .= "<tr><td>{$r['creado']}</td><td>{$r['id']}</td><td style='text-align:right'>$".number_format($r['total'],2)."</td></tr>";
        }
        $html .= "<tr><td colspan='2' align='right'><strong>Total vendido:</strong></td><td align='right'><strong>$".number_format($total,2)."</strong></td></tr>";
        $html .= "</tbody></table>";
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream("reporte_empleado_{$empleadoId}.pdf", ["Attachment" => true]);
        exit;
    }

    /**
     * Genera un reporte del estado de inventario en PDF.
     */
    public static function generarInventarioPDF(): void {
        $rows = Database::queryAll(
            "SELECT nombre, tipo_inventario, stock, disponibilidad, precio_unitario
             FROM productos WHERE activo = 1 ORDER BY nombre"
        );
        $html = "<h2 style='text-align:center;'>Inventario actual</h2>";
        $html .= "<table width='100%' border='1' cellpadding='5' cellspacing='0'>";
        $html .= "<thead><tr><th>Producto</th><th>Tipo Inv.</th><th>Stock/Disp.</th><th>Precio Unit.</th></tr></thead><tbody>";
        foreach ($rows as $r) {
            $stockDisp = $r['tipo_inventario'] === 'cantidad' ? (int)$r['stock'] : htmlspecialchars($r['disponibilidad']);
            $html .= "<tr>".
                     "<td>".htmlspecialchars($r['nombre'])."</td>".
                     "<td>{$r['tipo_inventario']}</td>".
                     "<td>{$stockDisp}</td>".
                     "<td style='text-align:right'>$".number_format($r['precio_unitario'],2)."</td>".
                     "</tr>";
        }
        $html .= "</tbody></table>";
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream("reporte_inventario.pdf", ["Attachment" => true]);
        exit;
    }
}

// Enrutamiento para GET y POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Determinar tipo de reporte
    $tipo = $_POST['reporte'] ?? '';
    switch ($tipo) {
        case 'ventas_pdf':
            $desde = $_POST['desde'] ?? date('Y-m-01');
            $hasta = $_POST['hasta'] ?? date('Y-m-d');
            ReportesController::generarVentasPDF($desde, $hasta);
            break;
        case 'empleado_pdf':
            $empleadoId = intval($_POST['empleado_id'] ?? 0);
            ReportesController::generarEmpleadoPDF($empleadoId);
            break;
        case 'inventario_pdf':
            ReportesController::generarInventarioPDF();
            break;
        default:
            // Si no se reconoce, redirigir a reportes
            header('Location: ../Views/admin/reportes.php');
            exit;
    }
} else {
    // GET: mostrar vista de reportes
    $action = $_GET['action'] ?? 'index';
    if ($action === 'index') {
        ReportesController::index();
    } else {
        header('Location: ../Views/admin/reportes.php');
        exit;
    }
}
