<!-- Views/admin/clientes.php -->
<?php 
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 2) {
    header("Location: ../login.php");
    exit;
}
require_once __DIR__ . '/../../Models/Database.php';
$clientes = Models\Database::queryAll("SELECT * FROM clientes ORDER BY creado DESC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Clientes - Admin</title>
  <link rel="stylesheet" href="../../assets/css/admin.css">
</head>
<body>
  <div class="container">
    <h1>Clientes Registrados</h1>
    <table class="table table-hover">
      <thead><tr><th>Nombre</th><th>Email</th><th>Documento</th><th>Registrado</th></tr></thead>
      <tbody>
        <?php foreach ($clientes as $c): ?>
        <tr>
          <td><?= htmlspecialchars($c['nombre']) ?></td>
          <td><?= htmlspecialchars($c['email']) ?></td>
          <td><?= htmlspecialchars($c['documento'] ?? '') ?></td>
          <td><?= $c['creado'] ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</body>
</html>
