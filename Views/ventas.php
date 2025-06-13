<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ventas</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/ventas.css">
    
</head>
<body>
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
        <a href="./dashboard.php" class="nav-btn px-3 md:px-4 py-2 rounded-lg bg-blue-700 hover:bg-blue-600 font-medium transition-colors" data-section="inventario">
          <i class="fas fa-boxes mr-1"></i> Inventario
        </a>
        <a href="./empleados.php" class="nav-btn px-3 md:px-4 py-2 rounded-lg hover:bg-blue-600 font-medium transition-colors" data-section="empleados">
          <i class="fas fa-users mr-1"></i> Empleados
        </a>
        <a href="./mesas.php" class="nav-btn px-3 md:px-4 py-2 rounded-lg hover:bg-blue-600 font-medium transition-colors" data-section="mesas">
          <i class="fas fa-chair mr-1"></i> Mesas
        </a>
        <a href="./ventas.php" class="nav-btn px-3 md:px-4 py-2 rounded-lg hover:bg-blue-600 font-medium transition-colors" data-section="ventas">
          <i class="fas fa-cash-register mr-1"></i> Ventas
        </a>
        <a href="./reporte.php" class="nav-btn px-3 md:px-4 py-2 rounded-lg hover:bg-blue-600 font-medium transition-colors" data-section="reporte">
          <i class="fas fa-chart-line mr-1"></i> Reporte
        </a>
      </div>
    </div>
  </nav>
       <section id="ventas" class="section">
      <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Ventas</h2>
        <div class="flex space-x-2">
          <button class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-colors">
            <i class="fas fa-plus-circle mr-1"></i> Nueva Venta
          </button>
          <button class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg transition-colors">
            <i class="fas fa-file-export mr-1"></i> Exportar
          </button>
        </div>
      </div>
      
      <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow-md p-4 col-span-1">
          <h3 class="font-medium text-gray-700 mb-4">Filtros</h3>
          
          <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Rango de Fechas</label>
            <div class="flex flex-col space-y-2">
              <input type="date" class="border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" value="2025-01-01">
              <input type="date" class="border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" value="2025-01-31">
            </div>
          </div>
          
          <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Categoría</label>
            <select class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
              <option>Todas las categorías</option>
              <option>Café</option>
              <option>Alimentos</option>
              <option>Postres</option>
            </select>
          </div>
          
          <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Método de Pago</label>
            <select class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
              <option>Todos los métodos</option>
              <option>Efectivo</option>
              <option>Tarjeta</option>
              <option>Transferencia</option>
            </select>
          </div>
          
          <button class="w-full bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-colors">
            Aplicar Filtros
          </button>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-4 col-span-1 lg:col-span-3">
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-blue-50 rounded-lg p-4 border border-blue-100">
              <h4 class="text-blue-800 text-sm font-medium mb-1">Total Ventas</h4>
              <p class="text-2xl font-bold text-blue-900">$85,423.50</p>
              <p class="text-xs text-blue-600 mt-1">+12.3% vs mes anterior</p>
            </div>
            <div class="bg-green-50 rounded-lg p-4 border border-green-100">
              <h4 class="text-green-800 text-sm font-medium mb-1">Promedio por Ticket</h4>
              <p class="text-2xl font-bold text-green-900">$254.80</p>
              <p class="text-xs text-green-600 mt-1">+3.5% vs mes anterior</p>
            </div>
            <div class="bg-purple-50 rounded-lg p-4 border border-purple-100">
              <h4 class="text-purple-800 text-sm font-medium mb-1">Total Transacciones</h4>
              <p class="text-2xl font-bold text-purple-900">335</p>
              <p class="text-xs text-purple-600 mt-1">+8.7% vs mes anterior</p>
            </div>
          </div>
          
          <div class="mb-6">
            <h3 class="font-medium text-gray-700 mb-4">Ventas por Período</h3>
            <div style="height: 300px;">
              <canvas id="ventas-chart"></canvas>
            </div>
          </div>
          
          <div>
            <h3 class="font-medium text-gray-700 mb-4">Últimas Transacciones</h3>
            <div class="overflow-x-auto">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Productos</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mesa</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Método</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200" id="ventas-table">
                  <!-- Data will be populated here -->
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </section>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    
</body>
</html>