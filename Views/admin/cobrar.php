<!-- Views/admin/cobrar.php -->
<?php 
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 2) {
    header("Location: ../login.php");
    exit;
}
require_once __DIR__ . '/../partials/header.php';
require_once __DIR__ . '/../../Models/PedidoModel.php';
require_once __DIR__ . '/../../Models/DetallePedidoModel.php';
$pedidoId = $_GET['pedido'] ?? null;
if (!$pedidoId) {
    header("Location: ventas.php");
    exit;
}
$pedido = Models\PedidoModel::obtenerPedido($pedidoId);
if (!$pedido || $pedido['estado'] != 'entregado') {
    echo "<p>El pedido no está pendiente de pago.</p>";
    exit;
}
$detalles = Models\DetallePedidoModel::obtenerDetalles($pedidoId);
// Obtener datos del cliente principal del pedido
$cliente = Models\Database::queryOne(
    "SELECT c.* FROM clientes c 
     JOIN sesiones_mesa s ON c.id = s.cliente_id 
     JOIN pedidos pd ON pd.sesion_id = s.id 
     WHERE pd.id = ?", [ $pedidoId ]
);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Cobrar Pedido #<?= htmlspecialchars($pedidoId) ?></title>
  <link rel="stylesheet" href="../../assets/css/admin.css" />
</head>
<body>
  <div class="container">
    <h1>Cobrar Pedido #<?= htmlspecialchars($pedidoId) ?></h1>
    <h3>Detalles:</h3>
    <ul>
      <?php foreach ($detalles as $item): ?>
      <li><?= $item['nombre'] ?> (x<?= $item['cantidad'] ?>) - $<?= number_format($item['precio_unit'],2) ?> c/u</li>
      <?php endforeach; ?>
    </ul>
    <p><strong>Total a pagar: $<?= number_format($pedido['total'], 2) ?></strong></p>
    <hr>
    <h3>Datos del Cliente</h3>
    <form action="../../Controllers/AdminController.php" method="POST">
      <?php if ($cliente): ?>
      <p>Nombre registrado: <input type="text" name="nombre_cliente" value="<?= htmlspecialchars($cliente['nombre']) ?>"></p>
      <p>Email: <input type="email" name="email_cliente" value="<?= htmlspecialchars($cliente['email']) ?>"></p>
      <p>Documento: <input type="text" name="documento_cliente" value="<?= htmlspecialchars($cliente['documento'] ?? '') ?>"></p>
      <input type="hidden" name="cliente_id" value="<?= $cliente['id'] ?>">
      <?php else: ?>
      <p><em>No hay cliente registrado para este pedido.</em></p>
      <?php endif; ?>

      <h4>Método de Pago:</h4>
      <select name="metodo_pago" class="form-select">
        <option value="efectivo">Efectivo</option>
        <option value="tarjeta">Tarjeta</option>
        <option value="qr_banco">QR Bancario</option>
        <option value="online">Online</option>
      </select>
      <br>
      <input type="hidden" name="cerrar_pedido_id" value="<?= $pedidoId ?>">
      <button type="submit" class="btn btn-success" onclick="return confirm('Confirmar registro de pago?');">
        Registrar Pago
      </button>
      <a href="ventas.php" class="btn btn-secondary">Cancelar</a>
    </form>
  </div>
    <?php require_once __DIR__ . '/../partials/footer.php'; ?>
</body>
</html>
