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

        // Inicialización del menú móvil
document.addEventListener('DOMContentLoaded', function() {
    const menuToggle = document.querySelector('.menu-toggle');
    const sidebar = document.querySelector('.sidebar');
    const overlay = document.querySelector('.sidebar-overlay');

    function toggleMenu() {
        sidebar.classList.toggle('active');
        overlay.classList.toggle('active');
        document.body.style.overflow = sidebar.classList.contains('active') ? 'hidden' : '';
        menuToggle.innerHTML = sidebar.classList.contains('active') ? 
            '<i class="fas fa-times"></i>' : 
            '<i class="fas fa-bars"></i>';
    }

    // Event listeners para el menú móvil
    menuToggle.addEventListener('click', toggleMenu);
    overlay.addEventListener('click', toggleMenu);

    // Cerrar menú al hacer clic en enlaces
    document.querySelectorAll('.nav-link').forEach(link => {
        link.addEventListener('click', () => {
            if (window.innerWidth <= 768) {
                toggleMenu();
            }
        });
    });

    // Manejar resize de la ventana
    function handleResize() {
        if (window.innerWidth > 768) {
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
            document.body.style.overflow = '';
            menuToggle.innerHTML = '<i class="fas fa-bars"></i>';
        }
    }

    window.addEventListener('resize', handleResize);
    window.addEventListener('orientationchange', handleResize);
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

// Mejorar manejo táctil
document.addEventListener('DOMContentLoaded', function() {
    // Detectar dispositivo táctil
    const isTouchDevice = 'ontouchstart' in window || navigator.maxTouchPoints > 0;
    
    if (isTouchDevice) {
        document.body.classList.add('touch-device');
        
        // Mejorar interacción con botones en dispositivos táctiles
        const buttons = document.querySelectorAll('.btn, .nav-link');
        buttons.forEach(button => {
            button.addEventListener('touchstart', function(e) {
                this.style.transform = 'scale(0.98)';
            });
            
            button.addEventListener('touchend', function(e) {
                this.style.transform = 'none';
            });
        });
    }

    // Optimizar desplazamiento en dispositivos móviles
    const tableContainer = document.querySelector('.table-container');
    if (tableContainer) {
        let startX;
        let scrollLeft;

        tableContainer.addEventListener('touchstart', (e) => {
            startX = e.touches[0].pageX - tableContainer.offsetLeft;
            scrollLeft = tableContainer.scrollLeft;
        });

        tableContainer.addEventListener('touchmove', (e) => {
            e.preventDefault();
            const x = e.touches[0].pageX - tableContainer.offsetLeft;
            const walk = (x - startX) * 2;
            tableContainer.scrollLeft = scrollLeft - walk;
        });
    }

    // Manejar cambios de orientación
    window.addEventListener('orientationchange', () => {
        setTimeout(() => {
            updateLayout();
        }, 100);
    });

    function updateLayout() {
        const vh = window.innerHeight * 0.01;
        document.documentElement.style.setProperty('--vh', `${vh}px`);
    }

    updateLayout();
    window.addEventListener('resize', updateLayout);
});