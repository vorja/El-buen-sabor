<!-- Views/admin/ventas.php -->
<?php 
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 2) {
    header("Location: ../login.php");
    exit;
}
require_once __DIR__ . '/../partials/header.php';
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
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Ventas - Admin</title>
  <link rel="stylesheet" href="../../assets/css/admin.css" />
</head>
<body>
  <div class="container">
    <h1>Ventas</h1>
    <?php if (!empty($pendientes)): ?>
      <h2>Pedidos pendientes de pago</h2>
      <table class="table table-striped">
        <thead><tr><th>ID Pedido</th><th>Mesero</th><th>Total</th><th>Acciones</th></tr></thead>
        <tbody>
          <?php foreach ($pendientes as $p): ?>
          <tr>
            <td><?= $p['id'] ?></td>
            <td><?= htmlspecialchars($p['mesero']) ?></td>
            <td>$<?= number_format($p['total'], 2) ?></td>
            <td>
              <a href="cobrar.php?pedido=<?= $p['id'] ?>" class="btn btn-primary">Pagar</a>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>

    <h2>Ventas cerradas</h2>
    <table class="table table-sm">
      <thead><tr><th>Fecha/Hora</th><th>ID Pedido</th><th>Mesero</th><th>Total</th></tr></thead>
      <tbody>
        <?php foreach ($ventas as $v): ?>
        <tr>
          <td><?= $v['creado'] ?></td>
          <td><?= $v['id'] ?></td>
          <td><?= htmlspecialchars($v['mesero']) ?></td>
          <td>$<?= number_format($v['total'], 2) ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <?php if (isset($_GET['paid'])): ?>
      <div class="alert alert-success">Pedido #<?= htmlspecialchars($_GET['paid']) ?> cobrado con éxito.</div>
    <?php endif; ?>
  </div>
    <?php require_once __DIR__ . '/../partials/footer.php'; ?>
</body>
</html>
