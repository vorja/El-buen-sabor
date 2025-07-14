<!-- Views/mesero/pedido.php -->
<?php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 1) {
    header("Location: ../login.php");
    exit;
}
require_once __DIR__ . '/../../Models/PedidoModel.php';
require_once __DIR__ . '/../../Models/DetallePedidoModel.php';

$mesaId = $_GET['mesa'] ?? null;
$pedidoId = $_GET['pedido'] ?? null;
if ($mesaId) {
    // obtener el pedido activo de esa mesa
    $pedidoData = Models\PedidoModel::obtenerPedidoActivoPorSesion(
                   Models\Database::queryOne("SELECT id FROM sesiones_mesa 
                                               JOIN qr_tokens ON sesiones_mesa.qr_token_id = qr_tokens.id 
                                               WHERE qr_tokens.mesa_id = ? AND cerrada = 0", 
                                               [ $mesaId ])['id'] ?? 0
                 );
    $pedidoId = $pedidoData['id'] ?? null;
} elseif ($pedidoId) {
    $pedidoData = Models\PedidoModel::obtenerPedido($pedidoId);
}
if (!$pedidoId || !$pedidoData) {
    echo "<p>No se encontró el pedido para la mesa.</p>";
    exit;
}
$detalles = Models\DetallePedidoModel::obtenerDetalles($pedidoId);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Pedido Mesa <?= htmlspecialchars($mesaId ?? '') ?></title>
  <link rel="stylesheet" href="../../assets/css/mesero.css" />
</head>
<body>
  <div class="container">
    <h1>Pedido - Mesa <?= htmlspecialchars($mesaId ?? '') ?></h1>
    <p><strong>Estado actual:</strong> <?= htmlspecialchars($pedidoData['estado']) ?></p>
    <h3>Detalles del pedido:</h3>
    <ul>
      <?php foreach ($detalles as $item): ?>
      <li><?= $item['nombre'] ?> (x<?= $item['cantidad'] ?>) - $<?= number_format($item['precio_unit'],2) ?> c/u</li>
      <?php endforeach; ?>
    </ul>
    <p><strong>Total: </strong>$<?= number_format($pedidoData['total'], 2) ?></p>

    <!-- Acciones según estado -->
    <?php if ($pedidoData['estado'] == 'en_progreso'): ?>
      <!-- Botón para confirmar pedido (enviar a cocina) -->
      <a href="../../Controllers/MeseroController.php?accion=confirmar&pedido=<?= $pedidoId ?>" 
         class="btn btn-warning"
         onclick="return confirm('¿Confirmar pedido y enviar a cocina?');">
         Enviar a Cocina
      </a>
    <?php endif; ?>
    <?php if ($pedidoData['estado'] == 'en_cocina'): ?>
      <!-- Botón para marcar como entregado -->
      <a href="../../Controllers/MeseroController.php?accion=entregar&pedido=<?= $pedidoId ?>" 
         class="btn btn-success"
         onclick="return confirm('¿Marcar pedido como entregado?');">
         Marcar Entregado
      </a>
    <?php endif; ?>

    <?php if ($pedidoData['estado'] == 'entregado'): ?>
      <p class="alert alert-info">Pedido entregado. Esperando pago.</p>
    <?php endif; ?>
    <?php if ($pedidoData['estado'] == 'cerrado'): ?>
      <p class="alert alert-success">Pedido cerrado y pagado.</p>
    <?php endif; ?>

    <p><a href="mesas.php">← Volver a Mis Mesas</a></p>
  </div>
</body>
</html>
