<?php
require_once __DIR__ . '/../Models/PedidoModel.php';
require_once __DIR__ . '/../Models/SesionModel.php';
require_once __DIR__ . '/../Models/MesaModel.php';
require_once __DIR__ . '/../Models/DetallePedidoModel.php';
require_once __DIR__ . '/../Models/ProductoModel.php';
session_start();

class AdminController {
    // Finalizar el pago de un pedido (cerrar pedido, registrar pago, actualizar datos cliente)
    public static function cerrarPedido($pedidoId, $metodoPago, $clienteDatos = []) {
        // Actualizar estado del pedido a 'cerrado'
        Models\PedidoModel::actualizarEstado($pedidoId, 'cerrado');
        // Registrar en la tabla pagos el método y monto
        $total = Models\PedidoModel::obtenerPedido($pedidoId)['total'] ?? 0;
        Models\Database::execute(
            "INSERT INTO pagos (pedido_id, metodo, monto, creado) VALUES (?, ?, ?, NOW())", 
            [ $pedidoId, $metodoPago, $total ]
        );
        // Si se proporcionaron datos adicionales del cliente (nombre, email, documento), actualizar
        if (!empty($clienteDatos)) {
            Models\Database::execute(
                "UPDATE clientes SET nombre = ?, email = ?, documento = ? WHERE id = ?", 
                [ 
                  $clienteDatos['nombre'], 
                  $clienteDatos['email'], 
                  $clienteDatos['documento'], 
                  $clienteDatos['cliente_id'] 
                ]
            );
        }
        // Obtener sesion_id asociado al pedido para cerrar la sesión y liberar mesa
        $pedido = Models\PedidoModel::obtenerPedido($pedidoId);
        if ($pedido) {
            $sesionId = $pedido['sesion_id'];
            Models\SesionModel::cerrarSesion($sesionId);
            // Liberar la mesa
            // Obtener mesa_id a través de la sesión -> qr_tokens
            $sesion = Models\SesionModel::obtenerSesion($sesionId);
            if ($sesion) {
                $qrTokenId = $sesion['qr_token_id'];
                $tokenData = Models\Database::queryOne("SELECT mesa_id FROM qr_tokens WHERE id = ?", [ $qrTokenId ]);
                if ($tokenData) {
                    Models\MesaModel::actualizarEstadoMesa($tokenData['mesa_id'], 'libre');
                }
            }
        }
        // (Generar factura PDF con Dompdf)
        self::generarFacturaPDF($pedidoId);
        // Redirigir al panel de ventas o confirmación
        header("Location: ../Views/admin/ventas.php?paid=$pedidoId");
        exit;
    }

    // Generar factura PDF usando Dompdf
    public static function generarFacturaPDF($pedidoId) {
        // Obtener datos del pedido y sus detalles
        $pedido = Models\PedidoModel::obtenerPedido($pedidoId);
        $detalles = Models\DetallePedidoModel::obtenerDetalles($pedidoId);
        $cliente = Models\Database::queryOne(
            "SELECT c.* FROM clientes c 
             JOIN sesiones_mesa s ON s.cliente_id = c.id 
             JOIN pedidos pd ON pd.sesion_id = s.id 
             WHERE pd.id = ?", [ $pedidoId ]
        );
        // Construir HTML de la factura
        $html = "<h1>Factura #{$pedidoId}</h1>";
        $html .= "<p>Fecha: {$pedido['creado']}</p>";
        $html .= "<p>Cliente: {$cliente['nombre']} - {$cliente['email']} - Doc: {$cliente['documento']}</p>";
        $html .= "<h3>Detalles del Pedido:</h3><table border='1' cellpadding='5' cellspacing='0'>";
        $html .= "<tr><th>Producto</th><th>Cant.</th><th>Precio Unit.</th><th>Subtotal</th></tr>";
        foreach ($detalles as $item) {
            $sub = $item['cantidad'] * $item['precio_unit'];
            $html .= "<tr>
                        <td>{$item['nombre']}</td>
                        <td align='center'>{$item['cantidad']}</td>
                        <td align='right'>$" . number_format($item['precio_unit'], 2) . "</td>
                        <td align='right'>$" . number_format($sub, 2) . "</td>
                      </tr>";
        }
        $html .= "<tr><td colspan='3' align='right'><strong>Total:</strong></td>
                      <td align='right'><strong>$" . number_format($pedido['total'], 2) . "</strong></td></tr>";
        $html .= "</table>";

        // Inicializar Dompdf y generar PDF
        // Cargar el autoload de Composer ubicado en lib/vendor/autoload.php
        require_once __DIR__ . '/../lib/vendor/autoload.php';
        $dompdf = new Dompdf\Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        // Guardar PDF en archivo o mostrar directamente. Por ejemplo:
        $output = $dompdf->output();
        file_put_contents(__DIR__."/../facturas/factura_$pedidoId.pdf", $output);
        // Alternativamente, para forzar descarga en el navegador:
        // $dompdf->stream("factura_$pedidoId.pdf");
    }
}

// Si se envía el formulario de cierre de pedido (pago) desde la vista admin
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cerrar_pedido_id'])) {
    $pedidoId = intval($_POST['cerrar_pedido_id']);
    $metodo = $_POST['metodo_pago'] ?? 'efectivo';
    // Recoger datos de cliente del formulario de pago (si se proporcionan)
    $clienteDatos = [
        'cliente_id' => $_POST['cliente_id'] ?? null,
        'nombre' => $_POST['nombre_cliente'] ?? '',
        'email'  => $_POST['email_cliente'] ?? '',
        'documento' => $_POST['documento_cliente'] ?? ''
    ];
    AdminController::cerrarPedido($pedidoId, $metodo, $clienteDatos);
}

if (isset($_POST['accion']) && $_POST['accion']=='agregar_producto') {
    Models\ProductoModel::crearProducto($_POST['nombre'], $_POST['categoria_id'], $_POST['descripcion'], 
                                floatval($_POST['precio']), $_POST['tipo_inventario'], 
                                isset($_POST['stock'])? floatval($_POST['stock']): null, 
                                isset($_POST['stock_min'])? floatval($_POST['stock_min']): null );
    header("Location: ../Views/admin/inventario.php");
    exit;
}