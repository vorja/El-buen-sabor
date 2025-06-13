<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/reporte.css">
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
    <section id="reporte" class="section">
      <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Reporte e Insights</h2>
        <div class="flex space-x-2">
          <button class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-colors">
            <i class="fas fa-file-pdf mr-1"></i> Generar PDF
          </button>
          <button class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg transition-colors">
            <i class="fas fa-share-alt mr-1"></i> Compartir
          </button>
        </div>
      </div>
      
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow-md p-4 col-span-1">
          <h3 class="font-medium text-gray-700 mb-4">Configuración de Reporte</h3>
          
          <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Rango de Fechas</label>
            <div class="flex flex-col space-y-2">
              <input type="date" class="border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" value="2025-01-01">
              <input type="date" class="border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" value="2025-01-31">
            </div>
          </div>
          
          <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de Reporte</label>
            <select class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
              <option>Reporte Completo</option>
              <option>Ventas</option>
              <option>Inventario</option>
              <option>Empleados</option>
            </select>
          </div>
          
          <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Detalle</label>
            <select class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
              <option>Alto</option>
              <option>Medio</option>
              <option>Bajo</option>
            </select>
          </div>
          
          <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Incluir Insights IA</label>
            <div class="flex items-center">
              <input type="checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" checked>
              <span class="ml-2 text-sm text-gray-700">Activar análisis de tendencias</span>
            </div>
          </div>
          
          <button class="w-full bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-colors" id="generar-reporte-btn">
            Generar Reporte
          </button>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-4 col-span-1 lg:col-span-2">
          <h3 class="font-medium text-gray-700 mb-4">Resumen de Insights</h3>
          
          <div class="mb-6 bg-blue-50 rounded-lg p-4 border border-blue-100" id="ai-insights">
            <div class="flex items-start">
              <div class="flex-shrink-0 bg-blue-100 rounded-full p-2">
                <i class="fas fa-robot text-blue-600"></i>
              </div>
              <div class="ml-3">
                <h4 class="text-blue-800 font-medium">Análisis IA</h4>
                <p class="text-sm text-gray-600 mt-1">Cargando insights inteligentes del período seleccionado...</p>
              </div>
            </div>
          </div>
          
          <div class="mb-6">
            <h4 class="font-medium text-gray-700 mb-3">Indicadores Clave</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4" id="kpi-container">
              <!-- KPIs will be populated here -->
            </div>
          </div>
          
          <div class="mb-6">
            <h4 class="font-medium text-gray-700 mb-3">Tendencias</h4>
            <div style="height: 200px;">
              <canvas id="tendencias-chart"></canvas>
            </div>
          </div>
          
          <div>
            <h4 class="font-medium text-gray-700 mb-3">Recomendaciones</h4>
            <ul class="space-y-2 text-sm text-gray-600" id="recomendaciones-list">
              <!-- Recommendations will be populated here -->
            </ul>
          </div>
        </div>
      </div>
    </section>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    
</body>
</html>