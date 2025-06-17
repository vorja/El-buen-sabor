<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Cafetería</title>
    <!-- Agregar Chart.js antes de usar sus funciones -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="../assets/js/dashboard.js"></script>
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
                        <a href="#" class="nav-link active" data-section="dashboard">
                            <i class="fas fa-tachometer-alt"></i>
                            Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link" data-section="ventas">
                            <i class="fas fa-chart-line"></i>
                            Ventas
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link" data-section="inventario">
                            <i class="fas fa-boxes"></i>
                            Inventario
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link" data-section="pedidos">
                            <i class="fas fa-shopping-cart"></i>
                            Pedidos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link" data-section="clientes">
                            <i class="fas fa-users"></i>
                            Clientes
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link" data-section="reportes">
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
                <h1 class="header-title">Dashboard Principal</h1>
                <div class="header-info">
                    <div class="date-time" id="dateTime"></div>
                    <div class="user-info">
                        <i class="fas fa-user-circle"></i>
                        <span>Admin</span>
                    </div>
                </div>
            </header>

            <!-- Metrics Cards -->
            <section class="metrics-grid">
                <div class="metric-card">
                    <div class="metric-header">
                        <span class="metric-title">Ventas de Hoy</span>
                        <div class="metric-icon" style="background: #e6f3ff; color: #0066cc;">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                    </div>
                    <div class="metric-value" id="ventasHoy">$2,450.75</div>
                    <div class="metric-subtitle" id="total_pedidos">89 pedidos realizados</div>
                    <div class="metric-trend">
                        <i class="fas fa-arrow-up trend-positive"></i>
                        <span class="trend-positive" id="porcentaje">+12.5%</span>
                        <span>vs ayer</span>
                    </div>
                </div>

                <div class="metric-card">
                    <div class="metric-header">
                        <span class="metric-title">Pedidos Activos</span>
                        <div class="metric-icon" style="background: #fff2e6; color: #ff8c00;">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                    </div>
                    <div class="metric-value" id="pedidosActivos">24</div>
                    <div class="metric-subtitle">En preparación y entrega</div>
                    <div class="metric-trend">
                        <i class="fas fa-clock"></i>
                        <span>Tiempo promedio: 8 min</span>
                    </div>
                </div>

                <div class="metric-card">
                    <div class="metric-header">
                        <span class="metric-title">Clientes Atendidos</span>
                        <div class="metric-icon" style="background: #e6ffe6; color: #009900;">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                    <div class="metric-value" id="clientesHoy">156</div>
                    <div class="metric-subtitle">Clientes únicos hoy</div>
                    <div class="metric-trend">
                        <i class="fas fa-arrow-up trend-positive"></i>
                        <span class="trend-positive">+8.3%</span>
                        <span>vs ayer</span>
                    </div>
                </div>

                <div class="metric-card">
                    <div class="metric-header">
                        <span class="metric-title">Producto Popular</span>
                        <div class="metric-icon" style="background: #ffe6f2; color: #cc0066;">
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                    <div class="metric-value" style="font-size: 1.8rem;" id="ProductoPopular">Cappuccino</div>
                    <div class="metric-subtitle" id="ppVendidoHoy">142 vendidos hoy</div>
                    <div class="metric-trend">
                        <i class="fas fa-coffee"></i>
                        <span>$852 en ingresos</span>
                    </div>
                </div>
            </section>

            <!-- Charts Section -->
            <section class="charts-grid">
                <div class="chart-card">
                    <h3 class="chart-title">Ventas de la Semana</h3>
                    <div class="chart-container">
                        <canvas id="ventasChart"></canvas>
                    </div>
                </div>

                <div class="chart-card">
                    <h3 class="chart-title">Productos Más Vendidos</h3>
                    <div class="chart-container">
                        <canvas id="productosChart"></canvas>
                    </div>
                </div>
            </section>

            <!-- Tables Section -->
            <section class="table-section">
                <div class="table-card">
                    <h3 class="table-title">Inventario Actual</h3>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Stock</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody id="inventarioTable">
                            <tr>
                                <td>Café en grano</td>
                                <td>45 kg</td>
                                <td><span class="status-badge status-normal">Normal</span></td>
                            </tr>
                            <tr>
                                <td>Leche</td>
                                <td>12 L</td>
                                <td><span class="status-badge status-bajo">Bajo</span></td>
                            </tr>
                            <tr>
                                <td>Azúcar</td>
                                <td>8 kg</td>
                                <td><span class="status-badge status-critico">Crítico</span></td>
                            </tr>
                            <tr>
                                <td>Croissants</td>
                                <td>25 unid</td>
                                <td><span class="status-badge status-normal">Normal</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="table-card">
                    <h3 class="table-title">Pedidos Recientes</h3>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Cliente</th>
                                <th>Total</th>
                                <th>Hora</th>
                            </tr>
                        </thead>
                        <tbody id="pedidosTable">
                            <tr>
                                <td>#001</td>
                                <td>Ana García</td>
                                <td>$18.50</td>
                                <td>14:23</td>
                            </tr>
                            <tr>
                                <td>#002</td>
                                <td>Carlos López</td>
                                <td>$12.00</td>
                                <td>14:20</td>
                            </tr>
                            <tr>
                                <td>#003</td>
                                <td>María Rodríguez</td>
                                <td>$15.75</td>
                                <td>14:18</td>
                            </tr>
                            <tr>
                                <td>#004</td>
                                <td>José Martínez</td>
                                <td>$9.00</td>
                                <td>14:15</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>
</body>
<link rel="stylesheet" href="../assets/css/dashboard.css">
</html>