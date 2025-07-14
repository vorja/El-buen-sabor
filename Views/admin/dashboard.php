<?php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 2) {
    header("Location: ../login.php");
    exit;
}
require_once __DIR__ . '/../../Models/Database.php';

// 1. Datos de ventas de los últimos 7 días
$salesRows = Models\Database::queryAll("
    SELECT DATE(creado) AS dia, 
           SUM(total) AS total 
      FROM pedidos 
     WHERE estado = 'cerrado' 
       AND creado >= CURDATE() - INTERVAL 6 DAY
     GROUP BY DATE(creado)
     ORDER BY DATE(creado)
");
$labelsVentas = array_column($salesRows, 'dia');
$dataVentas   = array_map('floatval', array_column($salesRows, 'total'));

// 2. Productos más vendidos últimos 7 días
$topProdRows = Models\Database::queryAll("
    SELECT p.nombre, SUM(dp.cantidad) AS cantidad
      FROM pedidos pd
      JOIN detalles_pedido dp ON dp.pedido_id = pd.id
      JOIN productos p ON p.id = dp.producto_id
     WHERE pd.estado = 'cerrado'
       AND pd.creado >= CURDATE() - INTERVAL 6 DAY
     GROUP BY dp.producto_id
     ORDER BY cantidad DESC
     LIMIT 5
");
$labelsProd = array_column($topProdRows, 'nombre');
$dataProd   = array_map('intval', array_column($topProdRows, 'cantidad'));

// 3. Productos con stock crítico o sin disponibilidad
$criticos = Models\Database::queryAll("
    SELECT nombre, tipo_inventario, stock, stock_minimo, disponibilidad 
      FROM productos
     WHERE (tipo_inventario = 'cantidad' AND stock <= IFNULL(stock_minimo,0))
        OR (tipo_inventario = 'disponibilidad' AND disponibilidad = 'agotado')
");

// 4. Últimos 5 pedidos (cualquier estado)
$ultPedidos = Models\Database::queryAll("
    SELECT pd.id, pd.creado, pd.estado, pd.total,
           e.nombre AS mesero, m.numero AS mesa
      FROM pedidos pd
      JOIN empleados e ON e.id = pd.mesero_id
      JOIN sesiones_mesa sm ON sm.id = pd.sesion_id
      JOIN qr_tokens qt ON qt.id = sm.qr_token_id
      JOIN mesas m ON m.id = qt.mesa_id
     ORDER BY pd.creado DESC
     LIMIT 5
");
$pageTitle = "Dashboard";
require_once __DIR__ . '/../partials/header.php';
?>

<div class="row mb-4">
  <div class="col-md-6">
    <div class="card p-3">
      <h5>Ventas últimos 7 días</h5>
      <canvas id="chartVentas"></canvas>
    </div>
  </div>
  <div class="col-md-6">
    <div class="card p-3">
      <h5>Top 5 productos vendidos</h5>
      <canvas id="chartProductos"></canvas>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-md-6">
    <div class="card p-3 mb-4">
      <h5>Stock Crítico / Agotado</h5>
      <?php if (count($criticos)): ?>
      <ul class="list-group">
        <?php foreach($criticos as $p): ?>
        <li class="list-group-item d-flex justify-content-between align-items-center">
          <?= htmlspecialchars($p['nombre']) ?>
          <?php if ($p['tipo_inventario']=='cantidad'): ?>
            <span class="badge bg-danger"><?= $p['stock'] ?> ≤ <?= $p['stock_minimo'] ?></span>
          <?php else: ?>
            <span class="badge bg-danger">Agotado</span>
          <?php endif; ?>
        </li>
        <?php endforeach; ?>
      </ul>
      <?php else: ?>
        <p class="text-success">Todos los productos con stock suficiente.</p>
      <?php endif; ?>
    </div>
  </div>
  <div class="col-md-6">
    <div class="card p-3 mb-4">
      <h5>Últimos 5 pedidos</h5>
      <table class="table table-sm">
        <thead><tr>
          <th>#</th><th>Fecha</th><th>Mesa</th><th>Mesero</th><th>Estado</th><th>Total</th>
        </tr></thead>
        <tbody>
        <?php foreach($ultPedidos as $o): ?>
          <tr>
            <td><?= $o['id'] ?></td>
            <td><?= $o['creado'] ?></td>
            <td><?= $o['mesa'] ?></td>
            <td><?= htmlspecialchars($o['mesero']) ?></td>
            <td><?= htmlspecialchars($o['estado']) ?></td>
            <td>$<?= number_format($o['total'],2) ?></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
