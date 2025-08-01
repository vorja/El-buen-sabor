<?php
// repository/Views/mesero/mesas.php
// Permite al mesero seleccionar una mesa libre para generar un código
// QR. Muestra las mesas disponibles y su estado. Cuando se genera
// un token se muestra un modal con el código QR para que el cliente
// lo escanee.

session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 1) {
    header('Location: ../login.php');
    exit;
}

require_once __DIR__ . '/../../Models/MesaModel.php';
use Models\MesaModel;

$mesas = MesaModel::obtenerMesas();

// Recuperar parámetros para mostrar el modal si se acaba de generar un token
$tokenGenerado      = $_GET['token'] ?? null;
$mesaSeleccionadaId = $_GET['mesa'] ?? null;
$mesaSeleccionadaNumero = null;
if ($tokenGenerado && $mesaSeleccionadaId) {
    foreach ($mesas as $mx) {
        if ($mx['id'] == $mesaSeleccionadaId) {
            $mesaSeleccionadaNumero = $mx['numero'];
            break;
        }
    }
}

// Título de la página para el header
$pageTitle = 'Mesas';
require_once __DIR__ . '/../partials/header_mesero.php';
?>

<h2 class="mb-4">Selecciona Mesa</h2>
<div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">
  <?php foreach ($mesas as $m): ?>
    <div class="col">
      <div class="card h-100 shadow-sm d-flex flex-column justify-content-between">
        <div class="p-3 text-center">
          <i class="fas fa-table fa-3x mb-2"></i>
          <h5 class="card-title">Mesa <?= htmlspecialchars($m['numero']) ?></h5>
        </div>
        <div class="card-body d-flex justify-content-center">
          <?php if ($m['estado'] === 'libre'): ?>
            <a href="../../Controllers/MeseroController.php?accion=generar_qr&mesa=<?= $m['id'] ?>" class="btn btn-primary">Seleccionar</a>
          <?php else: ?>
            <button class="btn btn-secondary" disabled>Ocupada</button>
          <?php endif; ?>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
</div>

<?php if ($tokenGenerado && $mesaSeleccionadaId && $mesaSeleccionadaNumero): ?>
  <!-- Modal para mostrar el código QR al mesero -->
  <div class="modal fade" id="qrModal" tabindex="-1" aria-labelledby="qrModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="qrModalLabel">Código QR para Mesa <?= htmlspecialchars($mesaSeleccionadaNumero) ?></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body text-center">
          <p>Solicite al cliente que escanee este código QR para ingresar sus datos y comenzar el pedido.</p>
          <?php
            // Construir la URL que se codificará en el QR. Incluir el ID del mesero
            $meseroId = $_SESSION['empleado_id'] ?? '';
            $qrLink   = '../cliente/loginCliente.php?token=' . urlencode($tokenGenerado) . '&mesero=' . urlencode($meseroId);
            $qrImg    = 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' . urlencode($qrLink);
          ?>
          <img src="<?= $qrImg ?>" alt="Código QR" class="img-fluid mb-3" />
          <p class="small">Enlace: <a href="<?= $qrLink ?>" target="_blank"><?= htmlspecialchars($qrLink) ?></a></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>
  <script>
    // Mostrar el modal automáticamente al cargar
    document.addEventListener('DOMContentLoaded', function () {
      var qrModal = new bootstrap.Modal(document.getElementById('qrModal'));
      qrModal.show();
    });
  </script>
<?php endif; ?>

<?php require_once __DIR__ . '/../partials/footer_mesero.php'; ?>