<?php
// repository/Views/admin/ventas.php
// Vista para el administrador que muestra los pedidos pendientes de cobro
// y las ventas cerradas.  En las ventas cerradas se habilita un
// modal con los detalles completos (cliente, mesero, productos).  La
// página requiere que el usuario sea administrador (rol 2).

session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 2) {
    header('Location: ../login.php');
    exit;
}

require_once __DIR__ . '/../../Models/Database.php';

use Models\Database;

// Obtener pedidos pendientes de pago (estado 'entregado')
$pendientes = Database::queryAll(
    "SELECT pd.id, pd.total, pd.creado, e.nombre AS mesero
       FROM pedidos pd
       JOIN empleados e ON pd.mesero_id = e.id
      WHERE pd.estado = 'entregado'
      ORDER BY pd.creado ASC"
);

// Obtener ventas cerradas (estado 'cerrado')
$ventas = Database::queryAll(
    "SELECT pd.id, pd.total, pd.creado, e.nombre AS mesero
       FROM pedidos pd
       JOIN empleados e ON pd.mesero_id = e.id
      WHERE pd.estado = 'cerrado'
      ORDER BY pd.creado DESC"
);

// Título de página para la cabecera
$pageTitle = 'Ventas';
require_once __DIR__ . '/../partials/admin_header.php';
?>

<h2 class="mb-4">Ventas</h2>

<?php if (!empty($pendientes)): ?>
  <div class="card shadow-sm mb-4">
    <div class="card-header bg-warning-subtle fw-semibold">Pedidos pendientes de pago</div>
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
          <thead class="table-light">
            <tr>
              <th>ID Pedido</th>
              <th>Mesero</th>
              <th>Total</th>
              <th class="text-center" style="width:120px">Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($pendientes as $p): ?>
            <tr>
              <td>#<?= $p['id'] ?></td>
              <td><?= htmlspecialchars($p['mesero']) ?></td>
              <td>$<?= number_format($p['total'], 2) ?></td>
              <td class="text-center">
                <a href="../../Controllers/MeseroController.php?accion=entregar&pedido=<?= $p['id'] ?>" class="btn btn-sm btn-success">
                  <i class="fa fa-credit-card me-1"></i> Cobrar
                </a>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
<?php endif; ?>

<div class="card shadow-sm mb-4">
  <div class="card-header bg-info-subtle fw-semibold">Ventas cerradas</div>
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover mb-0 align-middle">
        <thead class="table-light">
          <tr>
            <th>Fecha/Hora</th>
            <th>ID Pedido</th>
            <th>Mesero</th>
            <th>Total</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($ventas as $v): ?>
          <tr role="button" data-bs-toggle="modal" data-bs-target="#ventaModal-<?= $v['id'] ?>">
            <td><?= $v['creado'] ?></td>
            <td>#<?= $v['id'] ?></td>
            <td><?= htmlspecialchars($v['mesero']) ?></td>
            <td>$<?= number_format($v['total'], 2) ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php
// Generar modales para cada venta cerrada
foreach ($ventas as $v):
    // Obtener detalles del pedido: cliente, productos y cantidades
    $detalles = Database::queryAll(
        "SELECT c.nombre AS cliente_nombre, c.email AS cliente_email,
                pr.nombre AS producto_nombre, dp.cantidad, dp.precio_unit
           FROM pedidos pd
           JOIN sesiones_mesa sm ON sm.id = pd.sesion_id
           JOIN clientes c ON c.id = sm.cliente_id
           JOIN detalles_pedido dp ON dp.pedido_id = pd.id
           JOIN productos pr ON pr.id = dp.producto_id
          WHERE pd.id = ?",
        [ $v['id'] ]
    );
    // Si no hay detalles, continuar
    if (!$detalles) continue;
?>
<!-- Modal de detalle de venta -->
<div class="modal fade" id="ventaModal-<?= $v['id'] ?>" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Detalle de Venta #<?= $v['id'] ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <p><strong>Cliente:</strong> <?= htmlspecialchars($detalles[0]['cliente_nombre']) ?> (<?= htmlspecialchars($detalles[0]['cliente_email']) ?>)</p>
        <p><strong>Fecha:</strong> <?= $v['creado'] ?></p>
        <p><strong>Mesero:</strong> <?= htmlspecialchars($v['mesero']) ?></p>
        <p><strong>Total:</strong> $<?= number_format($v['total'], 2) ?></p>
        <hr>
        <h6>Productos</h6>
        <div class="table-responsive">
          <table class="table table-sm">
            <thead>
              <tr><th>Producto</th><th>Cantidad</th><th>Precio Unitario</th><th>Subtotal</th></tr>
            </thead>
            <tbody>
              <?php foreach ($detalles as $d): ?>
              <?php $subtotal = $d['cantidad'] * $d['precio_unit']; ?>
              <tr>
                <td><?= htmlspecialchars($d['producto_nombre']) ?></td>
                <td><?= intval($d['cantidad']) ?></td>
                <td>$<?= number_format($d['precio_unit'], 2) ?></td>
                <td>$<?= number_format($subtotal, 2) ?></td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>
<?php endforeach; ?>

<?php require_once __DIR__ . '/../partials/admin_footer.php'; ?>