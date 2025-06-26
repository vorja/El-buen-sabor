<?php
// Views/ventas.php
require_once __DIR__ . '/../models/MySQL.php';
session_start();

// Conexión
$mysql = new MySQL();
$conn  = $mysql->getConexion();

// Consulta para recuperar el historial de pedidos
$sql = "
     SELECT
      p.id AS venta_id,
      DATE_FORMAT(p.creado, '%d/%m/%Y %H:%i') AS fecha_hora,
      c.nombre AS cliente,
      GROUP_CONCAT(CONCAT(dp.cantidad,'× ', pr.nombre) SEPARATOR ', ') AS productos,
      p.total AS total_venta,
      p.estado,
      GROUP_CONCAT(DISTINCT pa.metodo SEPARATOR ', ') AS metodos_pago
    FROM pedidos p
    JOIN sesiones_mesa s ON s.id = p.sesion_id
    JOIN clientes c      ON c.id = s.cliente_id
    JOIN detalles_pedido dp ON dp.pedido_id = p.id
    JOIN productos pr    ON pr.id = dp.producto_id
    LEFT JOIN pagos pa   ON pa.pedido_id = p.id
    WHERE p.estado IN ('en_cocina','entregado','cerrado','anulado')
    GROUP BY p.id
    ORDER BY p.creado DESC
";
$result = $mysql->efectuarConsulta($sql);
$sqlEvol = "
  SELECT 
    MONTH(creado)   AS mes_num,
    DATE_FORMAT(creado, '%b') AS mes_label,
    SUM(total)      AS total_mes
  FROM pedidos
  WHERE estado = 'cerrado'
  GROUP BY YEAR(creado), MONTH(creado)
  ORDER BY YEAR(creado), MONTH(creado)
";
$resEvol = $mysql->efectuarConsulta($sqlEvol);

$meses      = [];
$ventasMes  = [];
while ($row = mysqli_fetch_assoc($resEvol)) {
    $meses[]     = $row['mes_label'];     // 'Ene','Feb',...
    $ventasMes[] = (float)$row['total_mes'];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ventas - Cafetería Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/ventas.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>
<body>
    <div class="container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="logo">
                <h1><i class="fas fa-coffee"></i> Café Central</h1>
                <p>Dashboard Administrativo</p>
            </div>
            <nav>
                <ul class="nav-menu">
                    <li class="nav-item">
                        <a href="../Views/dashboard.php" class="nav-link">
                            <i class="fas fa-tachometer-alt"></i>
                            inicio
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="../Views/ventas.php" class="nav-link active">
                            <i class="fas fa-chart-line"></i>
                            Ventas
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="../Views/inventario.php" class="nav-link">
                            <i class="fas fa-boxes"></i>
                            Inventario
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="../Views/pedidos.php" class="nav-link">
                            <i class="fas fa-shopping-cart"></i>
                            Pedidos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="../Views/clientes.php" class="nav-link">
                            <i class="fas fa-users"></i>
                            Clientes
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="../Views/reportes.php" class="nav-link">
                            <i class="fas fa-file-alt"></i>
                            Reportes
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Header -->
            <header class="header">
                <h1 class="header-title">
                    <i class="fas fa-chart-line"></i>
                    Gestión de Ventas
                </h1>
                <div class="header-info">
                    <div class="date-time" id="dateTime"></div>
                    <div class="user-info">
                        <i class="fas fa-user-circle"></i>
                        <span>Admin</span>
                    </div>
                </div>
            </header>

            <!-- Sales Controls -->
            <section class="sales-controls">
                <div class="control-card">
                    <h3 class="control-title">
                        <i class="fas fa-calendar-alt"></i>
                        Período de Ventas
                    </h3>
                    <div style="display: flex; gap: 0.5rem;">
                        <input type="date" class="date-selector" id="fechaInicio" value="2024-01-01" style="width: calc(50% - 0.25rem);">
                        <input type="date" class="date-selector" id="fechaFin" value="2024-12-31" style="width: calc(50% - 0.25rem);">
                    </div>
                </div>
                
                <div class="control-card">
                    <h3 class="control-title">
                        <i class="fas fa-filter"></i>
                        Filtrar por Estado
                    </h3>
                    <select class="filter-select" id="filtroEstado">
                        <option value="todos">Todos los estados</option>
                        <option value="completado">Completado</option>
                        <option value="pendiente">Pendiente</option>
                        <option value="cancelado">Cancelado</option>
                    </select>
                </div>
                
                <div class="control-card">
                    <h3 class="control-title">
                        <i class="fas fa-search"></i>
                        Buscar Venta
                    </h3>
                    <div class="search-box">
                        <input type="text" class="search-input" placeholder="ID, cliente o producto..." id="buscarVenta">
                        <i class="fas fa-search search-icon"></i>
                    </div>
                </div>
                
                <div class="control-card">
                    <h3 class="control-title">
                        <i class="fas fa-tags"></i>
                        Filtrar por Producto
                    </h3>
                    <select class="filter-select" id="filtroProducto">
                        <option value="todos">Todos los productos</option>
                        <option value="cappuccino">Cappuccino</option>
                        <option value="americano">Americano</option>
                        <option value="latte">Latte</option>
                        <option value="croissant">Croissant</option>
                        <option value="sandwich">Sandwich</option>
                    </select>
                </div>
            </section>

            <!-- Sales Summary Cards -->
            <section class="sales-summary">
                <div class="summary-card card-green">
                    <div class="summary-header">
                        <div class="summary-title">Ventas Totales</div>
                        <div class="summary-icon">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                    </div>
                    <div class="summary-value" id="ventasTotales">$15,284.50</div>
                    <div class="summary-change">
                        <i class="fas fa-arrow-up"></i>
                        +18.2% vs mes anterior
                    </div>
                </div>
                
                <div class="summary-card card-orange">
                    <div class="summary-header">
                        <div class="summary-title">Pedidos Completados</div>
                        <div class="summary-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                    <div class="summary-value" id="pedidosCompletados">1,247</div>
                    <div class="summary-change">
                        <i class="fas fa-arrow-up"></i>
                        +12.8% vs mes anterior
                    </div>
                </div>
                
                <div class="summary-card card-purple">
                    <div class="summary-header">
                        <div class="summary-title">Ticket Promedio</div>
                        <div class="summary-icon">
                            <i class="fas fa-receipt"></i>
                        </div>
                    </div>
                    <div class="summary-value" id="ticketPromedio">$12.25</div>
                    <div class="summary-change">
                        <i class="fas fa-arrow-up"></i>
                        +4.7% vs mes anterior
                    </div>
                </div>
            </section>

            <!-- Charts Section -->
            <section class="charts-grid">
                <div class="chart-card">
                    <h3 class="chart-title">
                        <i class="fas fa-chart-area"></i>
                        Evolución de Ventas
                    </h3>
                    <div class="chart-container">
                        <canvas id="ventasEvolucionChart"></canvas>
                    </div>
                </div>

                <div class="chart-card">
                    <h3 class="chart-title">
                        <i class="fas fa-chart-pie"></i>
                        Ventas por Categoría
                    </h3>
                    <div class="chart-container">
                        <canvas id="ventasCategoriaChart"></canvas>
                    </div>
                </div>
            </section>

            <!-- Sales Table -->
            <section class="sales-table-section">
                <div class="table-header">
                    <h3 class="table-title">
                        <i class="fas fa-list"></i>
                        Historial de Ventas
                    </h3>
                    <button class="export-btn" onclick="exportarVentas()">
                        <i class="fas fa-download"></i>
                        Exportar
                    </button>
                </div>
                
                <table class="sales-table">
                    <thead>
                        <tr>
                            <th>ID Venta</th>
                            <th>Fecha/Hora</th>
                            <th>Cliente</th>
                            <th>Productos</th>
                            <th>Total</th>
                            <th>Estado</th>
                            <th>Método Pago</th>
                        </tr>
                    </thead>
                    <tbody id="ventasTableBody">
                       <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td>#V<?= htmlspecialchars($row['venta_id']) ?></td>
                            <td><?= htmlspecialchars($row['fecha_hora']) ?></td>
                            <td><?= htmlspecialchars($row['cliente']) ?></td>
                            <td><?= htmlspecialchars($row['productos']) ?></td>
                            <td>$<?= number_format($row['total_venta'], 2) ?></td>
                            <td>
                              <span class="status-badge 
                                <?php
                                  switch($row['estado']){
                                    case 'entregado': echo 'status-completed'; break;
                                    case 'cerrado':    echo 'status-completed'; break;
                                    case 'anulado':    echo 'status-cancelled'; break;
                                    default:           echo 'status-pending'; break;
                                  }
                                ?>">
                                <?= ucfirst($row['estado']) ?>
                              </span>
                            </td>
                            <td><?= htmlspecialchars($row['metodos_pago'] ?: '—') ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                
                <div class="pagination">
                    <button onclick="previousPage()">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button class="active">1</button>
                    <button>2</button>
                    <button>3</button>
                    <button>4</button>
                    <button>5</button>
                    <button onclick="nextPage()">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </section>
        </main>
    </div>
    <script src="../assets/js/ventas.js"></script>
</body>
</html>