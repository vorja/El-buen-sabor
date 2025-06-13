<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mesas</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/mesas.css">
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
        <h2 class="text-2xl font-bold text-gray-800">Mesas</h2>
        <div class="flex space-x-2">
          <button class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-colors">
            <i class="fas fa-edit mr-1"></i> Editar Disposición
          </button>
          <button class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg transition-colors">
            <i class="fas fa-calendar-plus mr-1"></i> Reservaciones
          </button>
        </div>
      </div>
      
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow-md overflow-hidden col-span-2">
          <div class="p-4 border-b">
            <h3 class="font-medium text-lg">Plano del Local</h3>
          </div>
          <div class="p-6 relative" id="mesas-container">
            <div class="flex flex-wrap justify-center items-center" id="mesas-grid">
              <!-- Data will be populated here -->
            </div>
          </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
          <div class="p-4 border-b">
            <h3 class="font-medium text-lg">Leyenda y Estadísticas</h3>
          </div>
          <div class="p-4">
            <div class="mb-6">
              <div class="flex items-center mb-2">
                <div class="w-4 h-4 bg-green-500 mr-2"></div>
                <span>Disponible: <span id="disponible-count">0</span></span>
              </div>
              <div class="flex items-center mb-2">
                <div class="w-4 h-4 bg-red-500 mr-2"></div>
                <span>Ocupada: <span id="ocupada-count">0</span></span>
              </div>
              <div class="flex items-center">
                <div class="w-4 h-4 bg-yellow-500 mr-2"></div>
                <span>Reservada: <span id="reservada-count">0</span></span>
              </div>
            </div>
            
            <div class="mb-4">
              <h4 class="font-medium mb-2">Cambiar Estado de Mesa</h4>
              <div class="flex mb-3">
                <select id="mesa-selector" class="border rounded-l-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 w-full">
                  <option value="">Seleccionar mesa...</option>
                </select>
              </div>
              <div class="flex mb-3">
                <select id="estado-selector" class="border rounded-l-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 w-full">
                  <option value="disponible">Disponible</option>
                  <option value="ocupada">Ocupada</option>
                  <option value="reservada">Reservada</option>
                </select>
              </div>
              <button id="cambiar-estado-btn" class="w-full bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-colors">
                Cambiar Estado
              </button>
            </div>
          </div>
        </div>
      </div>
    </section>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

</body>
</html>