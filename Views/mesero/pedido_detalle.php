<?php
// Vista detallada de un pedido para el mesero.
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 1) {
    header("Location: ../login.php");
    exit;
}

require_once __DIR__ . '/../../Models/Database.php';
require_once __DIR__ . '/../../Models/PedidoModel.php';

$pedidoId = isset($_GET['pedido']) ? intval($_GET['pedido']) : 0;
if ($pedidoId <= 0) {
    header("Location: pedido.php");
    exit;
}

$pedido = \Models\PedidoModel::obtenerPedido($pedidoId);
if (!$pedido) {
    header("Location: pedido.php");
    exit;
}

// Obtener número de mesa asociado al pedido
$mesaInfo = \Models\Database::queryOne(
    "SELECT m.numero AS mesa
     FROM pedidos pd
     JOIN sesiones_mesa sm ON sm.id = pd.sesion_id
     JOIN qr_tokens qt ON qt.id = sm.qr_token_id
     JOIN mesas m ON m.id = qt.mesa_id
     WHERE pd.id = ?",
    [ $pedidoId ]
);
$mesaNumero = $mesaInfo ? $mesaInfo['mesa'] : '';

// Obtener detalles de productos del pedido
$items = \Models\Database::queryAll(
    "SELECT dp.cantidad, dp.precio_unit, p.nombre, p.imagen
     FROM detalles_pedido dp
     JOIN productos p ON dp.producto_id = p.id
     WHERE dp.pedido_id = ?",
    [ $pedidoId ]
);

// Calcular total si aún no está actualizado
$total = floatval($pedido['total']);
if ($total <= 0) {
    $suma = 0;
    foreach ($items as $it) {
        $suma += $it['cantidad'] * $it['precio_unit'];
    }
    $total = $suma;
    // Actualizar total en la BD
    \Models\PedidoModel::calcularTotal($pedidoId);
}

$pageTitle = "Pedido #" . $pedidoId;
require_once __DIR__ . '/../partials/header_mesero.php';
?>

<div class="container">
  <h2 class="mb-4">Pedido #<?= $pedidoId ?> – Mesa <?= htmlspecialchars($mesaNumero) ?></h2>
  <div class="mb-3">
    <span class="fw-bold">Estado:</span>
    <?php if ($pedido['estado'] === 'en_progreso'): ?>
      <span class="badge bg-primary-coffee">En Progreso</span>
    <?php elseif ($pedido['estado'] === 'en_cocina'): ?>
      <span class="badge bg-secondary-coffee">En Cocina</span>
    <?php else: ?>
      <span class="badge bg-success">Entregado</span>
    <?php endif; ?>
  </div>
  <div class="row g-3">
    <?php foreach ($items as $item): ?>
      <div class="col-12 col-md-6">
        <div class="card h-100 shadow-sm">
          <div class="row g-0">
            <div class="col-4">
              <?php if (!empty($item['imagen'])): ?>
                <img src="assets/img/productos/<?= htmlspecialchars($item['imagen']) ?>" class="img-fluid rounded-start" alt="<?= htmlspecialchars($item['nombre']) ?>" style="object-fit: cover; height: 100%; width: 100%;" />
              <?php else: ?>
                <img src="assets/img/productos/default.jpg" class="img-fluid rounded-start" alt="imagen" />
              <?php endif; ?>
            </div>
            <div class="col-8">
              <div class="card-body">
                <h5 class="card-title mb-2"><?= htmlspecialchars($item['nombre']) ?></h5>
                <p class="card-text mb-1">Cantidad: <?= intval($item['cantidad']) ?></p>
                <p class="card-text mb-1">Precio unitario: $<?= number_format($item['precio_unit'], 2) ?></p>
                <p class="card-text fw-semibold">Subtotal: $<?= number_format($item['cantidad'] * $item['precio_unit'], 2) ?></p>
              </div>
            </div>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
  <div class="mt-4">
    <h4>Total: $<?= number_format($total, 2) ?></h4>
  </div>
  <div class="mt-3 d-flex gap-3">
    <?php if ($pedido['estado'] === 'en_progreso'): ?>
      <?php
        // Construir URL para confirmar el pedido. Gracias al `<base href="/el-buen-sabor/">`
        // definido en el encabezado, la ruta se interpreta desde la raíz del
        // proyecto. Por ello no se utiliza "../../".
        $confirmarUrl = "Controllers/MeseroController.php?accion=confirmar&pedido=$pedidoId";
      ?>
      <a href="<?= $confirmarUrl ?>" class="btn btn-primary">Enviar a Cocina</a>
    <?php elseif ($pedido['estado'] === 'en_cocina'): ?>
      <?php
        $entregarUrl = "Controllers/MeseroController.php?accion=entregar&pedido=$pedidoId";
      ?>
      <a href="<?= $entregarUrl ?>" class="btn btn-success">Marcar como Entregado</a>
    <?php endif; ?>
    <a href="pedido.php" class="btn btn-secondary">Volver a Pedidos</a>
  </div>
</div>

<?php require_once __DIR__ . '/../partials/footer_mesero.php'; ?>