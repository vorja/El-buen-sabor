<?php
// repository/Views/cliente/loginCliente.php
// Vista que solicita el nombre y correo del cliente cuando éste
// accede mediante el código QR.  Se aplica un nuevo diseño más
// agradable basado en Bootstrap y estilos personalizados.  Si la
// sesión ya está activa, se redirige directamente al menú.

// Configurar la cookie de sesión para que dure 4 horas. Esto
// garantiza que la sesión del cliente persista incluso si el
// navegador se cierra y se vuelve a abrir dentro de ese periodo.
session_set_cookie_params([
    'lifetime' => 4 * 60 * 60,
    'path'     => '/',
    'httponly' => true,
]);
session_start();
// Si el cliente ya tiene una sesión de mesa activa, saltar al menú
if (isset($_SESSION['sesion_mesa_id'])) {
    header('Location: menu.php');
    exit;
}

$token  = $_GET['token'] ?? '';
$mesero = $_GET['mesero'] ?? '';
?><!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Ingreso Cliente – Cafetería</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet" />
  <!-- Ruta corregida al archivo de estilos personalizado. El CSS se
       encuentra en la carpeta `assets/css` a nivel raíz del proyecto.
       Desde `Views/cliente`, necesitamos subir dos niveles. -->
  <link rel="stylesheet" href="../../assets/css/cliente.css" />
</head>
<body>
  <?php if ($token == ''): ?>
    <div class="container">
      <div class="alert alert-danger mt-5">
        Token de mesa no proporcionado. Utilice un código QR válido.
      </div>
    </div>
  <?php else: ?>
  <div class="login-wrapper">
    <h2>Bienvenido a El Buen Sabor</h2>
    <p class="mb-4 text-center">Introduce tus datos para comenzar tu pedido.</p>
    <form action="../../Controllers/ClienteController.php" method="POST" novalidate>
      <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>" />
      <input type="hidden" name="mesero" value="<?= htmlspecialchars($mesero) ?>" />
      <div class="mb-3">
        <label for="nombre" class="form-label">Nombre completo</label>
        <input type="text" name="nombre" id="nombre" class="form-control" required placeholder="Tu nombre" />
      </div>
      <div class="mb-3">
        <label for="email" class="form-label">Correo electrónico</label>
        <input type="email" name="email" id="email" class="form-control" required placeholder="correo@ejemplo.com" />
      </div>
      <button type="submit" class="btn btn-primary w-100 mt-3">Ingresar</button>
    </form>
    <?php if (isset($_GET['error']) && $_GET['error'] == 'token'): ?>
      <div class="alert alert-danger">Código QR inválido o expirado.</div>
    <?php elseif (isset($_GET['error']) && $_GET['error'] == 'mesero'): ?>
      <div class="alert alert-danger">Error al identificar al mesero. Utilice el código proporcionado por su mesero.</div>
    <?php elseif (isset($_GET['error']) && $_GET['error'] == 'no_data'): ?>
      <div class="alert alert-danger">Debe ingresar su nombre y correo.</div>
    <?php endif; ?>
  </div>
  <?php endif; ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>