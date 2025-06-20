<!DOCTYPE html>
<html lang="es">
<head>
       <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventario - Cafetería</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="../assets/css/inventario.css">
</head>
<body>
    <!-- Botón del menú móvil -->
    <button class="menu-toggle">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Overlay para el sidebar -->
    <div class="sidebar-overlay"></div>

    <div class="container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="logo">
                <h1><i class="fas fa-coffee"></i> Café Central</h1>
                <p>Control de Inventario</p>
            </div>
            <nav>
                <ul class="nav-menu">
                    <li class="nav-item">
                        <a href="../Views/dashboard.php" class="nav-link">
                            <i class="fas fa-tachometer-alt"></i>
                            Inicio
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="../Views/ventas.php" class="nav-link">
                            <i class="fas fa-chart-line"></i>
                            Ventas
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="../Views/inventario.php" class="nav-link active">
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
                <h1 class="header-title">Control de Inventario</h1>
                <div class="header-actions">
                    <button class="btn btn-success" onclick="openModal('add')">
                        <i class="fas fa-plus"></i>
                        Agregar Producto
                    </button>
                    <button class="btn btn-secondary" onclick="exportInventory()">
                        <i class="fas fa-download"></i>
                        Exportar
                    </button>
                    <button class="btn btn-warning" onclick="generateReport()">
                        <i class="fas fa-file-alt"></i>
                        Reporte
                    </button>
                </div>
            </header>

            <!-- Alert Messages -->
            <div id="alertContainer"></div>

            <!-- Controls Section -->
            <section class="controls-section">
                <div class="controls-grid">
                    <div class="search-box">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" class="search-input" id="searchInput" placeholder="Buscar productos...">
                    </div>
                    <select class="filter-select" id="categoryFilter">
                        <option value="">Todas las categorías</option>
                        <option value="bebidas">Bebidas</option>
                        <option value="alimentos">Alimentos</option>
                        <option value="insumos">Insumos</option>
                        <option value="utensilios">Utensilios</option>
                    </select>
                    <select class="filter-select" id="statusFilter">
                        <option value="">Todos los estados</option>
                        <option value="normal">Normal</option>
                        <option value="bajo">Bajo</option>
                        <option value="critico">Crítico</option>
                    </select>
                    <button class="btn btn-secondary" onclick="clearFilters()">
                        <i class="fas fa-times"></i>
                        Limpiar
                    </button>
                </div>
            </section>

            <!-- Stats Cards -->
            <section class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon" style="background: #e6f3ff; color: #0066cc;">
                        <i class="fas fa-box"></i>
                    </div>
                    <div class="stat-value" id="totalProducts">245</div>
                    <div class="stat-label">Total Productos</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon" style="background: #e6ffe6; color: #009900;">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-value" id="normalStock">198</div>
                    <div class="stat-label">Stock Normal</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon" style="background: #fff2e6; color: #ff8c00;">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div class="stat-value" id="lowStock">32</div>
                    <div class="stat-label">Stock Bajo</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon" style="background: #ffe6e6; color: #cc0000;">
                        <i class="fas fa-times-circle"></i>
                    </div>
                    <div class="stat-value" id="criticalStock">15</div>
                    <div class="stat-label">Stock Crítico</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon" style="background: #f0e6ff; color: #8b00ff;">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                    <div class="stat-value" id="totalValue">$25,840</div>
                    <div class="stat-label">Valor Total</div>
                </div>
            </section>

            <!-- Main Table -->
            <section class="table-section">
                <div class="table-header">
                    <h3 class="table-title">Inventario Actual</h3>
                    <div class="header-actions">
                        <button class="btn btn-secondary btn-sm" onclick="refreshData()">
                            <i class="fas fa-sync-alt"></i>
                            Actualizar
                        </button>
                    </div>
                </div>
                <div class="table-container">
                    <table class="inventory-table">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Categoría</th>
                                <th>Stock Actual</th>
                                <th>Stock Mínimo</th>
                                <th>Precio Unitario</th>
                                <th>Valor Total</th>
                                <th>Estado</th>
                                <th>Última Actualización</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="inventoryTableBody">
                            <!-- Datos serán insertados dinámicamente -->
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>

    <!-- Modal para Agregar/Editar Producto -->
    <div id="productModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="modalTitle">Agregar Producto</h3>
                <button class="close" onclick="closeModal()">&times;</button>
            </div>
            <div class="modal-body">
                <form id="productForm">
                    <input type="hidden" id="productId">
                    <div class="form-group">
                        <label class="form-label" for="productName">Nombre del Producto</label>
                        <input type="text" class="form-input" id="productName" required>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label" for="productCategory">Categoría</label>
                            <select class="form-input" id="productCategory" required>
                                <option value="">Seleccionar categoría</option>
                                <option value="bebidas">Bebidas</option>
                                <option value="alimentos">Alimentos</option>
                                <option value="insumos">Insumos</option>
                                <option value="utensilios">Utensilios</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="productUnit">Unidad de Medida</label>
                            <select class="form-input" id="productUnit" required>
                                <option value="">Seleccionar unidad</option>
                                <option value="kg">Kilogramos (kg)</option>
                                <option value="g">Gramos (g)</option>
                                <option value="l">Litros (L)</option>
                                <option value="ml">Mililitros (ml)</option>
                                <option value="unidad">Unidades</option>
                                <option value="paquete">Paquetes</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label" for="currentStock">Stock Actual</label>
                            <input type="number" class="form-input" id="currentStock" min="0" step="0.01" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="minStock">Stock Mínimo</label>
                            <input type="number" class="form-input" id="minStock" min="0" step="0.01" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label" for="unitPrice">Precio Unitario</label>
                            <input type="number" class="form-input" id="unitPrice" min="0" step="0.01" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="supplier">Proveedor</label>
                            <input type="text" class="form-input" id="supplier">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="description">Descripción</label>
                        <textarea class="form-input" id="description" rows="3" placeholder="Descripción opcional del producto"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="saveProduct()">
                    <span id="saveButtonText">Guardar</span>
                    <span id="saveButtonLoading" class="loading" style="display: none;"></span>
                </button>
            </div>
        </div>
    </div>
<script src="../assets/js/inventario.js"></script>
</body>
</html>