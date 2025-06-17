   // Declarar variables globales para los gráficos
        let ventasChart = null;
        let productosChart = null;

        // Datos de ejemplo
        const dashboardData = {
            ventasSemanales: [
                { dia: 'Lun', ventas: 1200, pedidos: 45 },
                { dia: 'Mar', ventas: 1400, pedidos: 52 },
                { dia: 'Mié', ventas: 1100, pedidos: 38 },
                { dia: 'Jue', ventas: 1800, pedidos: 67 },
                { dia: 'Vie', ventas: 2200, pedidos: 78 },
                { dia: 'Sáb', ventas: 2500, pedidos: 89 },
                { dia: 'Dom', ventas: 1900, pedidos: 71 }
            ],
            productosPopulares: [
                { nombre: 'Cappuccino', ventas: 142 },
                { nombre: 'Americano', ventas: 128 },
                { nombre: 'Latte', ventas: 95 },
                { nombre: 'Espresso', ventas: 76 },
                { nombre: 'Croissant', ventas: 89 }
            ]
        };

        // Inicialización
        document.addEventListener('DOMContentLoaded', function() {
            initializeDateTime();
            initializeCharts();
            initializeNavigation();
            initializeRealTimeUpdates();
        });

        // Función para mostrar fecha y hora actual
        function initializeDateTime() {
            function updateDateTime() {
                const now = new Date();
                const options = { 
                    weekday: 'long', 
                    year: 'numeric', 
                    month: 'long', 
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                };
                document.getElementById('dateTime').textContent = now.toLocaleDateString('es-ES', options);
            }
            
            updateDateTime();
            setInterval(updateDateTime, 60000); // Actualizar cada minuto
        }

        // Función para inicializar gráficos
        function initializeCharts() {
            if (ventasChart) ventasChart.destroy();
            if (productosChart) productosChart.destroy();

            // Configuración común para responsividad
            const commonOptions = {
                responsive: true,
                maintainAspectRatio: false,
                layout: {
                    padding: {
                        left: 10,
                        right: 10,
                        top: 20,
                        bottom: 20
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: window.innerWidth < 768 ? 'bottom' : 'right',
                        labels: {
                            boxWidth: window.innerWidth < 576 ? 12 : 20,
                            font: {
                                size: window.innerWidth < 576 ? 10 : 12
                            }
                        }
                    }
                }
            };

            // Gráfico de ventas semanales
            const ctxVentas = document.getElementById('ventasChart').getContext('2d');
            ventasChart = new Chart(ctxVentas, {
                type: 'line',
                data: {
                    labels: dashboardData.ventasSemanales.map(item => item.dia),
                    datasets: [{
                        label: 'Ventas ($)',
                        data: dashboardData.ventasSemanales.map(item => item.ventas),
                        borderColor: '#8B4513',
                        backgroundColor: 'rgba(139, 69, 19, 0.1)',
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: '#8B4513',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: window.innerWidth < 576 ? 3 : 6
                    }]
                },
                options: {
                    ...commonOptions,
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0,0,0,0.1)'
                            },
                            ticks: {
                                callback: function(value) {
                                    return '$' + value;
                                },
                                font: {
                                    size: window.innerWidth < 576 ? 10 : 12
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    size: window.innerWidth < 576 ? 10 : 12
                                }
                            }
                        }
                    }
                }
            });

            // Gráfico de productos populares
            const ctxProductos = document.getElementById('productosChart').getContext('2d');
            productosChart = new Chart(ctxProductos, {
                type: 'doughnut',
                data: {
                    labels: dashboardData.productosPopulares.map(item => item.nombre),
                    datasets: [{
                        data: dashboardData.productosPopulares.map(item => item.ventas),
                        backgroundColor: [
                            '#8B4513',
                            '#D2691E',
                            '#F4A460',
                            '#DEB887',
                            '#A0522D'
                        ],
                        borderWidth: 0,
                        cutout: window.innerWidth < 576 ? '70%' : '60%'
                    }]
                },
                options: {
                    ...commonOptions,
                    plugins: {
                        legend: {
                            position: window.innerWidth < 992 ? 'bottom' : 'right',
                            labels: {
                                padding: window.innerWidth < 576 ? 10 : 20,
                                usePointStyle: true,
                                font: {
                                    size: window.innerWidth < 576 ? 10 : 12
                                }
                            }
                        }
                    }
                }
            });
        }

        // Función para navegación
        function initializeNavigation() {
            const navLinks = document.querySelectorAll('.nav-link');
            
            navLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    // Remover clase activa de todos los enlaces
                    navLinks.forEach(l => l.classList.remove('active'));
                    
                    // Agregar clase activa al enlace clickeado
                    this.classList.add('active');
                    
                    // Aquí puedes agregar lógica para cambiar el contenido según la sección
                    const section = this.getAttribute('data-section');
                    handleSectionChange(section);
                });
            });
        }

        // Función para manejar cambio de sección
        function handleSectionChange(section) {
            const headerTitle = document.querySelector('.header-title');
            
            switch(section) {
                case 'dashboard':
                    headerTitle.textContent = 'Dashboard Principal';
                    break;
                case 'ventas':
                    headerTitle.textContent = 'Gestión de Ventas';
                    break;
                case 'inventario':
                    headerTitle.textContent = 'Control de Inventario';
                    break;
                case 'pedidos':
                    headerTitle.textContent = 'Gestión de Pedidos';
                    break;
                case 'clientes':
                    headerTitle.textContent = 'Gestión de Clientes';
                    break;
                case 'reportes':
                    headerTitle.textContent = 'Reportes y Análisis';
                    break;
            }
        }

        // Función para actualizaciones en tiempo real
        function initializeRealTimeUpdates() {
            // Simular actualizaciones en tiempo real
            setInterval(() => {
                updateMetrics();
                updateTables();
            }, 30000); // Actualizar cada 30 segundos
        }

        // Función para actualizar métricas
        function updateMetrics() {
            // Simular cambios en las métricas
            const ventasElement = document.getElementById('ventasHoy');
            const pedidosElement = document.getElementById('pedidosActivos');
            const clientesElement = document.getElementById('clientesHoy');
            
            // Generar valores aleatorios realistas
            const nuevasVentas = (Math.random() * 500 + 2000).toFixed(2);
            const nuevosPedidos = Math.floor(Math.random() * 10 + 20);
            const nuevosClientes = Math.floor(Math.random() * 20 + 140);
            
            // Actualizar con animación
            animateCountUp(ventasElement, parseFloat(ventasElement.textContent.replace('$', '').replace(',', '')), parseFloat(nuevasVentas), '$');
            animateCountUp(pedidosElement, parseInt(pedidosElement.textContent), nuevosPedidos);
            animateCountUp(clientesElement, parseInt(clientesElement.textContent), nuevosClientes);
        }

        // Función para animar contadores
        function animateCountUp(element, start, end, prefix = '') {
            const duration = 1000;
            const startTime = performance.now();
            
            function updateCount(currentTime) {
                const elapsed = currentTime - startTime;
                const progress = Math.min(elapsed / duration, 1);
                
                const currentValue = start + (end - start) * progress;
                const displayValue = prefix + (prefix === '$' ? currentValue.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',') : Math.floor(currentValue));
                
                element.textContent = displayValue;
                
                if (progress < 1) {
                    requestAnimationFrame(updateCount);
                }
            }
            
            requestAnimationFrame(updateCount);
        }

        // Función para actualizar tablas
        function updateTables() {
            // Actualizar tabla de pedidos recientes
            const pedidosTable = document.getElementById('pedidosTable');
            const nuevoPedido = generateRandomOrder();
            
            // Agregar nuevo pedido al inicio de la tabla
            const newRow = pedidosTable.insertRow(0);
            newRow.innerHTML = `
                <td>${nuevoPedido.id}</td>
                <td>${nuevoPedido.cliente}</td>
                <td>${nuevoPedido.total}</td>
                <td>${nuevoPedido.hora}</td>
            `;
            
            // Mantener solo los últimos 4 pedidos
            if (pedidosTable.rows.length > 4) {
                pedidosTable.deleteRow(4);
            }
            
            // Resaltar nueva fila
            newRow.style.backgroundColor = '#e6f3ff';
            setTimeout(() => {
                newRow.style.backgroundColor = '';
            }, 3000);
        }

        // Función para generar pedido aleatorio
        function generateRandomOrder() {
            const clientes = ['Laura Pérez', 'Diego Ruiz', 'Carmen Vega', 'Roberto Silva', 'Patricia Morales'];
            const totales = ['$8.50', '$15.75', '$22.00', '$12.25', '$18.90'];
            const ids = ['#' + String(Math.floor(Math.random() * 1000)).padStart(3, '0')];
            
            const now = new Date();
            const hora = now.getHours().toString().padStart(2, '0') + ':' + now.getMinutes().toString().padStart(2, '0');
            
            return {
                id: ids[0],
                cliente: clientes[Math.floor(Math.random() * clientes.length)],
                total: totales[Math.floor(Math.random() * totales.length)],
                hora: hora
            };
        }

        // Funciones de utilidad para efectos visuales
        function addHoverEffects() {
            const cards = document.querySelectorAll('.metric-card, .chart-card, .table-card');
            
            cards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-5px)';
                    this.style.boxShadow = '0 15px 40px rgba(0, 0, 0, 0.15)';
                });
                
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                    this.style.boxShadow = '0 8px 32px rgba(0, 0, 0, 0.1)';
                });
            });
        }

        // Función para manejar responsive
        function handleResponsive() {
            const sidebar = document.querySelector('.sidebar');
            const mainContent = document.querySelector('.main-content');
            const menuToggle = document.createElement('button');
            
            menuToggle.innerHTML = '<i class="fas fa-bars"></i>';
            menuToggle.className = 'menu-toggle';
            menuToggle.style.cssText = `
                position: fixed;
                top: 20px;
                left: 20px;
                z-index: 1001;
                background: #8B4513;
                color: white;
                border: none;
                padding: 12px;
                border-radius: 8px;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
                display: none;
                cursor: pointer;
                font-size: 18px;
            `;
            
            document.body.appendChild(menuToggle);
            
            function toggleSidebar() {
                sidebar.classList.toggle('active');
                menuToggle.classList.toggle('active');
                
                if (sidebar.classList.contains('active')) {
                    document.body.style.overflow = 'hidden';
                } else {
                    document.body.style.overflow = '';
                }
            }
            
            function checkScreenSize() {
                if (window.innerWidth <= 768) {
                    menuToggle.style.display = 'block';
                    mainContent.style.marginLeft = '0';
                } else {
                    menuToggle.style.display = 'none';
                    sidebar.classList.remove('active');
                    mainContent.style.marginLeft = '250px';
                    document.body.style.overflow = '';
                }
            }
            
            menuToggle.addEventListener('click', toggleSidebar);
            
            // Cerrar sidebar al hacer click fuera
            document.addEventListener('click', (e) => {
                if (window.innerWidth <= 768 && 
                    !sidebar.contains(e.target) && 
                    !menuToggle.contains(e.target) &&
                    sidebar.classList.contains('active')) {
                    toggleSidebar();
                }
            });
            
            // Actualizar en tiempo real al cambiar el tamaño de la ventana
            let resizeTimeout;
            window.addEventListener('resize', () => {
                clearTimeout(resizeTimeout);
                resizeTimeout = setTimeout(checkScreenSize, 250);
            });
            
            checkScreenSize();
        }

        // Función para notificaciones
        function showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className = `notification notification-${type}`;
            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: ${type === 'success' ? '#10b981' : type === 'error' ? '#ef4444' : '#3b82f6'};
                color: white;
                padding: 15px 20px;
                border-radius: 10px;
                box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
                z-index: 1000;
                transform: translateX(400px);
                transition: transform 0.3s ease;
                max-width: 300px;
                font-weight: 500;
            `;
            notification.textContent = message;
            
            document.body.appendChild(notification);
            
            // Animar entrada
            setTimeout(() => {
                notification.style.transform = 'translateX(0)';
            }, 100);
            
            // Remover después de 3 segundos
            setTimeout(() => {
                notification.style.transform = 'translateX(400px)';
                setTimeout(() => {
                    document.body.removeChild(notification);
                }, 300);
            }, 3000);
        }

        // Función para exportar datos
        function exportData(type) {
            const data = {
                ventas: dashboardData.ventasSemanales,
                productos: dashboardData.productosPopulares,
                fecha: new Date().toISOString().split('T')[0]
            };
            
            const jsonData = JSON.stringify(data, null, 2);
            const blob = new Blob([jsonData], { type: 'application/json' });
            const url = URL.createObjectURL(blob);
            
            const a = document.createElement('a');
            a.href = url;
            a.download = `cafeteria_data_${data.fecha}.json`;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
            
            showNotification('Datos exportados exitosamente', 'success');
        }

        // Función para buscar en tablas
        function addSearchFunctionality() {
            const searchInput = document.createElement('input');
            searchInput.type = 'text';
            searchInput.placeholder = 'Buscar en pedidos...';
            searchInput.style.cssText = `
                width: 100%;
                padding: 10px;
                margin-bottom: 15px;
                border: 1px solid #e5e7eb;
                border-radius: 8px;
                font-size: 14px;
            `;
            
            const pedidosCard = document.querySelector('.table-section .table-card:last-child');
            const tableTitle = pedidosCard.querySelector('.table-title');
            tableTitle.after(searchInput);
            
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                const rows = document.querySelectorAll('#pedidosTable tr');
                
                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    row.style.display = text.includes(searchTerm) ? '' : 'none';
                });
            });
        }

        // Función para modo oscuro
        function initializeDarkMode() {
            const darkModeToggle = document.createElement('button');
            darkModeToggle.innerHTML = '<i class="fas fa-moon"></i>';
            darkModeToggle.className = 'dark-mode-toggle';
            darkModeToggle.style.cssText = `
                position: fixed;
                bottom: 20px;
                right: 20px;
                background: #8B4513;
                color: white;
                border: none;
                width: 50px;
                height: 50px;
                border-radius: 50%;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
                cursor: pointer;
                font-size: 18px;
                transition: all 0.3s ease;
                z-index: 1000;
            `;
            
            document.body.appendChild(darkModeToggle);
            
            darkModeToggle.addEventListener('click', function() {
                document.body.classList.toggle('dark-mode');
                const isDark = document.body.classList.contains('dark-mode');
                this.innerHTML = isDark ? '<i class="fas fa-sun"></i>' : '<i class="fas fa-moon"></i>';
                
                // Guardar preferencia
                localStorage.setItem('darkMode', isDark);
            });
            
            // Cargar preferencia guardada
            const savedDarkMode = localStorage.getItem('darkMode') === 'true';
            if (savedDarkMode) {
                document.body.classList.add('dark-mode');
                darkModeToggle.innerHTML = '<i class="fas fa-sun"></i>';
            }
        }

        // Función para inicializar tooltips
        function initializeTooltips() {
            const tooltipElements = document.querySelectorAll('[data-tooltip]');
            
            tooltipElements.forEach(element => {
                element.addEventListener('mouseenter', function(e) {
                    const tooltip = document.createElement('div');
                    tooltip.className = 'tooltip';
                    tooltip.textContent = this.getAttribute('data-tooltip');
                    tooltip.style.cssText = `
                        position: absolute;
                        background: rgba(0, 0, 0, 0.8);
                        color: white;
                        padding: 8px 12px;
                        border-radius: 6px;
                        font-size: 12px;
                        white-space: nowrap;
                        z-index: 1000;
                        pointer-events: none;
                        opacity: 0;
                        transition: opacity 0.3s ease;
                    `;
                    
                    document.body.appendChild(tooltip);
                    
                    const rect = this.getBoundingClientRect();
                    tooltip.style.left = rect.left + (rect.width / 2) - (tooltip.offsetWidth / 2) + 'px';
                    tooltip.style.top = rect.top - tooltip.offsetHeight - 10 + 'px';
                    
                    setTimeout(() => tooltip.style.opacity = '1', 10);
                    
                    this._tooltip = tooltip;
                });
                
                element.addEventListener('mouseleave', function() {
                    if (this._tooltip) {
                        document.body.removeChild(this._tooltip);
                        this._tooltip = null;
                    }
                });
            });
        }

        // Función para estadísticas avanzadas
        function calculateAdvancedStats() {
            const ventas = dashboardData.ventasSemanales;
            const total = ventas.reduce((sum, item) => sum + item.ventas, 0);
            const promedio = total / ventas.length;
            const maximo = Math.max(...ventas.map(item => item.ventas));
            const minimo = Math.min(...ventas.map(item => item.ventas));
            
            return {
                total: total.toFixed(2),
                promedio: promedio.toFixed(2),
                maximo: maximo.toFixed(2),
                minimo: minimo.toFixed(2),
                crecimiento: ((ventas[ventas.length - 1].ventas - ventas[0].ventas) / ventas[0].ventas * 100).toFixed(1)
            };
        }

        // Inicializar todas las funciones al cargar la página
        document.addEventListener('DOMContentLoaded', function() {
            initializeDateTime();
            initializeCharts();
            initializeNavigation();
            initializeRealTimeUpdates();
            addHoverEffects();
            handleResponsive();
            addSearchFunctionality();
            initializeDarkMode();
            initializeTooltips();
            
            // Mostrar notificación de bienvenida
            setTimeout(() => {
                showNotification('¡Dashboard cargado exitosamente!', 'success');
            }, 1000);
            
            // Calcular estadísticas avanzadas
            const stats = calculateAdvancedStats();
            console.log('Estadísticas de la semana:', stats);
        });

        // Función para refrescar datos
        function refreshData() {
            const refreshBtn = document.createElement('button');
            refreshBtn.innerHTML = '<i class="fas fa-sync-alt"></i>';
            refreshBtn.className = 'refresh-btn';
            refreshBtn.style.cssText = `
                position: fixed;
                bottom: 80px;
                right: 20px;
                background: #10b981;
                color: white;
                border: none;
                width: 50px;
                height: 50px;
                border-radius: 50%;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
                cursor: pointer;
                font-size: 16px;
                transition: all 0.3s ease;
                z-index: 1000;
            `;
            
            document.body.appendChild(refreshBtn);
            
            refreshBtn.addEventListener('click', function() {
                this.style.transform = 'rotate(360deg)';
                updateMetrics();
                updateTables();
                showNotification('Datos actualizados', 'success');
                
                setTimeout(() => {
                    this.style.transform = 'rotate(0deg)';
                }, 300);
            });
        }

        // Llamar función de refresh
        setTimeout(refreshData, 2000);

        // Agregar evento de redimensionamiento para las gráficas
        window.addEventListener('resize', function() {
            // Usar debounce para evitar demasiadas actualizaciones
            clearTimeout(window.resizeTimeout);
            window.resizeTimeout = setTimeout(function() {
                initializeCharts();
            }, 250);
        });