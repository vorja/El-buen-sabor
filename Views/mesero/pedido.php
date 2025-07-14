<?php
// Views/mesero/pedidos.php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 1) {
    header("Location: ../login.php");
    exit;
}
require_once __DIR__ . '/../../Models/Database.php';
require_once __DIR__ . '/../../Models/PedidoModel.php';

// Obtener pedidos pendientes o en cocina asignados al mesero
$meseroId = $_SESSION['empleado_id'];
$pedidos = \Models\Database::queryAll(
    "SELECT pd.id, pd.creado, pd.estado, pd.total, m.numero AS mesa
       FROM pedidos pd
       JOIN sesiones_mesa sm ON sm.id = pd.sesion_id
       JOIN qr_tokens qt ON qt.id = sm.qr_token_id
       JOIN mesas m ON m.id = qt.mesa_id
      WHERE pd.mesero_id = ?
        AND pd.estado IN ('en_progreso','en_cocina')
      ORDER BY pd.creado ASC",
    [ $meseroId ]
);

$pageTitle = "Pedidos Pendientes";
require_once __DIR__ . '/../partials/header.php';
?>

<div class="row">
  <div class="col-12">
    <h2 class="mb-4">Mis Pedidos</h2>
    <?php if (count($pedidos) === 0): ?>
      <div class="card text-center p-5">
        <i class="fas fa-inbox fa-3x mb-3 text-muted"></i>
        <h5 class="card-title">No hay pedidos pendientes</h5>
        <p class="card-text text-muted">Aún no hay nuevos pedidos o todos están en cocina.</p>
      </div>
    <?php else: ?>
      <div class="list-group">
        <?php foreach ($pedidos as $p): ?>
          <a href="pedido.php?pedido=<?= $p['id'] ?>" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
            <div>
              <h6>Pedido #<?= $p['id'] ?> - Mesa <?= htmlspecialchars($p['mesa']) ?></h6>
              <small class="text-muted"><?= $p['creado'] ?></small>
            </div>
            <div class="text-end">
              <?php if ($p['estado']==='en_progreso'): ?>
                <span class="badge bg-primary-coffee">En Progreso</span>
              <?php else: ?>
                <span class="badge bg-secondary-coffee">En Cocina</span>
              <?php endif; ?>
              <p class="mb-0">$<?= number_format($p['total'],2) ?></p>
            </div>
          </a>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
