<?php
// repository/index.php
// Página de inicio pública.  Esta página se muestra cuando un
// visitante entra al sitio sin escanear un código QR.  Aquí
// mostramos una descripción del negocio y un catálogo de productos en
// tarjetas, organizados por categorías.  No se muestran los precios
// ni botones de compra, ya que esta sección sirve para que los
// potenciales clientes conozcan la oferta del local.

require_once __DIR__ . '/Models/ProductoModel.php';
require_once __DIR__ . '/Models/Database.php';

use Models\ProductoModel;

// Obtener los productos agrupados por categoría.  Sólo
// mostramos productos activos; se incluyen tanto disponibles como
// agotados porque el catálogo es meramente informativo.
$productosPorCategoria = ProductoModel::obtenerProductosPorCategoria(false);

// Determinar la primera categoría para mostrarla por defecto.
// array_key_first solo está disponible a partir de PHP 7.3. Para
// versiones anteriores utilizamos key() sobre el primer elemento del array.
if (!function_exists('array_key_first')) {
    $firstCategoryKey = key($productosPorCategoria);
} else {
    $firstCategoryKey = array_key_first($productosPorCategoria);
}

?><!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>El Buen Sabor – Conoce nuestro menú</title>
    <!-- Tailwind CSS via CDN para un diseño moderno y responsivo -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; }
        .hero { background-image: url('https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?auto=format&fit=crop&w=1950&q=80'); }
        .hero::before { content:''; position:absolute; inset:0; background:rgba(0,0,0,0.5); }
    </style>
</head>
<body class="bg-gray-100">
    <!-- Hero Section -->
    <section class="hero relative h-[60vh] bg-cover bg-center flex items-center justify-center text-center text-white">
        <div class="z-10">
            <h1 class="text-5xl font-bold mb-4 drop-shadow-lg">El Buen Sabor</h1>
            <p class="text-xl mb-8 drop-shadow">Descubre el arte del café artesanal</p>
            <a href="#menu" class="bg-yellow-600 text-white px-8 py-3 rounded-full hover:bg-yellow-700 transition duration-300">Ver Menú</a>
        </div>
    </section>

    <!-- Menu Section -->
    <section id="menu" class="py-16 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <h2 class="text-3xl font-bold text-center mb-12">Nuestro Menú</h2>
            <!-- Navegación de categorías -->
            <div class="flex justify-center space-x-4 mb-8 overflow-x-auto pb-2">
                <?php $primera = true; foreach ($productosPorCategoria as $categoriaNombre => $prods): ?>
                    <button class="category-tab px-6 py-2 rounded-full bg-gray-200 hover:bg-gray-300 transition duration-300 <?php echo $primera ? 'active' : ''; ?>" data-category="<?php echo md5($categoriaNombre); ?>">
                        <?php echo htmlspecialchars(ucfirst($categoriaNombre)); ?>
                    </button>
                <?php $primera = false; endforeach; ?>
            </div>
            <!-- Contenedor de productos -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php foreach ($productosPorCategoria as $categoriaNombre => $prods): ?>
                    <?php foreach ($prods as $producto): ?>
                        <div class="menu-item bg-white rounded-lg shadow-lg overflow-hidden <?php echo $categoriaNombre !== $firstCategoryKey ? 'hidden' : ''; ?>" data-category="<?php echo md5($categoriaNombre); ?>">
                            <?php if (!empty($producto['imagen'])): ?>
                                <img src="<?php echo htmlspecialchars($producto['imagen']); ?>" alt="<?php echo htmlspecialchars($producto['nombre']); ?>" class="w-full h-48 object-cover">
                            <?php else: ?>
                                <img src="https://images.unsplash.com/photo-1541167760496-1628856ab772?auto=format&fit=crop&w=500&q=60" alt="Producto" class="w-full h-48 object-cover">
                            <?php endif; ?>
                            <div class="p-6">
                                <h3 class="text-xl font-semibold mb-2"><?php echo htmlspecialchars($producto['nombre']); ?></h3>
                                <?php if (!empty($producto['descripcion'])): ?>
                                    <p class="text-gray-600 mb-4 line-clamp-3"><?php echo htmlspecialchars($producto['descripcion']); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center md:text-left">
                <div>
                    <h3 class="text-xl font-bold mb-4">El Buen Sabor</h3>
                    <p class="text-gray-400">Café artesanal de la mejor calidad</p>
                </div>
                <div>
                    <h3 class="text-xl font-bold mb-4">Horario</h3>
                    <p class="text-gray-400">Lunes - Viernes: 7:00 AM - 8:00 PM</p>
                    <p class="text-gray-400">Sábado - Domingo: 8:00 AM - 9:00 PM</p>
                </div>
                <div>
                    <h3 class="text-xl font-bold mb-4">Contacto</h3>
                    <p class="text-gray-400">📞 (123) 456-7890</p>
                    <p class="text-gray-400">✉️ info@elbuensabor.com</p>
                </div>
            </div>
        </div>
    </footer>
    <script>
        // Alternar visibilidad de productos según la categoría seleccionada
        document.querySelectorAll('.category-tab').forEach(function(tab) {
            tab.addEventListener('click', function() {
                document.querySelectorAll('.category-tab').forEach(function(t) {
                    t.classList.remove('active');
                });
                tab.classList.add('active');
                var cat = tab.getAttribute('data-category');
                document.querySelectorAll('.menu-item').forEach(function(item) {
                    if (item.getAttribute('data-category') === cat) {
                        item.classList.remove('hidden');
                    } else {
                        item.classList.add('hidden');
                    }
                });
            });
        });
    </script>
</body>
</html>