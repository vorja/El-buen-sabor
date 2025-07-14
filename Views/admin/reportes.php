<?php
// Views/admin/reportes.php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 2) {
    header('Location: ../login.php');
    exit;
}

// Incluir nuestro wrapper PDO
require_once __DIR__ . '/../../Models/Database.php';
use Models\Database;

// Obtener todas las ventas ya cerradas
$ventas = Database::queryAll("
    SELECT 
      m.numero AS mesa,
      c.nombre AS cliente,
      p.total,
      p.estado,
      p.creado
    FROM pedidos p
    JOIN sesiones_mesa sm ON sm.id = p.sesion_id
    JOIN qr_tokens qt     ON qt.id = sm.qr_token_id
    JOIN mesas m          ON m.id = qt.mesa_id
    JOIN clientes c       ON c.id = sm.cliente_id
    WHERE p.estado = 'cerrado'
    ORDER BY p.creado DESC
");

require_once __DIR__ . '/../partials/header.php';
?>

<div class="container mt-4">
  <h2 class="mb-4">📊 Reporte de Ventas Cerradas</h2>
  <?php if (empty($ventas)): ?>
    <div class="alert alert-info text-center">No hay ventas cerradas registradas.</div>
  <?php else: ?>
    <div class="table-responsive">
      <table class="table table-striped table-hover">
        <thead class="table-dark">
          <tr>
            <th>Mesa</th><th>Cliente</th><th>Total (USD)</th><th>Estado</th><th>Fecha</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($ventas as $v): ?>
          <tr>
            <td><?= htmlspecialchars($v['mesa']) ?></td>
            <td><?= htmlspecialchars($v['cliente']) ?></td>
            <td>$<?= number_format($v['total'], 2) ?></td>
            <td><span class="badge bg-success"><?= htmlspecialchars($v['estado']) ?></span></td>
            <td><?= date('d/m/Y H:i', strtotime($v['creado'])) ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>
