    // Actualizar fecha y hora
        function updateDateTime() {
            const now = new Date();
            const options = { 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric', 
                hour: '2-digit', 
                minute: '2-digit' 
            };
            document.getElementById('dateTime').textContent = now.toLocaleDateString('es-ES', options);
        }

        updateDateTime();
        setInterval(updateDateTime, 60000);

        // Gráfico de evolución de ventas
        const ctxEvolucion = document.getElementById('ventasEvolucionChart').getContext('2d');
        new Chart(ctxEvolucion, {
            type: 'line',
            data: {
                labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun'],
                datasets: [{
                    label: 'Ventas ($)',
                    data: [8500, 9200, 11300, 10800, 13400, 15284],
                    borderColor: '#e67e22',
                    backgroundColor: 'rgba(230, 126, 34, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#e67e22',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '$' + value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });

        // Gráfico de ventas por categoría
        const ctxCategoria = document.getElementById('ventasCategoriaChart').getContext('2d');
        new Chart(ctxCategoria, {
            type: 'doughnut',
            data: {
                labels: ['Bebidas Calientes', 'Bebidas Frías', 'Pasteles', 'Sandwiches', 'Otros'],
                datasets: [{
                    data: [45, 20, 15, 12, 8],
                    backgroundColor: [
                        '#e67e22',
                        '#3498db',
                        '#2ecc71',
                        '#f39c12',
                        '#9b59b6'
                    ],
                    borderWidth: 0,
                    hoverOffset: 10
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true
                        }
                    }
                }
            }
        });

        // Funciones de filtrado y búsqueda (simuladas)
        document.getElementById('filtroEstado').addEventListener('change', function() {
            filtrarVentas();
        });

        document.getElementById('filtroProducto').addEventListener('change', function() {
            filtrarVentas();
        });

        document.getElementById('buscarVenta').addEventListener('input', function() {
            buscarVentas(this.value);
        });

        function filtrarVentas() {
            console.log('Filtrando ventas...');
            // Aquí iría la lógica de filtrado real
        }

        function buscarVentas(termino) {
            console.log('Buscando:', termino);
            // Aquí iría la lógica de búsqueda real
        }

        function exportarVentas() {
            alert('Exportando datos de ventas...');
            // Aquí iría la lógica de exportación real
        }

        function previousPage() {
            console.log('Página anterior');
        }

        function nextPage() {
            console.log('Página siguiente');
        }

        // Navegación del sidebar (simulada)
        document.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                document.querySelectorAll('.nav-link').forEach(l => l.classList.remove('active'));
                this.classList.add('active');
            });
        });