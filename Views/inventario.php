<!DOCTYPE html>
<html lang="es">
<head>
       <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventario - Cafetería</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
      <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            color: #333;
        }

        .container {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            padding: 2rem 0;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
        }

        .logo {
            text-align: center;
            margin-bottom: 2rem;
            padding: 0 1rem;
        }

        .logo h1 {
            color: #8B4513;
            font-size: 1.8rem;
            font-weight: bold;
        }

        .logo p {
            color: #666;
            font-size: 0.9rem;
            margin-top: 0.5rem;
        }

        .nav-menu {
            list-style: none;
            padding: 0 1rem;
        }

        .nav-item {
            margin-bottom: 0.5rem;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            color: #666;
            text-decoration: none;
            border-radius: 10px;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .nav-link:hover,
        .nav-link.active {
            background: linear-gradient(135deg, #8B4513, #D2691E);
            color: white;
            transform: translateX(5px);
        }

        .nav-link i {
            margin-right: 0.75rem;
            width: 20px;
            text-align: center;
        }

        /* Main Content */
        .main-content {
            margin-left: 250px;
            flex: 1;
            padding: 2rem;
        }

        .header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 1.5rem 2rem;
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .header-title {
            color: #333;
            font-size: 1.8rem;
            font-weight: bold;
        }

        .header-actions {
            display: flex;
            gap: 1rem;
            align-items: center;
            flex-wrap: wrap;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
            font-size: 0.9rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, #8B4513, #D2691E);
            color: white;
        }

        .btn-secondary {
            background: #f8f9fa;
            color: #666;
            border: 1px solid #e9ecef;
        }

        .btn-success {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
        }

        .btn-warning {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        /* Search and Filters */
        .controls-section {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 1.5rem;
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
        }

        .controls-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr auto;
            gap: 1rem;
            align-items: center;
        }

        .search-box {
            position: relative;
        }

        .search-input {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 2.5rem;
            border: 1px solid #e9ecef;
            border-radius: 10px;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .search-input:focus {
            outline: none;
            border-color: #8B4513;
            box-shadow: 0 0 0 3px rgba(139, 69, 19, 0.1);
        }

        .search-icon {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            color: #666;
        }

        .filter-select {
            padding: 0.75rem 1rem;
            border: 1px solid #e9ecef;
            border-radius: 10px;
            font-size: 0.9rem;
            background: white;
            cursor: pointer;
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 1.5rem;
            border-radius: 15px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            text-align: center;
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-3px);
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin: 0 auto 1rem;
        }

        .stat-value {
            font-size: 1.8rem;
            font-weight: bold;
            color: #333;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            color: #666;
            font-size: 0.85rem;
        }

        /* Main Table */
        .table-section {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin-bottom: 2rem;
        }

        .table-header {
            padding: 1.5rem 2rem;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .table-title {
            font-size: 1.25rem;
            font-weight: bold;
            color: #333;
        }

        .table-container {
            max-width: 100vw;
            overflow-x: auto;
        }

        .inventory-table {
            width: 100%;
            border-collapse: collapse;
        }

        .inventory-table th,
        .inventory-table td {
            padding: 1rem 1.5rem;
            text-align: left;
            border-bottom: 1px solid #f1f3f4;
        }

        .inventory-table th {
            background: #f8f9fa;
            font-weight: 600;
            color: #374151;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .inventory-table tbody tr {
            transition: background-color 0.2s ease;
        }

        .inventory-table tbody tr:hover {
            background: #f8f9fa;
        }

        .product-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .product-image {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            background: #f1f3f4;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }

        .product-details h4 {
            font-size: 0.9rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 0.25rem;
        }

        .product-details p {
            font-size: 0.8rem;
            color: #666;
        }

        .status-badge {
            padding: 0.375rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-normal {
            background: #d1fae5;
            color: #065f46;
        }

        .status-bajo {
            background: #fef3c7;
            color: #92400e;
        }

        .status-critico {
            background: #fee2e2;
            color: #991b1b;
        }

        .stock-info {
            text-align: center;
        }

        .stock-value {
            font-weight: 600;
            font-size: 1rem;
            color: #333;
        }

        .stock-unit {
            font-size: 0.8rem;
            color: #666;
            margin-top: 0.25rem;
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }

        .btn-sm {
            padding: 0.5rem;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.2s ease;
            font-size: 0.8rem;
        }

        .btn-edit {
            background: #3b82f6;
            color: white;
        }

        .btn-delete {
            background: #ef4444;
            color: white;
        }

        .btn-sm:hover {
            transform: scale(1.1);
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(5px);
        }

        .modal-content {
            background: white;
            margin: 5% auto;
            padding: 0;
            border-radius: 20px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: modalSlideIn 0.3s ease;
        }

        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: translateY(-50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .modal-header {
            padding: 1.5rem 2rem;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-title {
            font-size: 1.25rem;
            font-weight: bold;
            color: #333;
        }

        .close {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: #666;
            padding: 0.5rem;
            border-radius: 6px;
            transition: all 0.2s ease;
        }

        .close:hover {
            background: #f1f3f4;
            color: #333;
        }

        .modal-body {
            padding: 2rem;
            max-height: 60vh;
            overflow-y: auto;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: #374151;
        }

        .form-input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid #e9ecef;
            border-radius: 10px;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .form-input:focus {
            outline: none;
            border-color: #8B4513;
            box-shadow: 0 0 0 3px rgba(139, 69, 19, 0.1);
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .modal-footer {
            padding: 1.5rem 2rem;
            border-top: 1px solid #e9ecef;
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
        }

        /* Alerta de confirmación */
        .confirm-dialog {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 0 50px rgba(0, 0, 0, 0.3);
            z-index: 2000;
            min-width: 300px;
            text-align: center;
        }

        .confirm-dialog h3 {
            margin-bottom: 1rem;
            color: #333;
        }

        .confirm-dialog .btn-group {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-top: 1.5rem;
        }

        .confirm-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(5px);
            z-index: 1999;
        }

        /* Alert Messages */
        .alert {
            padding: 1rem 1.5rem;
            border-radius: 10px;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 500;
        }

        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }

        .alert-warning {
            background: #fef3c7;
            color: #92400e;
            border: 1px solid #fde68a;
        }

        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        /* Responsive Design */
        @media (max-width: 1400px) {
            .stats-grid {
                grid-template-columns: repeat(3, 1fr);
                gap: 1rem;
            }
        }

         @media (max-width: 400px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
            .header-title {
                font-size: 1rem;
            }
            .modal-content {
                width: 100vw;
                margin: 0;
                border-radius: 0;
            }
        }

        @media (max-width: 992px) {
            .sidebar {
                position: fixed;
                left: -250px;
                top: 0;
                height: 100vh;
                z-index: 1000;
                transition: left 0.3s ease;
            }

            .sidebar.active {
                left: 0;
            }

            .main-content {
                margin-left: 0;
                padding: 1rem;
            }

            .header-title {
                font-size: 1.5rem;
            }

            .btn {
                padding: 0.6rem 1rem;
            }
        }

        @media (max-width: 768px) {
            .menu-toggle {
                display: flex !important;
                position: fixed;
                top: 1rem;
                left: 1rem;
                z-index: 1002;
                background: #fff;
                border-radius: 50%;
                width: 44px;
                height: 44px;
                align-items: center;
                justify-content: center;
                border: none;
                box-shadow: 0 2px 10px rgba(0,0,0,0.1);
                cursor: pointer;
            }

            .menu-toggle i {
                font-size: 1.5rem;
                color: #8B4513;
            }

            .sidebar {
                position: fixed;
                left: -100%;
                top: 0;
                width: 80%;
                max-width: 300px;
                height: 100%;
                background: #fff;
                z-index: 1001;
                transition: 0.3s ease-in-out;
                overflow-y: auto;
                -webkit-overflow-scrolling: touch;
            }

            .sidebar.active {
                left: 0;
            }

            .sidebar-overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.5);
                z-index: 1000;
                opacity: 0;
                transition: opacity 0.3s ease;
            }

            .sidebar-overlay.active {
                display: block;
                opacity: 1;
            }

            .main-content {
                margin-left: 0;
                padding-top: 70px;
            }
        }

        @media screen and (max-width: 480px) {
    .header-title {
        font-size: 1.2rem;
    }

    .btn {
        padding: 0.5rem;
        font-size: 0.8rem;
    }

    .stat-value {
        font-size: 1.2rem;
    }

    .modal-content {
        width: 100%;
        height: 100vh;
        margin: 0;
        border-radius: 0;
    }

    .modal-body {
        padding: 1rem;
        height: calc(100vh - 120px);
        overflow-y: auto;
    }

    .action-buttons {
        flex-direction: column;
        gap: 0.5rem;
    }

    .action-buttons .btn {
        width: 100%;
    }
}

/* Overlay para sidebar móvil */
.sidebar-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.5);
    z-index: 999;
}

.sidebar-overlay.active {
    display: block;
}

 html {
            scroll-behavior: smooth;
        }


        body, html {
            width: 100%;
            min-height: 100%;
        }

        .container {
            width: 100vw;
            max-width: 100vw;
        }

        .main-content {
            min-width: 0;
            width: 100%;
            box-sizing: border-box;
        }

    </style>
</head>
<body>
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
                        <a href="#" class="nav-link">
                            <i class="fas fa-tachometer-alt"></i>
                            Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="fas fa-chart-line"></i>
                            Ventas
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link active">
                            <i class="fas fa-boxes"></i>
                            Inventario
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="fas fa-shopping-cart"></i>
                            Pedidos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="fas fa-users"></i>
                            Clientes
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
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

    <script>
        // Base de datos simulada de productos
        let inventoryData = [
            {
                id: 1,
                name: 'Café en grano arábica',
                category: 'bebidas',
                unit: 'kg',
                currentStock: 45,
                minStock: 10,
                unitPrice: 380,
                supplier: 'Café Central Proveedores',
                description: 'Café de alta calidad, mezcla arábica, bolsa de 1kg.',
                lastUpdate: '2025-06-10 09:12',
            },
            {
                id: 2,
                name: 'Leche entera',
                category: 'insumos',
                unit: 'l',
                currentStock: 18,
                minStock: 12,
                unitPrice: 22,
                supplier: 'Lácteos S.A.',
                description: 'Caja de leche entera 1L.',
                lastUpdate: '2025-06-12 14:40',
            },
            {
                id: 3,
                name: 'Azúcar refinada',
                category: 'insumos',
                unit: 'kg',
                currentStock: 4,
                minStock: 8,
                unitPrice: 12,
                supplier: 'Dulzura S.A.',
                description: 'Bolsa de azúcar refinada 1kg.',
                lastUpdate: '2025-06-13 08:20',
            },
            {
                id: 4,
                name: 'Taza cerámica',
                category: 'utensilios',
                unit: 'unidad',
                currentStock: 35,
                minStock: 15,
                unitPrice: 25,
                supplier: 'Proveedora Barista',
                description: 'Taza blanca para café 250ml.',
                lastUpdate: '2025-06-13 10:10',
            },
            {
                id: 5,
                name: 'Croissant',
                category: 'alimentos',
                unit: 'unidad',
                currentStock: 8,
                minStock: 20,
                unitPrice: 28,
                supplier: 'Panadería París',
                description: 'Croissant tradicional, pieza individual.',
                lastUpdate: '2025-06-13 09:05',
            }
        ];

        // Utilidad para status
        function getStockStatus(product) {
            if (product.currentStock <= product.minStock / 2) {
                return { status: 'Crítico', class: 'status-critico' };
            } else if (product.currentStock <= product.minStock) {
                return { status: 'Bajo', class: 'status-bajo' };
            } else {
                return { status: 'Normal', class: 'status-normal' };
            }
        }

        // Renderizar tabla de inventario
        function renderInventoryTable(data = inventoryData) {
            const tbody = document.getElementById('inventoryTableBody');
            tbody.innerHTML = '';
            if (data.length === 0) {
                tbody.innerHTML = `<tr><td colspan="9" style="text-align:center; color:#888;">Sin productos</td></tr>`;
                return;
            }
            data.forEach(product => {
                const statusObj = getStockStatus(product);
                const totalValue = (product.currentStock * product.unitPrice).toLocaleString('es-MX', { style: 'currency', currency: 'MXN', minimumFractionDigits: 2 });
                tbody.innerHTML += `
                    <tr>
                        <td>
                            <div class="product-info">
                                <div class="product-image"><i class="fas fa-box"></i></div>
                                <div class="product-details">
                                    <h4>${product.name}</h4>
                                    <p>${product.description || ''}</p>
                                </div>
                            </div>
                        </td>
                        <td>${capitalize(product.category)}</td>
                        <td class="stock-info">
                            <div class="stock-value">${product.currentStock}</div>
                            <div class="stock-unit">${product.unit}</div>
                        </td>
                        <td>${product.minStock}</td>
                        <td>${product.unitPrice.toLocaleString('es-MX', { style: 'currency', currency: 'MXN', minimumFractionDigits: 2 })}</td>
                        <td>${totalValue}</td>
                        <td>
                            <span class="status-badge ${statusObj.class}">${statusObj.status}</span>
                        </td>
                        <td>${product.lastUpdate}</td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn btn-edit btn-sm" onclick="openModal('edit', ${product.id})"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-delete btn-sm" onclick="deleteProduct(${product.id})"><i class="fas fa-trash-alt"></i></button>
                            </div>
                        </td>
                    </tr>
                `;
            });
        }

        // Actualizar tarjetas de estadísticas
        function updateStats() {
            const totalProducts = inventoryData.length;
            let normal = 0, bajo = 0, critico = 0, totalValue = 0;
            inventoryData.forEach(p => {
                const status = getStockStatus(p).status;
                if (status === 'Normal') normal++;
                else if (status === 'Bajo') bajo++;
                else critico++;
                totalValue += p.currentStock * p.unitPrice;
            });
            document.getElementById('totalProducts').textContent = totalProducts;
            document.getElementById('normalStock').textContent = normal;
            document.getElementById('lowStock').textContent = bajo;
            document.getElementById('criticalStock').textContent = critico;
            document.getElementById('totalValue').textContent = totalValue.toLocaleString('es-MX', { style: 'currency', currency: 'MXN', minimumFractionDigits: 2 });
        }

        // Capitaliza primera letra
        function capitalize(str) {
            if (!str) return '';
            return str.charAt(0).toUpperCase() + str.slice(1);
        }

        // Modal
        function openModal(mode, id = null) {
            document.getElementById('productModal').style.display = 'block';
            document.getElementById('productForm').reset();
            document.getElementById('productId').value = '';
            document.getElementById('saveButtonText').textContent = mode === 'edit' ? 'Actualizar' : 'Guardar';
            document.getElementById('modalTitle').textContent = mode === 'edit' ? 'Editar Producto' : 'Agregar Producto';
            if (mode === 'edit' && id != null) {
                const product = inventoryData.find(p => p.id === id);
                if (product) {
                    document.getElementById('productId').value = product.id;
                    document.getElementById('productName').value = product.name;
                    document.getElementById('productCategory').value = product.category;
                    document.getElementById('productUnit').value = product.unit;
                    document.getElementById('currentStock').value = product.currentStock;
                    document.getElementById('minStock').value = product.minStock;
                    document.getElementById('unitPrice').value = product.unitPrice;
                    document.getElementById('supplier').value = product.supplier;
                    document.getElementById('description').value = product.description || '';
                }
            }
        }
        function closeModal() {
            document.getElementById('productModal').style.display = 'none';
        }

        // Guardar producto (Agregar o Editar)
        function saveProduct() {
            const form = document.getElementById('productForm');
            if (!form.reportValidity()) return;
            const id = document.getElementById('productId').value;
            const prod = {
                id: id ? parseInt(id) : (inventoryData.length ? Math.max(...inventoryData.map(p => p.id)) + 1 : 1),
                name: document.getElementById('productName').value,
                category: document.getElementById('productCategory').value,
                unit: document.getElementById('productUnit').value,
                currentStock: parseFloat(document.getElementById('currentStock').value),
                minStock: parseFloat(document.getElementById('minStock').value),
                unitPrice: parseFloat(document.getElementById('unitPrice').value),
                supplier: document.getElementById('supplier').value,
                description: document.getElementById('description').value,
                lastUpdate: new Date().toISOString().slice(0, 16).replace('T', ' ')
            };

            document.getElementById('saveButtonText').style.display = 'none';
            document.getElementById('saveButtonLoading').style.display = 'inline-block';

            setTimeout(() => {
                if (id) {
                    // Editar
                    const idx = inventoryData.findIndex(p => p.id === prod.id);
                    if (idx !== -1) inventoryData[idx] = prod;
                    showAlert('¡Producto actualizado exitosamente!', 'success');
                } else {
                    // Agregar
                    inventoryData.push(prod);
                    showAlert('¡Producto agregado exitosamente!', 'success');
                }
                closeModal();
                renderInventoryTable();
                updateStats();
                document.getElementById('saveButtonText').style.display = 'inline';
                document.getElementById('saveButtonLoading').style.display = 'none';
            }, 800);
        }

        // Eliminar producto
        function deleteProduct(id) {
            const product = inventoryData.find(p => p.id === id);
            if (!product) return;

            const overlay = document.createElement('div');
            overlay.className = 'confirm-overlay';
            
            const dialog = document.createElement('div');
            dialog.className = 'confirm-dialog';
            dialog.innerHTML = `
                <h3>¿Estás seguro?</h3>
                <p>¿Deseas eliminar el producto "${product.name}"?</p>
                <div class="btn-group">
                    <button class="btn btn-secondary" onclick="this.closest('.confirm-overlay').remove()">Cancelar</button>
                    <button class="btn btn-delete" onclick="confirmDelete(${id}, this)">Eliminar</button>
                </div>
            `;
            
            overlay.appendChild(dialog);
            document.body.appendChild(overlay);
        }

        function confirmDelete(id, button) {
            button.disabled = true;
            button.innerHTML = '<span class="loading"></span>';
            
            setTimeout(() => {
                inventoryData = inventoryData.filter(p => p.id !== id);
                renderInventoryTable();
                updateStats();
                button.closest('.confirm-overlay').remove();
                showAlert('Producto eliminado exitosamente.', 'success');
            }, 800);
        }

        // Barra de búsqueda y filtros
        function applyFilters() {
            const search = document.getElementById('searchInput').value.toLowerCase();
            const category = document.getElementById('categoryFilter').value;
            const status = document.getElementById('statusFilter').value;
            let data = inventoryData.filter(p => 
                p.name.toLowerCase().includes(search) ||
                (p.description && p.description.toLowerCase().includes(search))
            );
            if (category) data = data.filter(p => p.category === category);
            if (status) {
                data = data.filter(p => getStockStatus(p).status.toLowerCase() === status);
            }
            renderInventoryTable(data);
        }

        document.getElementById('searchInput').addEventListener('input', applyFilters);
        document.getElementById('categoryFilter').addEventListener('change', applyFilters);
        document.getElementById('statusFilter').addEventListener('change', applyFilters);

        function clearFilters() {
            document.getElementById('searchInput').value = '';
            document.getElementById('categoryFilter').value = '';
            document.getElementById('statusFilter').value = '';
            renderInventoryTable();
        }

        // Mensaje de alerta
        function showAlert(message, type = 'success') {
            const alertContainer = document.getElementById('alertContainer');
            const alertClass = type === 'success' ? 'alert-success' : type === 'warning' ? 'alert-warning' : 'alert-error';
            alertContainer.innerHTML = `<div class="alert ${alertClass}"><i class="fas fa-info-circle"></i>${message}</div>`;
            setTimeout(() => { alertContainer.innerHTML = ''; }, 2500);
        }

        // Refrescar datos (simulado)
        function refreshData() {
            showAlert('Datos actualizados.', 'success');
            renderInventoryTable();
            updateStats();
        }

        // Exportar inventario a CSV (simple)
        function exportInventory() {
            let csv = 'Nombre,Categoría,Unidad,Stock Actual,Stock Mínimo,Precio Unitario,Proveedor,Descripción,Última Actualización\n';
            inventoryData.forEach(p => {
                csv += `"${p.name}","${capitalize(p.category)}","${p.unit}",${p.currentStock},${p.minStock},${p.unitPrice},"${p.supplier}","${p.description || ''}","${p.lastUpdate}"\n`;
            });
            const blob = new Blob([csv], { type: 'text/csv' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'inventario.csv';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            showAlert('Inventario exportado exitosamente.', 'success');
        }

        // Generar Reporte (simulado)
        function generateReport() {
            showAlert('Reporte generado (funcionalidad demo).', 'success');
        }

        // Función para manejar el menú en dispositivos móviles
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            sidebar.classList.toggle('active');
        }

        // Agregar botón de menú en el header para móviles
        document.querySelector('.header').insertAdjacentHTML('afterbegin', `
            <button class="btn btn-secondary d-md-none" onclick="toggleSidebar()" style="display:none;">
                <i class="fas fa-bars"></i>
            </button>
        `);

        // Cerrar sidebar al hacer clic fuera en dispositivos móviles
        document.addEventListener('click', (e) => {
            const sidebar = document.querySelector('.sidebar');
            if (sidebar.classList.contains('active') && !sidebar.contains(e.target) && 
                !e.target.closest('.btn-secondary')) {
                sidebar.classList.remove('active');
            }
        });

        // Mejoras para dispositivos móviles
document.addEventListener('DOMContentLoaded', function() {
    // Crear botón del menú
    const menuButton = document.createElement('button');
    menuButton.className = 'menu-toggle';
    menuButton.innerHTML = '<i class="fas fa-bars"></i>';
    document.body.appendChild(menuButton);

    // Crear overlay
    const overlay = document.createElement('div');
    overlay.className = 'sidebar-overlay';
    document.body.appendChild(overlay);

    const sidebar = document.querySelector('.sidebar');

    function toggleMenu() {
        sidebar.classList.toggle('active');
        overlay.classList.toggle('active');
        document.body.style.overflow = sidebar.classList.contains('active') ? 'hidden' : '';
        menuButton.innerHTML = sidebar.classList.contains('active') ? 
            '<i class="fas fa-times"></i>' : 
            '<i class="fas fa-bars"></i>';
    }

    menuButton.addEventListener('click', toggleMenu);
    overlay.addEventListener('click', toggleMenu);

    // Cerrar menú al hacer clic en enlaces
    document.querySelectorAll('.nav-link').forEach(link => {
        link.addEventListener('click', () => {
            if (window.innerWidth <= 768) {
                toggleMenu();
            }
        });
    });

    // Manejar redimensionamiento de ventana
    function handleResize() {
        const isMobile = window.innerWidth <= 768;
        menuButton.style.display = isMobile ? 'flex' : 'none';
        
        if (!isMobile && sidebar.classList.contains('active')) {
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
            document.body.style.overflow = '';
        }
    }

    window.addEventListener('resize', handleResize);
    handleResize(); // Verificar estado inicial

    // Manejar cambios de orientación
    window.addEventListener('orientationchange', () => {
        if (sidebar.classList.contains('active')) {
            toggleMenu();
        }
    });
});
    </script>
</body>
</html>