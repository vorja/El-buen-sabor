
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mesas - Café Manager</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        * {
            font-family: 'Inter', sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        
        .glass-effect {
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.18);
        }
        
        .card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 25px 45px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 35px 65px rgba(0, 0, 0, 0.15);
        }
        
        .mesa {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            margin: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        
        .mesa::before {
            content: '';
            position: absolute;
            inset: -3px;
            border-radius: 50%;
            background: linear-gradient(45deg, #ff6b6b, #4ecdc4, #45b7d1, #96ceb4);
            z-index: -1;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .mesa:hover::before {
            opacity: 1;
        }
        
        .mesa:hover {
            transform: scale(1.1) rotate(5deg);
        }
        
        .mesa.disponible {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            box-shadow: 0 15px 35px rgba(102, 126, 234, 0.4);
        }
        
        .mesa.ocupada {
            background: linear-gradient(135deg, #ff6b6b, #ee5a6f);
            color: white;
            box-shadow: 0 15px 35px rgba(255, 107, 107, 0.4);
        }
        
        .mesa.reservada {
            background: linear-gradient(135deg, #feca57, #ff9ff3);
            color: white;
            box-shadow: 0 15px 35px rgba(254, 202, 87, 0.4);
        }
        
        .mesa.mantenimiento {
            background: linear-gradient(135deg, #a4b0be, #747d8c);
            color: white;
            box-shadow: 0 15px 35px rgba(164, 176, 190, 0.4);
        }
        
        .pulse {
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        
        .floating {
            animation: floating 3s ease-in-out infinite;
        }
        
        @keyframes floating {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        
        .glow {
            animation: glow 2s ease-in-out infinite alternate;
        }
        
        @keyframes glow {
            from { box-shadow: 0 0 20px rgba(102, 126, 234, 0.5); }
            to { box-shadow: 0 0 30px rgba(102, 126, 234, 0.8); }
        }
        
        .nav-glass {
            background: rgba(26, 54, 93, 0.9);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .btn-modern {
            background: linear-gradient(135deg, #667eea, #764ba2);
            border: none;
            color: white;
            padding: 12px 24px;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        }
        
        .btn-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 40px rgba(102, 126, 234, 0.4);
        }
        
        .stat-card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.9), rgba(255, 255, 255, 0.7));
            border-radius: 20px;
            padding: 20px;
            text-align: center;
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }
        
        .control-panel {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(255, 255, 255, 0.85));
            border-radius: 25px;
            padding: 30px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.1);
        }
        
        .floor-plan {
            background: radial-gradient(circle at center, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.05));
            border-radius: 25px;
            padding: 40px;
            min-height: 500px;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
            gap: 20px;
        }
        
        .section-title {
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 700;
            font-size: 2rem;
            margin-bottom: 20px;
        }
        
        .legend-item {
            display: flex;
            align-items: center;
            padding: 12px;
            border-radius: 12px;
            margin-bottom: 10px;
            background: rgba(255, 255, 255, 0.5);
            transition: all 0.3s ease;
        }
        
        .legend-item:hover {
            background: rgba(255, 255, 255, 0.8);
            transform: translateX(5px);
        }
        
        .status-indicator {
            width: 16px;
            height: 16px;
            border-radius: 50%;
            margin-right: 12px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }
        
        .form-control {
            background: rgba(255, 255, 255, 0.9);
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 12px;
            padding: 12px 16px;
            transition: all 0.3s ease;
            width: 100%;
            margin-bottom: 15px;
        }
        
        .form-control:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
    </style>
</head>
<body class="min-h-screen">
    <nav class="nav-glass fixed w-full z-50 top-0">
        <div class="container mx-auto px-6">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center">
                    <div class="bg-gradient-to-r from-yellow-400 to-orange-500 p-3 rounded-full mr-3">
                        <i class="fas fa-coffee text-white text-xl"></i>
                    </div>
                    <div>
                        <span class="font-bold text-xl text-white">Café Manager</span>
                        <div class="text-xs text-blue-200">Sistema de Gestión</div>
                    </div>
                </div>
                <div class="hidden md:flex items-center space-x-6">
                    <a href="#" class="text-white hover:text-blue-200 transition-colors font-medium">
                        <i class="fas fa-boxes mr-2"></i>Inventario
                    </a>
                    <a href="#" class="text-white hover:text-blue-200 transition-colors font-medium">
                        <i class="fas fa-users mr-2"></i>Empleados
                    </a>
                    <a href="#" class="text-blue-200 font-semibold">
                        <i class="fas fa-chair mr-2"></i>Mesas
                    </a>
                    <a href="#" class="text-white hover:text-blue-200 transition-colors font-medium">
                        <i class="fas fa-cash-register mr-2"></i>Ventas
                    </a>
                    <a href="#" class="text-white hover:text-blue-200 transition-colors font-medium">
                        <i class="fas fa-chart-line mr-2"></i>Reportes
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="pt-24 pb-12 px-6">
        <div class="container mx-auto">
            <div class="text-center mb-12">
                <h1 class="section-title">Gestión de Mesas</h1>
                <p class="text-white/80 text-lg">Control y monitoreo en tiempo real</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">
                <div class="stat-card floating">
                    <div class="text-3xl font-bold text-blue-600 mb-2" id="total-mesas">24</div>
                    <div class="text-gray-600 font-medium">Total Mesas</div>
                </div>
                <div class="stat-card floating" style="animation-delay: 0.1s">
                    <div class="text-3xl font-bold text-green-500 mb-2" id="disponible-count">18</div>
                    <div class="text-gray-600 font-medium">Disponibles</div>
                </div>
                <div class="stat-card floating" style="animation-delay: 0.2s">
                    <div class="text-3xl font-bold text-red-500 mb-2" id="ocupada-count">4</div>
                    <div class="text-gray-600 font-medium">Ocupadas</div>
                </div>
                <div class="stat-card floating" style="animation-delay: 0.3s">
                    <div class="text-3xl font-bold text-yellow-500 mb-2" id="reservada-count">2</div>
                    <div class="text-gray-600 font-medium">Reservadas</div>
                </div>
            </div>

+            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2">
                    <div class="card rounded-3xl overflow-hidden">
                        <div class="p-6 border-b border-gray-100">
                            <div class="flex justify-between items-center">
                                <h3 class="text-xl font-bold text-gray-800">
                                    <i class="fas fa-map mr-2 text-blue-500"></i>
                                    Plano del Restaurante
                                </h3>
                                <div class="flex space-x-2">
                                    <button class="btn-modern text-sm">
                                        <i class="fas fa-expand-arrows-alt mr-1"></i>
                                        Vista Completa
                                    </button>
                                    <button class="btn-modern text-sm bg-gradient-to-r from-green-400 to-blue-500">
                                        <i class="fas fa-sync-alt mr-1"></i>
                                        Actualizar
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="floor-plan" id="mesas-container">
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="card rounded-3xl overflow-hidden">
                        <div class="p-6 border-b border-gray-100">
                            <h3 class="text-lg font-bold text-gray-800">
                                <i class="fas fa-info-circle mr-2 text-blue-500"></i>
                                Leyenda
                            </h3>
                        </div>
                        <div class="p-6 space-y-3">
                            <div class="legend-item">
                                <div class="status-indicator bg-gradient-to-r from-blue-500 to-purple-600"></div>
                                <span class="font-medium">Disponible</span>
                            </div>
                            <div class="legend-item">
                                <div class="status-indicator bg-gradient-to-r from-red-500 to-pink-500"></div>
                                <span class="font-medium">Ocupada</span>
                            </div>
                            <div class="legend-item">
                                <div class="status-indicator bg-gradient-to-r from-yellow-400 to-pink-400"></div>
                                <span class="font-medium">Reservada</span>
                            </div>
                            <div class="legend-item">
                                <div class="status-indicator bg-gradient-to-r from-gray-400 to-gray-600"></div>
                                <span class="font-medium">Mantenimiento</span>
                            </div>
                        </div>
                    </div>

                    <div class="card rounded-3xl overflow-hidden">
                        <div class="p-6 border-b border-gray-100">
                            <h3 class="text-lg font-bold text-gray-800">
                                <i class="fas fa-cogs mr-2 text-blue-500"></i>
                                Control de Estado
                            </h3>
                        </div>
                        <div class="p-6">
                            <select id="mesa-selector" class="form-control">
                                <option value="">Seleccionar mesa...</option>
                            </select>
                            <select id="estado-selector" class="form-control">
                                <option value="disponible">Disponible</option>
                                <option value="ocupada">Ocupada</option>
                                <option value="reservada">Reservada</option>
                                <option value="mantenimiento">Mantenimiento</option>
                            </select>
                            <button id="cambiar-estado-btn" class="btn-modern w-full">
                                <i class="fas fa-check mr-2"></i>
                                Cambiar Estado
                            </button>
                        </div>
                    </div>

                    <div class="card rounded-3xl overflow-hidden">
                        <div class="p-6 border-b border-gray-100">
                            <h3 class="text-lg font-bold text-gray-800">
                                <i class="fas fa-bolt mr-2 text-blue-500"></i>
                                Acciones Rápidas
                            </h3>
                        </div>
                        <div class="p-6 space-y-3">
                            <button class="btn-modern w-full bg-gradient-to-r from-green-400 to-blue-500">
                                <i class="fas fa-calendar-plus mr-2"></i>
                                Nueva Reserva
                            </button>
                            <button class="btn-modern w-full bg-gradient-to-r from-purple-400 to-pink-500">
                                <i class="fas fa-users mr-2"></i>
                                Asignar Mesero
                            </button>
                            <button class="btn-modern w-full bg-gradient-to-r from-orange-400 to-red-500">
                                <i class="fas fa-tools mr-2"></i>
                                Mantenimiento
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const mesas = [
            { id: 1, numero: 1, estado: 'disponible', capacidad: 4 },
            { id: 2, numero: 2, estado: 'ocupada', capacidad: 2 },
            { id: 3, numero: 3, estado: 'disponible', capacidad: 6 },
            { id: 4, numero: 4, estado: 'reservada', capacidad: 4 },
            { id: 5, numero: 5, estado: 'disponible', capacidad: 2 },
            { id: 6, numero: 6, estado: 'ocupada', capacidad: 4 },
            { id: 7, numero: 7, estado: 'disponible', capacidad: 8 },
            { id: 8, numero: 8, estado: 'mantenimiento', capacidad: 4 },
            { id: 9, numero: 9, estado: 'disponible', capacidad: 2 },
            { id: 10, numero: 10, estado: 'ocupada', capacidad: 6 },
            { id: 11, numero: 11, estado: 'disponible', capacidad: 4 },
            { id: 12, numero: 12, estado: 'reservada', capacidad: 2 },
            { id: 13, numero: 13, estado: 'disponible', capacidad: 4 },
            { id: 14, numero: 14, estado: 'disponible', capacidad: 4 },
            { id: 15, numero: 15, estado: 'disponible', capacidad: 2 },
            { id: 16, numero: 16, estado: 'disponible', capacidad: 6 },
            { id: 17, numero: 17, estado: 'disponible', capacidad: 4 },
            { id: 18, numero: 18, estado: 'ocupada', capacidad: 2 },
            { id: 19, numero: 19, estado: 'disponible', capacidad: 4 },
            { id: 20, numero: 20, estado: 'disponible', capacidad: 8 },
            { id: 21, numero: 21, estado: 'disponible', capacidad: 4 },
            { id: 22, numero: 22, estado: 'disponible', capacidad: 2 },
            { id: 23, numero: 23, Estado: 'disponible', capacidad: 4 },
            { id: 24, numero: 24, estado: 'disponible', capacidad: 6 }
        ];

        function renderMesas() {
            const container = document.getElementById('mesas-container');
            const selector = document.getElementById('mesa-selector');
            
            container.innerHTML = '';
            selector.innerHTML = '<option value="">Seleccionar mesa...</option>';
            
            mesas.forEach(mesa => {
                const mesaElement = document.createElement('div');
                mesaElement.className = `mesa ${mesa.estado}`;
                mesaElement.innerHTML = `
                    <div class="text-center">
                        <div class="font-bold text-lg">${mesa.numero}</div>
                        <div class="text-xs opacity-80">${mesa.capacidad}pers</div>
                    </div>
                `;
                
                if (mesa.estado === 'ocupada') {
                    mesaElement.classList.add('pulse');
                } else if (mesa.estado === 'disponible') {
                    mesaElement.classList.add('glow');
                }
                
                mesaElement.addEventListener('click', () => {
                    selectMesa(mesa.id);
                });
                
                container.appendChild(mesaElement);
                
                const option = document.createElement('option');
                option.value = mesa.id;
                option.textContent = `Mesa ${mesa.numero} (${mesa.capacidad} personas)`;
                selector.appendChild(option);
            });
        }

        function selectMesa(mesaId) {
            document.getElementById('mesa-selector').value = mesaId;
            
            document.querySelectorAll('.mesa').forEach(el => {
                el.style.transform = '';
                el.style.boxShadow = '';
            });
            
            const selectedMesa = document.querySelector(`.mesa:nth-child(${mesaId})`);
            if (selectedMesa) {
                selectedMesa.style.transform = 'scale(1.2)';
                selectedMesa.style.boxShadow = '0 0 30px rgba(102, 126, 234, 0.8)';
            }
        }

        function updateStats() {
            const stats = mesas.reduce((acc, mesa) => {
                acc[mesa.estado] = (acc[mesa.estado] || 0) + 1;
                return acc;
            }, {});
            
            document.getElementById('total-mesas').textContent = mesas.length;
            document.getElementById('disponible-count').textContent = stats.disponible || 0;
            document.getElementById('ocupada-count').textContent = stats.ocupada || 0;
            document.getElementById('reservada-count').textContent = stats.reservada || 0;
        }

        function cambiarEstado() {
            const mesaId = document.getElementById('mesa-selector').value;
            const nuevoEstado = document.getElementById('estado-selector').value;
            
            if (!mesaId || !nuevoEstado) {
                alert('Por favor selecciona una mesa y un estado');
                return;
            }
            
            const mesa = mesas.find(m => m.id == mesaId);
            if (mesa) {
                mesa.estado = nuevoEstado;
                renderMesas();
                updateStats();
                
                const btn = document.getElementById('cambiar-estado-btn');
                btn.innerHTML = '<i class="fas fa-check mr-2"></i>¡Actualizado!';
                btn.style.background = 'linear-gradient(135deg, #10b981, #059669)';
                
                setTimeout(() => {
                    btn.innerHTML = '<i class="fas fa-check mr-2"></i>Cambiar Estado';
                    btn.style.background = 'linear-gradient(135deg, #667eea, #764ba2)';
                }, 2000);
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            renderMesas();
            updateStats();
            
            document.getElementById('cambiar-estado-btn').addEventListener('click', cambiarEstado);
            
            setInterval(() => {
                updateStats();
            }, 30000);
        });

        setInterval(() => {
            const disponibles = document.querySelectorAll('.mesa.disponible');
            disponibles.forEach((mesa, index) => {
                setTimeout(() => {
                    mesa.style.transform = 'scale(1.05)';
                    setTimeout(() => {
                        mesa.style.transform = '';
                    }, 200);
                }, index * 100);
            });
        }, 5000);
    </script>
</body>
</html>