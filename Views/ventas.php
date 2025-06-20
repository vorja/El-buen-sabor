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
                        <tr>
                            <td>#V001</td>
                            <td>17/06/2025 14:23</td>
                            <td>Ana García</td>
                            <td>2x Cappuccino, 1x Croissant</td>
                            <td>$18.50</td>
                            <td><span class="status-badge status-completed">Completado</span></td>
                            <td>Tarjeta</td>
                        </tr>
                        <tr>
                            <td>#V002</td>
                            <td>17/06/2025 14:20</td>
                            <td>Carlos López</td>
                            <td>1x Americano, 1x Sandwich</td>
                            <td>$12.00</td>
                            <td><span class="status-badge status-completed">Completado</span></td>
                            <td>Efectivo</td>
                        </tr>
                        <tr>
                            <td>#V003</td>
                            <td>17/06/2025 14:18</td>
                            <td>María Rodríguez</td>
                            <td>1x Latte, 2x Muffin</td>
                            <td>$15.75</td>
                            <td><span class="status-badge status-pending">Pendiente</span></td>
                            <td>Transferencia</td>
                        </tr>
                        <tr>
                            <td>#V004</td>
                            <td>17/06/2025 14:15</td>
                            <td>José Martínez</td>
                            <td>1x Espresso</td>
                            <td>$9.00</td>
                            <td><span class="status-badge status-completed">Completado</span></td>
                            <td>Tarjeta</td>
                        </tr>
                        <tr>
                            <td>#V005</td>
                            <td>17/06/2025 14:12</td>
                            <td>Laura Fernández</td>
                            <td>3x Cappuccino, 1x Tarta</td>
                            <td>$24.30</td>
                            <td><span class="status-badge status-cancelled">Cancelado</span></td>
                            <td>Efectivo</td>
                        </tr>
                        <tr>
                            <td>#V006</td>
                            <td>17/06/2025 14:10</td>
                            <td>Pedro Sánchez</td>
                            <td>2x Americano, 1x Bagel</td>
                            <td>$13.25</td>
                            <td><span class="status-badge status-completed">Completado</span></td>
                            <td>Tarjeta</td>
                        </tr>
                        <tr>
                            <td>#V007</td>
                            <td>17/06/2025 14:08</td>
                            <td>Sofía Castro</td>
                            <td>1x Latte, 1x Galleta</td>
                            <td>$8.50</td>
                            <td><span class="status-badge status-completed">Completado</span></td>
                            <td>Efectivo</td>
                        </tr>
                        <tr>
                            <td>#V008</td>
                            <td>17/06/2025 14:05</td>
                            <td>Miguel Torres</td>
                            <td>1x Cappuccino, 1x Croissant</td>
                            <td>$11.75</td>
                            <td><span class="status-badge status-pending">Pendiente</span></td>
                            <td>Transferencia</td>
                        </tr>
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