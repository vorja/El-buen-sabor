<?php
// repository/Views/mesero/pedidos.php
// Vista para meseros que muestra las mesas con pedidos en curso.
// Se agrupan los pedidos por mesa para que cada tarjeta represente una
// mesa ocupada.  Al hacer clic en la tarjeta se redirige a la vista
// detallada de la mesa donde se muestran todos los productos
// solicitados.

session_start();
// Asegurarse de que el usuario es mesero (rol_id = 1)
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 1) {
    header('Location: ../login.php');
    exit;
}

require_once __DIR__ . '/../../Models/Database.php';

$meseroId = $_SESSION['empleado_id'];

// Obtener las mesas con pedidos activos (en_progreso o en_cocina)
// junto con el total acumulado de los pedidos y los ids de los
// pedidos asociados.
$mesas = Models\Database::queryAll(
    "SELECT m.id AS mesa_id, m.numero AS mesa_numero,
            GROUP_CONCAT(pd.id) AS pedidos_ids,
            SUM(pd.total) AS total_acumulado
       FROM pedidos pd
       JOIN sesiones_mesa sm ON sm.id = pd.sesion_id
       JOIN qr_tokens qt ON qt.id = sm.qr_token_id
       JOIN mesas m ON m.id = qt.mesa_id
      WHERE pd.mesero_id = ?
        AND pd.estado IN ('en_progreso','en_cocina')
      GROUP BY m.id, m.numero
      ORDER BY m.numero",
    [ $meseroId ]
);

// Título para el encabezado
$pageTitle = 'Mis Mesas';
// Incluir cabecera común del mesero
require_once __DIR__ . '/../partials/header_mesero.php';
?>

<div class="row">
  <div class="col-12">
    <h2 class="mb-4">Mesas con pedidos</h2>
    <?php if (count($mesas) === 0): ?>
      <div class="card text-center p-5">
        <i class="fas fa-inbox fa-3x mb-3 text-muted"></i>
        <h5 class="card-title">No hay mesas con pedidos</h5>
        <p class="card-text text-muted">Aún no hay pedidos pendientes o todos han sido enviados.</p>
      </div>
    <?php else: ?>
      <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        <?php foreach ($mesas as $mesa): ?>
          <div class="col">
            <div class="card h-100 shadow-sm position-relative">
              <div class="card-body">
                <h5 class="card-title">Mesa <?= htmlspecialchars($mesa['mesa_numero']) ?></h5>
                <p class="card-text mb-1">
                  <small class="text-muted">Pedidos: <?= htmlspecialchars($mesa['pedidos_ids']) ?></small>
                </p>
                <p class="card-text fw-bold">Total acumulado: $<?= number_format($mesa['total_acumulado'], 2) ?></p>
              </div>
              <a href="mesa_detalle.php?mesa_id=<?= $mesa['mesa_id'] ?>" class="stretched-link"></a>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</div>

<?php require_once __DIR__ . '/../partials/footer_mesero.php'; ?>