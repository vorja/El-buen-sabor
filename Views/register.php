<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/register.css">
</head>
<body>
     <body class="min-h-screen flex flex-col items-center justify-center p-4">
    <div class="coffee-beans"></div>
    
    <div class="w-full max-w-md form-container p-8 relative">
      <h1 class="text-3xl font-bold text-center mb-6">Bienvenido a nuestra Cafetería</h1>
      <p class="text-center mb-8 text-gray-600">Regístrate para recibir ofertas exclusivas y novedades.</p>
      
      <div id="registration-form">
        <form id="customer-form" class="space-y-6">
          <div class="form-group">
            <label for="email" class="block text-sm font-medium mb-1">Correo Electrónico</label>
            <input type="email" id="email" name="email" placeholder="ejemplo@correo.com" class="input-field w-full p-3">
            <div id="email-error" class="error-message hidden"></div>
          </div>
          
          <div class="form-group">
            <label for="fullname" class="block text-sm font-medium mb-1">Nombre Completo</label>
            <input type="text" id="fullname" name="fullname" placeholder="Juan Pérez Gómez" class="input-field w-full p-3">
            <div id="fullname-error" class="error-message hidden"></div>
          </div>
          
          <div class="form-group">
            <label for="phone" class="block text-sm font-medium mb-1">Teléfono</label>
            <input type="tel" id="phone" name="phone" placeholder="3101234567" class="input-field w-full p-3">
            <div id="phone-error" class="error-message hidden"></div>
          </div>
          
          <div class="form-group">
            <label for="cedula" class="block text-sm font-medium mb-1">Cédula de Ciudadanía</label>
            <input type="text" id="cedula" name="cedula" placeholder="1234567890" class="input-field w-full p-3">
            <div id="cedula-error" class="error-message hidden"></div>
          </div>
          
          <button type="submit" class="btn-coffee w-full py-3 px-4 rounded-lg font-medium text-center">
            Registrarse
          </button>
        </form>
      </div>
      
      <div id="success-message" class="hidden flex flex-col items-center justify-center py-8">
        <div class="success-banner w-full p-4 mb-6 rounded-lg">
          <p class="text-center text-lg font-medium"><i class="fas fa-check-circle mr-2"></i> ¡Registro exitoso!</p>
          <p class="text-center text-sm mt-2">Gracias por registrarte en nuestra cafetería.</p>
        </div>
        <button id="new-registration" class="btn-coffee py-3 px-6 rounded-lg font-medium">
          Registrar otro cliente
        </button>
      </div>
    </div>
    
    <p class="mt-8 text-sm text-gray-500">© 2025 Cafetería. Todos los derechos reservados.</p>
</body>
</html>