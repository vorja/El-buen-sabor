<?php
// Views/admin/dashboard.php
session_start();
// Solo admins (rol = 2)
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 2) {
    header('Location: ../login.php');
    exit;
}

// Carga de la conexión PDO
require_once __DIR__ . '/../../Models/Database.php';
use Models\Database;

// Métricas de hoy
$today = date('Y-m-d');
$qtyRow = Database::queryOne(
    "SELECT COALESCE(SUM(dp.cantidad),0) AS qty
     FROM detalles_pedido dp
     JOIN pedidos p ON dp.pedido_id = p.id
     WHERE p.estado = 'cerrado'
       AND DATE(p.creado) = ?", [$today]
);
$qtyVendidaHoy       = $qtyRow['qty'];
$pedidosVendidosRow = Database::queryOne(
    "SELECT COUNT(*) AS cnt
     FROM pedidos
     WHERE estado = 'cerrado'
       AND DATE(creado) = ?", [$today]
);
$pedidosVendidosHoy = $pedidosVendidosRow['cnt'];
$canceladosRow = Database::queryOne(
    "SELECT COUNT(*) AS cnt
     FROM pedidos
     WHERE estado = 'anulado'
       AND DATE(creado) = ?", [$today]
);
$canceladosHoy      = $canceladosRow['cnt'];
$pendientesRow = Database::queryOne(
    "SELECT COUNT(*) AS cnt
     FROM pedidos
     WHERE estado NOT IN ('cerrado','anulado')
       AND DATE(creado) = ?", [$today]
);
$pendientesHoy      = $pendientesRow['cnt'];

// 1) Ventas últimos 7 días
$salesRows = Database::queryAll(
    "SELECT DATE(creado) AS dia, SUM(total) AS total
     FROM pedidos
     WHERE estado = 'cerrado'
       AND creado >= CURDATE() - INTERVAL 6 DAY
     GROUP BY DATE(creado)
     ORDER BY DATE(creado)"
);
$labelsVentas = array_column($salesRows, 'dia');
$dataVentas   = array_map('floatval', array_column($salesRows, 'total'));

// 2) Top 5 productos vendidos
$topProdRows = Database::queryAll(
    "SELECT p.nombre, SUM(dp.cantidad) AS cantidad
     FROM pedidos pd
     JOIN detalles_pedido dp ON dp.pedido_id = pd.id
     JOIN productos p ON p.id = dp.producto_id
     WHERE pd.estado = 'cerrado'
       AND pd.creado >= CURDATE() - INTERVAL 6 DAY
     GROUP BY dp.producto_id
     ORDER BY cantidad DESC
     LIMIT 5"
);
$labelsProd = array_column($topProdRows, 'nombre');
$dataProd   = array_map('intval', array_column($topProdRows, 'cantidad'));

// 3) Ventas por categoría (Doughnut)
$catRows = Database::queryAll(
    "SELECT c.nombre, SUM(p.total) AS total
     FROM pedidos p
     JOIN sesiones_mesa sm ON sm.id = p.sesion_id
     JOIN qr_tokens qt ON qt.id = sm.qr_token_id
     JOIN detalles_pedido dp ON dp.pedido_id = p.id
     JOIN productos pr ON pr.id = dp.producto_id
     JOIN categorias c ON c.id = pr.categoria_id
     WHERE p.estado = 'cerrado'
     GROUP BY c.id"
);
$labelsCat = array_column($catRows, 'nombre');
$dataCat   = array_map('floatval', array_column($catRows, 'total'));

// 4) Stock de cada producto (Bar chart)
$stockRows = Database::queryAll(
    "SELECT nombre,
            COALESCE(stock, 0) AS stock_val
     FROM productos
     WHERE activo = 1
     ORDER BY nombre"
);
$labelsStock = array_column($stockRows, 'nombre');
$dataStock   = array_map('floatval', array_column($stockRows, 'stock_val'));

// 5) Ventas por mesero (Bar chart)
$meseroRows = Database::queryAll(
    "SELECT e.nombre, COALESCE(SUM(p.total),0) AS total
     FROM empleados e
     LEFT JOIN pedidos p ON p.mesero_id = e.id AND p.estado = 'cerrado'
     WHERE e.rol_id = 1
     GROUP BY e.id
     ORDER BY total DESC"
);
$labelsMeseros = array_column($meseroRows, 'nombre');
$dataMeseros  = array_map('floatval', array_column($meseroRows, 'total'));

$pageTitle = "Dashboard";
require_once __DIR__ . '/../partials/header.php';
?>

<!-- Métricas de Hoy -->
<div class="container mb-4">
  <div class="d-flex justify-content-between align-items-stretch">
    <!-- Card Stat: Cantidad Vendida Hoy -->
    <div class="card text-white bg-primary flex-fill mx-2">
      <div class="card-body text-center">
        <i class="bi bi-currency-dollar fs-1 mb-2"></i>
        <h6 class="card-title">Cantidad Vendida Hoy</h6>
        <p class="fs-3 fw-bold mb-0"><?= $qtyVendidaHoy ?></p>
      </div>
    </div>
    <!-- Card Stat: Pedidos Vendidos Hoy -->
    <div class="card text-white bg-success flex-fill mx-2">
      <div class="card-body text-center">
        <i class="bi bi-bag-check fs-1 mb-2"></i>
        <h6 class="card-title">Pedidos Vendidos Hoy</h6>
        <p class="fs-3 fw-bold mb-0"><?= $pedidosVendidosHoy ?></p>
      </div>
    </div>
    <!-- Card Stat: Cancelados Hoy -->
    <div class="card text-white bg-danger flex-fill mx-2">
      <div class="card-body text-center">
        <i class="bi bi-x-circle fs-1 mb-2"></i>
        <h6 class="card-title">Cancelados Hoy</h6>
        <p class="fs-3 fw-bold mb-0"><?= $canceladosHoy ?></p>
      </div>
    </div>
    <!-- Card Stat: Pendientes Hoy -->
    <div class="card text-white bg-warning flex-fill mx-2">
      <div class="card-body text-center">
        <i class="bi bi-clock-history fs-1 mb-2"></i>
        <h6 class="card-title">Pedidos Pendientes Hoy</h6>
        <p class="fs-3 fw-bold mb-0"><?= $pendientesHoy ?></p>
      </div>
    </div>
  </div>
</div>


<div class="row mb-4">
  <!-- Ventas últimos 7 días -->
  <div class="col-lg-6 mb-4">
    <div class="card p-3">
      <h5>Ventas últimos 7 días</h5>
      <canvas id="chartVentas"></canvas>
    </div>
  </div>
  <!-- Top 5 productos vendidos -->
  <div class="col-lg-6 mb-4">
    <div class="card p-3">
      <h5>Top 5 productos vendidos</h5>
      <canvas id="chartProductos"></canvas>
    </div>
  </div>
</div>

<div class="row mb-4">
  <!-- Ventas por categoría -->
  <div class="col-lg-6 mb-4">
    <div class="card p-3">
      <h5>Ventas por Categoría</h5>
      <canvas id="chartCategoria"></canvas>
    </div>
  </div>

  <!-- Stock y Ventas por Mesero apiladas -->
  <div class="col-lg-6 mb-4">
    <!-- Stock de Productos -->
    <div class="card p-3 mb-4">
      <h5>Stock de Productos</h5>
      <canvas id="chartStock"></canvas>
    </div>
    <!-- Ventas por Mesero debajo -->
    <div class="card p-3">
      <h5>Ventas por Mesero</h5>
      <canvas id="chartVentasMesero"></canvas>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// PHP to JS data
const ventasLabels    = <?= json_encode($labelsVentas) ?>;
const ventasData      = <?= json_encode($dataVentas) ?>;
const prodLabels      = <?= json_encode($labelsProd) ?>;
const prodData        = <?= json_encode($dataProd) ?>;
const catLabels       = <?= json_encode($labelsCat) ?>;
const catData         = <?= json_encode($dataCat) ?>;
const stockLabels     = <?= json_encode($labelsStock) ?>;
const stockData       = <?= json_encode($dataStock) ?>;
const meseroLabels    = <?= json_encode($labelsMeseros) ?>;
const meseroData      = <?= json_encode($dataMeseros) ?>;

// 1) Ventas últimos 7 días (línea)
new Chart(document.getElementById('chartVentas'), {
  type: 'line',
  data: { labels: ventasLabels, datasets: [{ label:'Ventas ($)', data: ventasData, fill:true, tension:0.2 }] },
  options: { scales:{ x:{ title:{display:true,text:'Fecha'}}, y:{ title:{display:true,text:'Total ($)'}, beginAtZero:true } } }
});

// 2) Top 5 productos vendidos (barra horizontal)
new Chart(document.getElementById('chartProductos'), {
  type: 'bar',
  data: { labels: prodLabels, datasets: [{ label:'Unidades vendidas', data: prodData, backgroundColor:'rgba(54,162,235,0.7)', borderRadius:4 }] },
  options: { indexAxis:'y', scales:{ x:{ title:{display:true,text:'Cantidad'}}, y:{ title:{display:true,text:'Producto'} } } }
});

// 3) Ventas por Categoría (doughnut)
new Chart(document.getElementById('chartCategoria'), {
  type:'doughnut',
  data: { labels: catLabels, datasets:[{ data: catData, backgroundColor:['rgba(75,192,192,0.6)','rgba(255,159,64,0.6)','rgba(153,102,255,0.6)','rgba(255,205,86,0.6)'] }] },
  options:{ plugins:{ legend:{position:'bottom'} } }
});

// 4) Stock de Productos (barra vertical)
new Chart(document.getElementById('chartStock'), {
  type:'bar',
  data: { labels: stockLabels, datasets: [{ label:'Stock', data: stockData, backgroundColor:'rgba(255,99,132,0.6)', borderRadius:4 }] },
  options:{ scales:{ x:{ title:{display:true,text:'Producto'}}, y:{ title:{display:true,text:'Stock'}, beginAtZero:true } } }
});

// 5) Ventas por Mesero (barra vertical pequeña)
new Chart(document.getElementById('chartVentasMesero'), {
  type:'bar',
  data: { labels: meseroLabels, datasets:[{ label:'Ventas ($)', data: meseroData, backgroundColor:'rgba(255,159,64,0.7)', borderRadius:4 }] },
  options:{
    indexAxis:'y',
    scales:{ x:{ display:false }, y:{ title:{display:true,text:'Mesero'} } },
    plugins:{ legend:{ display:false } }
  }
});
</script>
