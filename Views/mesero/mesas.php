<?php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 1) {
    header("Location: ../login.php");
    exit;
}
require_once __DIR__ . '/../../Models/MesaModel.php';
use Models\MesaModel;

$mesas = MesaModel::obtenerMesas();
require_once __DIR__ . '/../partials/header.php';
?>
<div class="container py-5">
  <h1 class="text-center mb-4" style="color:#a38672;">Selecciona tu Mesa</h1>
  <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">
    <?php foreach ($mesas as $m): ?>
      <div class="col">
        <div class="mesa-card h-100 d-flex flex-column justify-content-between">
          <div>
            <i class="fas fa-table fa-3x"></i>
            <h4 class="mt-2" style="color:#5b4534;">Mesa <?= htmlspecialchars($m['numero']) ?></h4>
          </div>
          <?php if ($m['estado'] === 'libre'): ?>
            <button
              class="btn-coffee"
              onclick="location.href='../../Controllers/MeseroController.php?accion=generar_qr&mesa=<?= $m['id'] ?>'">
              Seleccionar
            </button>
          <?php else: ?>
            <button class="btn-coffee" disabled>
              Ocupada
            </button>
          <?php endif; ?>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>
<?php require_once __DIR__ . '/../partials/footer.php'; ?>
