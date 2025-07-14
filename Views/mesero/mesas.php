<?php
// Views/mesero/mesas.php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 1) {
    header("Location: ../login.php");
    exit;
}
require_once __DIR__ . '/../../Models/Database.php';
require_once __DIR__ . '/../../Models/MesaModel.php';

// Obtener siempre las 6 mesas
$mesas = \Models\MesaModel::obtenerMesas();  // Debe devolver 6 filas
$pageTitle = "Mesas";
require_once __DIR__ . '/../partials/header.php';
?>

<h2 class="mb-4">Selecciona tu Mesa</h2>
<div class="row">
  <?php foreach ($mesas as $mesa): ?>
    <div class="col-6 col-md-4 mb-3">
      <button 
        class="btn btn-primary w-100 py-4"
        onclick="location.href='../../Controllers/MeseroController.php?accion=generar_qr&mesa=<?= $mesa['id'] ?>'">
        <i class="fas fa-table fa-2x"></i><br>
        Mesa <?= htmlspecialchars($mesa['numero']) ?>
      </button>
    </div>
  <?php endforeach; ?>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
