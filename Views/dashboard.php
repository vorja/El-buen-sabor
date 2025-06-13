<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DashBoard</title>
     <script src="https://cdn.tailwindcss.com"></script>
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.min.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>
<body>
    <body class="bg-gray-100 font-sans">
  <!-- Navbar -->
  <nav class="bg-blue-800 text-white shadow-lg fixed w-full z-10">
    <div class="container mx-auto px-4">
      <div class="flex justify-between items-center py-3">
        <div class="flex items-center">
          <i class="fas fa-coffee text-2xl mr-2"></i>
          <span class="font-bold text-xl">Café Manager</span>
        </div>
        <div class="hidden md:block">
          <span class="text-sm text-blue-200">
            <i class="fas fa-clock mr-1"></i> Última actualización: <span id="last-update">Ahora</span>
          </span>
        </div>
      </div>
      <div class="navbar flex space-x-1 md:space-x-4 py-2 overflow-x-auto">
        <button class="nav-btn px-3 md:px-4 py-2 rounded-lg bg-blue-700 hover:bg-blue-600 font-medium transition-colors" data-section="inventario">
          <i class="fas fa-boxes mr-1"></i> Inventario
        </button>
        <button class="nav-btn px-3 md:px-4 py-2 rounded-lg hover:bg-blue-600 font-medium transition-colors" data-section="empleados">
          <i class="fas fa-users mr-1"></i> Empleados
        </button>
        <button class="nav-btn px-3 md:px-4 py-2 rounded-lg hover:bg-blue-600 font-medium transition-colors" data-section="mesas">
          <i class="fas fa-chair mr-1"></i> Mesas
        </button>
        <button class="nav-btn px-3 md:px-4 py-2 rounded-lg hover:bg-blue-600 font-medium transition-colors" data-section="ventas">
          <i class="fas fa-cash-register mr-1"></i> Ventas
        </button>
        <button class="nav-btn px-3 md:px-4 py-2 rounded-lg hover:bg-blue-600 font-medium transition-colors" data-section="reporte">
          <i class="fas fa-chart-line mr-1"></i> Reporte
        </button>
      </div>
    </div>
  </nav>

   <main class="container mx-auto px-4 pt-32 pb-10">
    <!-- Inventario Section -->
    <section id="inventario" class="section active">
      <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Inventario</h2>
        <div class="flex space-x-2">
          <button class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-colors">
            <i class="fas fa-plus mr-1"></i> Nuevo Ítem
          </button>
          <button class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg transition-colors">
            <i class="fas fa-file-export mr-1"></i> Exportar
          </button>
        </div>
      </div>

      <div class="bg-white rounded-lg shadow-md overflow-hidden relative" id="inventario-container">
        <div class="p-4 border-b flex flex-col md:flex-row justify-between items-center gap-4">
          <div class="w-full md:w-64">
            <div class="relative">
              <input type="text" placeholder="Buscar inventario..." class="w-full pl-10 pr-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
              <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            </div>
          </div>
          <div class="flex space-x-2">
            <select class="border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
              <option>Todos los items</option>
              <option>Café</option>
              <option>Alimentos</option>
              <option>Utensilios</option>
            </select>
            <select class="border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
              <option>Ordenar por nombre</option>
              <option>Ordenar por stock</option>
              <option>Ordenar por precio</option>
            </select>
          </div>
        </div>
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Categoría</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Precio</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200" id="inventario-table">
              <!-- Data will be populated here -->
            </tbody>
          </table>
        </div>
        <div class="p-4 border-t flex justify-between items-center">
          <div class="text-sm text-gray-500">Mostrando 10 de 34 items</div>
          <div class="flex space-x-1">
            <button class="px-3 py-1 border rounded hover:bg-gray-100">&laquo;</button>
            <button class="px-3 py-1 border rounded bg-blue-500 text-white">1</button>
            <button class="px-3 py-1 border rounded hover:bg-gray-100">2</button>
            <button class="px-3 py-1 border rounded hover:bg-gray-100">3</button>
            <button class="px-3 py-1 border rounded hover:bg-gray-100">&raquo;</button>
          </div>
        </div>
      </div>
    </section>
    
</body>
</html>