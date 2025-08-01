<?php
// repository/Views/mesero/mesa_detalle.php
// Muestra el detalle de una mesa, agregando las cantidades de cada
// producto de todos los pedidos en curso para esa mesa.  El mesero
// puede ver rápidamente qué productos se han solicitado y en qué
// cantidades.

session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 1) {
    header('Location: ../login.php');
    exit;
}

require_once __DIR__ . '/../../Models/Database.php';

$mesaId = isset($_GET['mesa_id']) ? intval($_GET['mesa_id']) : 0;
if ($mesaId <= 0) {
    header('Location: pedidos.php');
    exit;
}

$meseroId = $_SESSION['empleado_id'];

// Obtener número de mesa
$mesaInfo = Models\Database::queryOne('SELECT numero FROM mesas WHERE id = ?', [ $mesaId ]);
if (!$mesaInfo) {
    header('Location: pedidos.php');
    exit;
}
$mesaNumero = $mesaInfo['numero'];

// Obtener productos agregados a los pedidos activos para esta mesa asignados al mesero
$items = Models\Database::queryAll(
    "SELECT pr.id AS producto_id, pr.nombre, pr.imagen,
            SUM(dp.cantidad) AS total_cantidad
       FROM pedidos p
       JOIN detalles_pedido dp ON dp.pedido_id = p.id
       JOIN productos pr ON pr.id = dp.producto_id
       JOIN sesiones_mesa sm ON sm.id = p.sesion_id
       JOIN qr_tokens qt ON qt.id = sm.qr_token_id
      WHERE p.mesero_id = ?
        AND qt.mesa_id   = ?
        AND p.estado IN ('en_progreso','en_cocina')
      GROUP BY pr.id, pr.nombre, pr.imagen
      ORDER BY pr.nombre",
    [ $meseroId, $mesaId ]
);

$pageTitle = 'Mesa ' . htmlspecialchars($mesaNumero);
require_once __DIR__ . '/../partials/header_mesero.php';
?>

<div class="container">
  <h2 class="mb-4">Detalle de Mesa <?= htmlspecialchars($mesaNumero) ?></h2>
  <?php if (count($items) === 0): ?>
    <div class="alert alert-info">No hay productos solicitados en esta mesa.</div>
  <?php else: ?>
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
      <?php foreach ($items as $item): ?>
        <div class="col">
          <div class="card h-100 shadow-sm">
            <?php if (!empty($item['imagen'])): ?>
              <img src="<?= htmlspecialchars($item['imagen']) ?>" class="card-img-top" alt="<?= htmlspecialchars($item['nombre']) ?>" style="object-fit:cover; height:180px;" />
            <?php else: ?>
              <img src="https://images.unsplash.com/photo-1551024506-0bccd828d307?auto=format&fit=crop&w=500&q=60" class="card-img-top" alt="Imagen" style="object-fit:cover; height:180px;" />
            <?php endif; ?>
            <div class="card-body">
              <h5 class="card-title mb-2"><?= htmlspecialchars($item['nombre']) ?></h5>
              <p class="card-text mb-1">Cantidad total: <?= intval($item['total_cantidad']) ?></p>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
  <div class="mt-4">
    <a href="pedidos.php" class="btn btn-secondary">Volver</a>
  </div>
</div>

<?php require_once __DIR__ . '/../partials/footer_mesero.php'; ?>