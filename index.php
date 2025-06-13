<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>El Buen Sabor - Café Artesanal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        .menu-item {
            transition: transform 0.3s ease;
        }
        .menu-item:hover {
            transform: translateY(-5px);
        }
        .category-tab.active {
            background-color: #4A5568;
            color: white;
        }
    </style>
</head>
<body class="bg-gray-100">
    <!-- Hero Section -->
    <div class="relative h-[60vh] bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80');">
        <div class="absolute inset-0 bg-black bg-opacity-50"></div>
        <div class="relative h-full flex items-center justify-center text-center">
            <div class="text-white">
                <h1 class="text-5xl font-bold mb-4">El Buen Sabor</h1>
                <p class="text-xl mb-8">Descubre el arte del café artesanal</p>
                <a href="#menu" class="bg-yellow-600 text-white px-8 py-3 rounded-full hover:bg-yellow-700 transition duration-300">
                    Ver Menú
                </a>
            </div>
        </div>
    </div>

    <!-- Menu Section -->
    <div id="menu" class="py-16 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <h2 class="text-3xl font-bold text-center mb-12">Nuestro Menú</h2>
            
            <!-- Category Tabs -->
            <div class="flex justify-center space-x-4 mb-8 overflow-x-auto pb-2">
                <button class="category-tab active px-6 py-2 rounded-full bg-gray-200 hover:bg-gray-300 transition duration-300" data-category="cafe">Café</button>
                <button class="category-tab px-6 py-2 rounded-full bg-gray-200 hover:bg-gray-300 transition duration-300" data-category="postres">Postres</button>
                <button class="category-tab px-6 py-2 rounded-full bg-gray-200 hover:bg-gray-300 transition duration-300" data-category="sandwiches">Sandwiches</button>
            </div>

            <!-- Menu Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Café Items -->
                <div class="menu-item bg-white rounded-lg shadow-lg overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1541167760496-1628856ab772?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60" alt="Café Americano" class="w-full h-48 object-cover">
                    <div class="p-6">
                        <h3 class="text-xl font-semibold mb-2">Café Americano</h3>
                        <p class="text-gray-600 mb-4">Café negro intenso con un toque de agua caliente</p>
                        <div class="flex justify-between items-center">
                            <span class="text-2xl font-bold text-yellow-600">$2.50</span>
                            <button class="bg-yellow-600 text-white px-4 py-2 rounded-full hover:bg-yellow-700 transition duration-300">
                                Agregar
                            </button>
                        </div>
                    </div>
                </div>

                <div class="menu-item bg-white rounded-lg shadow-lg overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1572442388796-11668a67e53d?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60" alt="Cappuccino" class="w-full h-48 object-cover">
                    <div class="p-6">
                        <h3 class="text-xl font-semibold mb-2">Cappuccino</h3>
                        <p class="text-gray-600 mb-4">Espresso con leche espumada y cacao</p>
                        <div class="flex justify-between items-center">
                            <span class="text-2xl font-bold text-yellow-600">$3.50</span>
                            <button class="bg-yellow-600 text-white px-4 py-2 rounded-full hover:bg-yellow-700 transition duration-300">
                                Agregar
                            </button>
                        </div>
                    </div>
                </div>

                <div class="menu-item bg-white rounded-lg shadow-lg overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1579888944880-d98341245702?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60" alt="Latte" class="w-full h-48 object-cover">
                    <div class="p-6">
                        <h3 class="text-xl font-semibold mb-2">Latte</h3>
                        <p class="text-gray-600 mb-4">Espresso con leche cremosa y arte latte</p>
                        <div class="flex justify-between items-center">
                            <span class="text-2xl font-bold text-yellow-600">$4.00</span>
                            <button class="bg-yellow-600 text-white px-4 py-2 rounded-full hover:bg-yellow-700 transition duration-300">
                                Agregar
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Postres Items -->
                <div class="menu-item bg-white rounded-lg shadow-lg overflow-hidden hidden" data-category="postres">
                    <img src="https://images.unsplash.com/photo-1551024506-0bccd828d307?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60" alt="Tiramisú" class="w-full h-48 object-cover">
                    <div class="p-6">
                        <h3 class="text-xl font-semibold mb-2">Tiramisú</h3>
                        <p class="text-gray-600 mb-4">Postre italiano con café y mascarpone</p>
                        <div class="flex justify-between items-center">
                            <span class="text-2xl font-bold text-yellow-600">$5.50</span>
                            <button class="bg-yellow-600 text-white px-4 py-2 rounded-full hover:bg-yellow-700 transition duration-300">
                                Agregar
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Sandwiches Items -->
                <div class="menu-item bg-white rounded-lg shadow-lg overflow-hidden hidden" data-category="sandwiches">
                    <img src="https://images.unsplash.com/photo-1528735602780-2552fd46c7af?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60" alt="Club Sandwich" class="w-full h-48 object-cover">
                    <div class="p-6">
                        <h3 class="text-xl font-semibold mb-2">Club Sandwich</h3>
                        <p class="text-gray-600 mb-4">Pollo, tocino, lechuga y tomate</p>
                        <div class="flex justify-between items-center">
                            <span class="text-2xl font-bold text-yellow-600">$7.50</span>
                            <button class="bg-yellow-600 text-white px-4 py-2 rounded-full hover:bg-yellow-700 transition duration-300">
                                Agregar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-gray-800 text-white py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
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
        document.querySelectorAll('.category-tab').forEach(tab => {
            tab.addEventListener('click', () => {
                document.querySelectorAll('.category-tab').forEach(t => {
                    t.classList.remove('active');
                });
                
                tab.classList.add('active');
                
                const category = tab.dataset.category;
                document.querySelectorAll('.menu-item').forEach(item => {
                    if (category === 'cafe') {
                        item.classList.remove('hidden');
                    } else {
                        item.classList.toggle('hidden', item.dataset.category !== category);
                    }
                });
            });
        });
    </script>
</body>
</html> 