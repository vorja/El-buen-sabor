<!-- Views/admin/empleados.php -->
<?php 
  session_start();
  if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 2) {
      header("Location: ../login.php");
      exit;
  }
  require_once __DIR__ . '/../partials/header.php';
  require_once __DIR__ . '/../../Models/Database.php';
  // Obtener todos los empleados con su rol
  $empleados = Models\Database::queryAll("
      SELECT e.id, e.nombre, e.email, r.nombre AS rol
      FROM empleados e
      JOIN roles r ON e.rol_id = r.id
      ORDER BY e.id
  ");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Empleados - Admin</title>
  <link rel="stylesheet" href="../../assets/css/admin.css" />
</head>
<body>
  <div class="container">
    <h1>Gestión de Empleados</h1>
    <table class="table table-striped">
      <thead>
        <tr><th>ID</th><th>Nombre</th><th>Email</th><th>Rol</th></tr>
      </thead>
      <tbody>
        <?php foreach ($empleados as $emp): ?>
        <tr>
          <td><?= $emp['id'] ?></td>
          <td><?= htmlspecialchars($emp['nombre']) ?></td>
          <td><?= htmlspecialchars($emp['email']) ?></td>
          <td><?= htmlspecialchars($emp['rol']) ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <hr>
    <h3>Nuevo Empleado</h3>
    <form action="../../Controllers/AdminController.php" method="POST" class="mb-3">
      <div class="mb-2">
        <label>Nombre: 
          <input type="text" name="nombre" required class="form-control">
        </label>
      </div>
      <div class="mb-2">
        <label>Email: 
          <input type="email" name="email" required class="form-control">
        </label>
      </div>
      <div class="mb-2">
        <label>Contraseña: 
          <input type="password" name="password" required class="form-control">
        </label>
      </div>
      <div class="mb-2">
        <label>Rol: 
          <select name="rol_id" class="form-select">
            <option value="1">Mesero</option>
            <option value="2">Administrador</option>
          </select>
        </label>
      </div>
      <input type="hidden" name="accion" value="agregar_empleado">
      <button type="submit" class="btn btn-primary">Guardar Empleado</button>
    </form>
  </div>
  <?php require_once __DIR__ . '/../partials/footer.php'; ?>
</body>
</html>
