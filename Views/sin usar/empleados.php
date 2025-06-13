<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Empleados - Café Manager</title>
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
        
        .employee-card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.9), rgba(255, 255, 255, 0.7));
            border-radius: 20px;
            padding: 25px;
            text-align: center;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid rgba(255, 255, 255, 0.3);
            position: relative;
            overflow: hidden;
        }
        
        .employee-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            transition: left 0.6s;
        }
        
        .employee-card:hover::before {
            left: 100%;
        }
        
        .employee-card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
        }
        
        .avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin: 0 auto 15px;
            position: relative;
            overflow: hidden;
            border: 4px solid transparent;
            background: linear-gradient(45deg, #667eea, #764ba2) padding-box,
                        linear-gradient(45deg, #667eea, #764ba2) border-box;
            transition: all 0.3s ease;
        }
        
        .avatar:hover {
            transform: scale(1.1) rotate(5deg);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }
        
        .avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
        }
        
        .status-badge {
            position: absolute;
            bottom: 5px;
            right: 5px;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            border: 3px solid white;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }
        
        .status-activo { background: linear-gradient(135deg, #10b981, #059669); }
        .status-descanso { background: linear-gradient(135deg, #f59e0b, #d97706); }
        .status-inactivo { background: linear-gradient(135deg, #ef4444, #dc2626); }
        
        .role-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 10px;
        }
        
        .role-barista { 
            background: linear-gradient(135deg, #8b5cf6, #a78bfa);
            color: white;
        }
        .role-mesero { 
            background: linear-gradient(135deg, #06b6d4, #67e8f9);
            color: white;
        }
        .role-cocinero { 
            background: linear-gradient(135deg, #f59e0b, #fbbf24);
            color: white;
        }
        .role-gerente { 
            background: linear-gradient(135deg, #ef4444, #f87171);
            color: white;
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
            padding: 25px;
            text-align: center;
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }
        
        .floating {
            animation: floating 3s ease-in-out infinite;
        }
        
        @keyframes floating {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
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
        
        .search-container {
            position: relative;
            max-width: 400px;
        }
        
        .search-input {
            background: rgba(255, 255, 255, 0.9);
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 15px;
            padding: 12px 45px 12px 16px;
            width: 100%;
            transition: all 0.3s ease;
        }
        
        .search-input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            background: rgba(255, 255, 255, 1);
        }
        
        .search-icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #667eea;
        }
        
        .filter-select {
            background: rgba(255, 255, 255, 0.9);
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 12px;
            padding: 10px 15px;
            transition: all 0.3s ease;
        }
        
        .filter-select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        .pulse {
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }
        
        .performance-bar {
            width: 100%;
            height: 8px;
            background: rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            overflow: hidden;
            margin-top: 10px;
        }
        
        .performance-fill {
            height: 100%;
            background: linear-gradient(90deg, #667eea, #764ba2);
            border-radius: 10px;
            transition: width 0.6s ease;
        }
        
        .grid-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 25px;
            padding: 30px;
        }
        
        @media (max-width: 768px) {
            .grid-container {
                grid-template-columns: 1fr;
                padding: 20px;
            }
        }
    </style>
</head>
<body class="min-h-screen">
    <!-- Navigation -->
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
                    <a href="#" class="text-blue-200 font-semibold">
                        <i class="fas fa-users mr-2"></i>Empleados
                    </a>
                    <a href="#" class="text-white hover:text-blue-200 transition-colors font-medium">
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

    <!-- Main Content -->
    <div class="pt-24 pb-12 px-6">
        <div class="container mx-auto">
            <!-- Header -->
            <div class="text-center mb-12">
                <h1 class="section-title">Gestión de Empleados</h1>
                <p class="text-white/80 text-lg">Equipo de trabajo y rendimiento</p>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">
                <div class="stat-card floating">
                    <div class="text-3xl font-bold text-blue-600 mb-2" id="total-empleados">15</div>
                    <div class="text-gray-600 font-medium">Total Empleados</div>
                </div>
                <div class="stat-card floating" style="animation-delay: 0.1s">
                    <div class="text-3xl font-bold text-green-500 mb-2" id="activos-count">12</div>
                    <div class="text-gray-600 font-medium">Activos</div>
                </div>
                <div class="stat-card floating" style="animation-delay: 0.2s">
                    <div class="text-3xl font-bold text-yellow-500 mb-2" id="descanso-count">2</div>
                    <div class="text-gray-600 font-medium">En Descanso</div>
                </div>
                <div class="stat-card floating" style="animation-delay: 0.3s">
                    <div class="text-3xl font-bold text-red-500 mb-2" id="inactivos-count">1</div>
                    <div class="text-gray-600 font-medium">Inactivos</div>
                </div>
            </div>

            <!-- Control Panel -->
            <div class="card rounded-3xl mb-8 overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <div class="flex flex-col lg:flex-row justify-between items-center gap-6">
                        <div class="search-container">
                            <input type="text" placeholder="Buscar empleado..." class="search-input" id="search-input">
                            <i class="fas fa-search search-icon"></i>
                        </div>
                        
                        <div class="flex flex-wrap gap-4 items-center">
                            <select class="filter-select" id="role-filter">
                                <option value="">Todos los roles</option>
                                <option value="barista">Barista</option>
                                <option value="mesero">Mesero</option>
                                <option value="cocinero">Cocinero</option>
                                <option value="gerente">Gerente</option>
                            </select>
                            
                            <select class="filter-select" id="status-filter">
                                <option value="">Todos los estados</option>
                                <option value="activo">Activo</option>
                                <option value="descanso">En Descanso</option>
                                <option value="inactivo">Inactivo</option>
                            </select>
                            
                            <button class="btn-modern">
                                <i class="fas fa-user-plus mr-2"></i>
                                Nuevo Empleado
                            </button>
                            
                            <button class="btn-modern bg-gradient-to-r from-green-400 to-blue-500">
                                <i class="fas fa-calendar-alt mr-2"></i>
                                Horarios
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Employees Grid -->
            <div class="card rounded-3xl overflow-hidden">
                <div class="grid-container" id="empleados-grid">
                    <!-- Employee cards will be populated here -->
                </div>
            </div>
        </div>
    </div>

    <script>
        // Sample employee data
        const empleados = [
            {
                id: 1,
                nombre: "Ana García",
                apellido: "López",
                rol: "barista",
                estado: "activo",
                telefono: "+57 300 123 4567",
                email: "ana.garcia@cafemanager.com",
                fechaIngreso: "2023-01-15",
                rendimiento: 92,
                avatar: "https://images.unsplash.com/photo-1494790108755-2616b612b647?w=150&h=150&fit=crop&crop=face"
            },
            {
                id: 2,
                nombre: "Carlos",
                apellido: "Rodríguez",
                rol: "mesero",
                estado: "activo",
                telefono: "+57 301 234 5678",
                email: "carlos.rodriguez@cafemanager.com",
                fechaIngreso: "2023-02-01",
                rendimiento: 88,
                avatar: "https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=150&h=150&fit=crop&crop=face"
            },
            {
                id: 3,
                nombre: "María",
                apellido: "González",
                rol: "cocinero",
                estado: "activo",
                telefono: "+57 302 345 6789",
                email: "maria.gonzalez@cafemanager.com",
                fechaIngreso: "2023-01-20",
                rendimiento: 95,
                avatar: "https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=150&h=150&fit=crop&crop=face"
            },
            {
                id: 4,
                nombre: "Luis",
                apellido: "Martínez",
                rol: "gerente",
                estado: "activo",
                telefono: "+57 303 456 7890",
                email: "luis.martinez@cafemanager.com",
                fechaIngreso: "2022-11-10",
                rendimiento: 98,
                avatar: "https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=150&h=150&fit=crop&crop=face"
            },
            {
                id: 5,
                nombre: "Sofia",
                apellido: "Torres",
                rol: "barista",
                estado: "descanso",
                telefono: "+57 304 567 8901",
                email: "sofia.torres@cafemanager.com",
                fechaIngreso: "2023-03-05",
                rendimiento: 85,
                avatar: "https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=150&h=150&fit=crop&crop=face"
            },
            {
                id: 6,
                nombre: "Diego",
                apellido: "Hernández",
                rol: "mesero",
                estado: "activo",
                telefono: "+57 305 678 9012",
                email: "diego.hernandez@cafemanager.com",
                fechaIngreso: "2023-02-15",
                rendimiento: 90,
                avatar: "https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=150&h=150&fit=crop&crop=face"
            },
            {
                id: 7,
                nombre: "Valentina",
                apellido: "Jiménez",
                rol: "barista",
                estado: "activo",
                telefono: "+57 306 789 0123",
                email: "valentina.jimenez@cafemanager.com",
                fechaIngreso: "2023-04-01",
                rendimiento: 87,
                avatar: "https://images.unsplash.com/photo-1544005313-94ddf0286df2?w=150&h=150&fit=crop&crop=face"
            },
            {
                id: 8,
                nombre: "Andrés",
                apellido: "Vargas",
                rol: "cocinero",
                estado: "activo",
                telefono: "+57 307 890 1234",
                email: "andres.vargas@cafemanager.com",
                fechaIngreso: "2023-01-30",
                rendimiento: 93,
                avatar: "https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?w=150&h=150&fit=crop&crop=face"
            },
            {
                id: 9,
                nombre: "Isabella",
                apellido: "Ramírez",
                rol: "mesero",
                estado: "descanso",
                telefono: "+57 308 901 2345",
                email: "isabella.ramirez@cafemanager.com",
                fechaIngreso: "2023-03-10",
                rendimiento: 89,
                avatar: "https://images.unsplash.com/photo-1517841905240-472988babdf9?w=150&h=150&fit=crop&crop=face"
            },
            {
                id: 10,
                nombre: "Santiago",
                apellido: "Cruz",
                rol: "barista",
                estado: "activo",
                telefono: "+57 309 012 3456",
                email: "santiago.cruz@cafemanager.com",
                fechaIngreso: "2023-04-15",
                rendimiento: 91,
                avatar: "https://images.unsplash.com/photo-1519345182560-3f2917c472ef?w=150&h=150&fit=crop&crop=face"
            },
            {
                id: 11,
                nombre: "Camila",
                apellido: "Morales",
                rol: "mesero",
                estado: "activo",
                telefono: "+57 310 123 4567",
                email: "camila.morales@cafemanager.com",
                fechaIngreso: "2023-02-28",
                rendimiento: 86,
                avatar: "https://images.unsplash.com/photo-1521119989659-a83eee488004?w=150&h=150&fit=crop&crop=face"
            },
            {
                id: 12,
                nombre: "Felipe",
                apellido: "Castillo",
                rol: "cocinero",
                estado: "inactivo",
                telefono: "+57 311 234 5678",
                email: "felipe.castillo@cafemanager.com",
                fechaIngreso: "2023-01-05",
                rendimiento: 75,
                avatar: "https://images.unsplash.com/photo-1531427186611-ecfd6d936c79?w=150&h=150&fit=crop&crop=face"
            }
        ];

        let filteredEmpleados = [...empleados];

        function renderEmpleados(empleadosArray = filteredEmpleados) {
            const container = document.getElementById('empleados-grid');
            
            container.innerHTML = empleadosArray.map(empleado => `
                <div class="employee-card" data-id="${empleado.id}">
                    <div class="avatar">
                        <img src="${empleado.avatar}" alt="${empleado.nombre}" onerror="this.src='https://via.placeholder.com/80/667eea/ffffff?text=${empleado.nombre.charAt(0)}'">
                        <div class="status-badge status-${empleado.estado}"></div>
                    </div>
                    
                    <div class="role-badge role-${empleado.rol}">
                        ${empleado.rol}
                    </div>
                    
                    <h3 class="font-bold text-lg text-gray-800 mb-1">
                        ${empleado.nombre} ${empleado.apellido}
                    </h3>
                    
                    <div class="text-sm text-gray-600 mb-3">
                        <div class="flex items-center justify-center mb-1">
                            <i class="fas fa-envelope mr-2 text-blue-500"></i>
                            <span class="text-xs">${empleado.email}</span>
                        </div>
                        <div class="flex items-center justify-center">
                            <i class="fas fa-phone mr-2 text-green-500"></i>
                            <span class="text-xs">${empleado.telefono}</span>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-xs font-medium text-gray-600">Rendimiento</span>
                            <span class="text-xs font-bold text-blue-600">${empleado.rendimiento}%</span>
                        </div>
                        <div class="performance-bar">
                            <div class="performance-fill" style="width: ${empleado.rendimiento}%"></div>
                        </div>
                    </div>
                    
                    <div class="flex space-x-2 mt-4">
                        <button class="flex-1 bg-blue-500 hover:bg-blue-600 text-white text-xs py-2 px-3 rounded-lg transition-colors">
                            <i class="fas fa-edit mr-1"></i>
                            Editar
                        </button>
                        <button class="flex-1 bg-green-500 hover:bg-green-600 text-white text-xs py-2 px-3 rounded-lg transition-colors">
                            <i class="fas fa-eye mr-1"></i>
                            Ver
                        </button>
                    </div>
                </div>
            `).join('');
        }

        function updateStats() {
            const stats = empleados.reduce((acc, empleado) => {
                acc[empleado.estado] = (acc[empleado.estado] || 0) + 1;
                return acc;
            }, {});
            
            document.getElementById('total-empleados').textContent = empleados.length;
            document.getElementById('activos-count').textContent = stats.activo || 0;
            document.getElementById('descanso-count').textContent = stats.descanso || 0;
            document.getElementById('inactivos-count').textContent = stats.inactivo || 0;
        }

        function filterEmpleados() {
            const searchTerm = document.getElementById('search-input').value.toLowerCase();
            const roleFilter = document.getElementById('role-filter').value;
            const statusFilter = document.getElementById('status-filter').value;
            
            filteredEmpleados = empleados.filter(empleado => {
                const matchesSearch = empleado.nombre.toLowerCase().includes(searchTerm) ||
                                    empleado.apellido.toLowerCase().includes(searchTerm) ||
                                    empleado.email.toLowerCase().includes(searchTerm);
                
                const matchesRole = !roleFilter || empleado.rol === roleFilter;
                const matchesStatus = !statusFilter || empleado.estado === statusFilter;
                
                return matchesSearch && matchesRole && matchesStatus;
            });
            
            renderEmpleados(filteredEmpleados);
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', () => {
            renderEmpleados();
            updateStats();
            
            // Event listeners
            document.getElementById('search-input').addEventListener('input', filterEmpleados);
            document.getElementById('role-filter').addEventListener('change', filterEmpleados);
            document.getElementById('status-filter').addEventListener('change', filterEmpleados);
            
            // Auto-refresh stats every 30 seconds
            setInterval(updateStats, 30000);
        });

        // Add some dynamic effects
        setInterval(() => {
            const activeEmployees = document.querySelectorAll('[data-id]');
            activeEmployees.forEach((card, index) => {
                setTimeout(() => {
                    card.style.transform = 'scale(1.02)';
                    setTimeout(() => {
                        card.style.transform = '';
                    }, 200);
                }, index * 50);
            });
        }, 8000);
    </script>
</body>
</html>