 <!DOCTYPE html>
 <html lang="en">
 <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>empleados</title>
      <script src="https://cdn.tailwindcss.com"></script>
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.min.css">
    <link rel="stylesheet" href="../assets/css/empleados.css">
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
   
      <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Empleados</h2>
        <div class="flex space-x-2">
          <button class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-colors">
            <i class="fas fa-user-plus mr-1"></i> Nuevo Empleado
          </button>
          <button class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg transition-colors">
            <i class="fas fa-calendar-alt mr-1"></i> Horarios
          </button>
        </div>
      </div>
      
      <div class="bg-white rounded-lg shadow-md overflow-hidden relative" id="empleados-container">
        <div class="p-4 border-b flex flex-col md:flex-row justify-between items-center gap-4">
          <div class="w-full md:w-64">
            <div class="relative">
              <input type="text" placeholder="Buscar empleado..." class="w-full pl-10 pr-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
              <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            </div>
          </div>
          <div class="flex space-x-2">
            <select class="border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
              <option>Todos los roles</option>
              <option>Barista</option>
              <option>Mesero</option>
              <option>Cocinero</option>
              <option>Gerente</option>
            </select>
            <select class="border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
              <option>Todos los estados</option>
              <option>Activo</option>
              <option>Descanso</option>
              <option>Inactivo</option>
            </select>
          </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 p-4" id="empleados-grid">
          <!-- Data will be populated here -->
        </div>
        <div class="p-4 border-t flex justify-between items-center">
          <div class="text-sm text-gray-500">Mostrando 9 de 15 empleados</div>
          <div class="flex space-x-1">
            <button class="px-3 py-1 border rounded hover:bg-gray-100">&laquo;</button>
            <button class="px-3 py-1 border rounded bg-blue-500 text-white">1</button>
            <button class="px-3 py-1 border rounded hover:bg-gray-100">2</button>
            <button class="px-3 py-1 border rounded hover:bg-gray-100">&raquo;</button>
          </div>
        </div>
      </div>
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

 </body>
 </html>