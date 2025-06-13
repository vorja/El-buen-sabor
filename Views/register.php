<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - El Buen Sabor</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/register.css">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css">
</head>

<body class="min-h-screen flex flex-col items-center justify-center p-4 bg-coffee-pattern">
    <div class="coffee-beans"></div>
    
    <div class="w-full max-w-md form-container p-8 relative bg-white/95 backdrop-blur-sm rounded-xl shadow-2xl">
        <h1 class="text-3xl font-bold text-center mb-6 text-coffee-dark">Bienvenido a El Buen Sabor</h1>
        <p class="text-center mb-8 text-gray-600">Regístrate para recibir ofertas exclusivas y novedades.</p>
        
        <div id="registration-form">
            <form id="customer-form" class="space-y-6" method="post" action="../Controllers/registro.php">
                <div class="form-group">
                    <label for="email" class="block text-sm font-medium mb-1 text-coffee-dark">Correo Electrónico</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-coffee-medium">✉</span>
                        <input type="email" id="email" name="email" placeholder="ejemplo@correo.com" 
                               class="input-field w-full p-3 pl-10 rounded-lg border border-coffee-light focus:border-coffee-dark focus:ring-2 focus:ring-coffee-light">
                    </div>
                    <div id="email-error" class="error-message hidden text-red-500 text-sm mt-1"></div>
                </div>
                
                <div class="form-group">
                    <label for="fullname" class="block text-sm font-medium mb-1 text-coffee-dark">Nombre Completo</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-coffee-medium">👤</span>
                        <input type="text" id="fullname" name="fullname" placeholder="Juan Pérez Gómez" 
                               class="input-field w-full p-3 pl-10 rounded-lg border border-coffee-light focus:border-coffee-dark focus:ring-2 focus:ring-coffee-light">
                    </div>
                    <div id="fullname-error" class="error-message hidden text-red-500 text-sm mt-1"></div>
                </div>
                
                <div class="form-group">
                    <label for="phone" class="block text-sm font-medium mb-1 text-coffee-dark">Teléfono</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-coffee-medium">📱</span>
                        <input type="tel" id="phone" name="phone" placeholder="3101234567" 
                               class="input-field w-full p-3 pl-10 rounded-lg border border-coffee-light focus:border-coffee-dark focus:ring-2 focus:ring-coffee-light">
                    </div>
                    <div id="phone-error" class="error-message hidden text-red-500 text-sm mt-1"></div>
                </div>
                
                <div class="form-group">
                    <label for="cedula" class="block text-sm font-medium mb-1 text-coffee-dark">Cédula de Ciudadanía</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-coffee-medium">🪪</span>
                        <input type="text" id="cedula" name="cedula" placeholder="1234567890" 
                               class="input-field w-full p-3 pl-10 rounded-lg border border-coffee-light focus:border-coffee-dark focus:ring-2 focus:ring-coffee-light">
                    </div>
                    <div id="cedula-error" class="error-message hidden text-red-500 text-sm mt-1"></div>
                </div>

                <div class="form-group">
                    <label for="contraseña" class="block text-sm font-medium mb-1 text-coffee-dark">Contraseña</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-coffee-medium">🔒</span>
                        <input type="password" id="contraseña" name="contraseña" placeholder="********" 
                               class="input-field w-full p-3 pl-10 rounded-lg border border-coffee-light focus:border-coffee-dark focus:ring-2 focus:ring-coffee-light">
                        <span class="absolute right-3 top-1/2 transform -translate-y-1/2 text-coffee-medium cursor-pointer" id="togglePassword">👁️</span>
                    </div>
                    <div id="contraseña-error" class="error-message hidden text-red-500 text-sm mt-1"></div>
                </div>
                
                <button type="submit" class="btn-coffee w-full py-3 px-4 rounded-lg font-medium text-center bg-coffee-dark text-white hover:bg-coffee-medium transition-colors duration-300">
                    ☕ Registrarse
                </button>
            </form>
        </div>
        
        <div id="success-message" class="hidden flex flex-col items-center justify-center py-8">
            <div class="success-banner w-full p-4 mb-6 rounded-lg bg-green-100 border border-green-200">
                <p class="text-center text-lg font-medium text-green-800">
                    ✅ ¡Registro exitoso!
                </p>
                <p class="text-center text-sm mt-2 text-green-600">
                    Gracias por registrarte en El Buen Sabor.
                </p>
            </div>
            <button id="new-registration" class="btn-coffee py-3 px-6 rounded-lg font-medium bg-coffee-dark text-white hover:bg-coffee-medium transition-colors duration-300">
                Registrar otro cliente
            </button>
        </div>
    </div>
    
    <p class="mt-8 text-sm text-gray-500">© 2024 El Buen Sabor. Todos los derechos reservados.</p>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>
    <script src="../assets/js/register.js"></script>
</body>
</html>