<!-- Views/admin/ventas.php -->
<?php 
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 2) {
    header("Location: ../login.php");
    exit;
}
// Cargar cabecera de administrador con barra lateral
$pageTitle = "Ventas";
require_once __DIR__ . '/../partials/admin_header.php';
require_once __DIR__ . '/../../Models/Database.php';
$ventas = Models\Database::queryAll(
    "SELECT pd.id, pd.total, pd.creado, e.nombre as mesero 
     FROM pedidos pd
     JOIN empleados e ON pd.mesero_id = e.id
     WHERE pd.estado = 'cerrado'
     ORDER BY pd.creado DESC"
);
$pendientes = Models\Database::queryAll(
    "SELECT pd.id, pd.total, pd.creado, e.nombre as mesero 
     FROM pedidos pd
     JOIN empleados e ON pd.mesero_id = e.id
     WHERE pd.estado = 'entregado'
     ORDER BY pd.creado ASC"
);
?>
  <!--
    Diseño de Ventas
    Se utiliza un contenedor fluid para ajustar al ancho completo y se muestran dos tarjetas: una para los pedidos
    pendientes de pago y otra para el historial de ventas cerradas. Cada tabla es responsiva y se acompaña de
    encabezados descriptivos. Además, se muestra una alerta de confirmación cuando un pedido ha sido cobrado.
  -->
  <div class="container-fluid">
    <h2 class="mb-4">Ventas</h2>
    <?php if (!empty($pendientes)): ?>
      <div class="card shadow-sm mb-4">
        <div class="card-header bg-warning-subtle fw-semibold">
          Pedidos pendientes de pago
        </div>
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
                    <a href="Views/admin/cobrar.php?pedido=<?= $p['id'] ?>" class="btn btn-sm btn-success">
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
      <div class="card-header bg-info-subtle fw-semibold">
        Ventas cerradas
      </div>
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
              <tr>
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

    <?php if (isset($_GET['paid'])): ?>
      <div class="alert alert-success">Pedido #<?= htmlspecialchars($_GET['paid']) ?> cobrado con éxito.</div>
    <?php endif; ?>
  </div>
  <?php require_once __DIR__ . '/../partials/admin_footer.php'; ?>
