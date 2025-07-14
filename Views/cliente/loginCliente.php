
<?php 

session_start();
if (isset($_SESSION['sesion_mesa_id'])) {
    header("Location: menu.php");
    exit;
}

$token = $_GET['token'] ?? '';  
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Ingreso Cliente - Café</title>
  <link rel="stylesheet" href="../assets/css/cliente.css" />
</head>
<body>
  <?php if ($token == ''): ?>
    <p class="text-danger">Token de mesa no proporcionado. Use el QR válido.</p>
  <?php else: ?>
  <div class="container">
    <h2>Bienvenido a Cafetería</h2>
    <p>Por favor ingrese sus datos para iniciar el pedido en la mesa.</p>
    <form action="../../Controllers/ClienteController.php" method="POST">
      <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
      <div class="mb-3">
        <label for="nombre" class="form-label">Nombre:</label>
        <input type="text" name="nombre" id="nombre" class="form-control" required>
      </div>
      <div class="mb-3">
        <label for="email" class="form-label">Correo:</label>
        <input type="email" name="email" id="email" class="form-control" required>
      </div>
      <button type="submit" class="btn btn-primary">Ingresar</button>
    </form>
    <?php if (isset($_GET['error']) && $_GET['error']=='token'): ?>
      <div class="alert alert-danger mt-3">Código QR inválido o expirado.</div>
    <?php endif; ?>
  </div>
  <?php endif; ?>
</body>
</html>
