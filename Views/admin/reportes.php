<!-- Views/admin/reportes.php -->
<?php 
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 2) {
    header("Location: ../login.php");
    exit;
}
require_once __DIR__ . '/../partials/header.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reportes - Admin</title>
  <link rel="stylesheet" href="../../assets/css/admin.css">
</head>
<body>
  <div class="container">
    <h1>Reportes</h1>
    <h3>Balance de Ventas por Fecha</h3>
    <form action="../../Controllers/ReportesController.php" method="POST" target="_blank">
      <label>Desde: <input type="date" name="desde" required></label>
      <label>Hasta: <input type="date" name="hasta" required></label>
      <button type="submit" name="reporte" value="ventas_pdf" class="btn btn-primary">Generar PDF</button>
    </form>

    <h3>Desempeño por Empleado</h3>
    <form action="../../Controllers/ReportesController.php" method="POST" target="_blank">
      <label>Empleado: 
        <select name="empleado_id">
          <?php 
          require_once __DIR__ . '/../../Models/EmpleadoModel.php';
          $meseros = Models\EmpleadoModel::obtenerMeseros();
          foreach ($meseros as $m): ?>
          <option value="<?= $m['id'] ?>"><?= htmlspecialchars($m['nombre']) ?></option>
          <?php endforeach; ?>
        </select>
      </label>
      <button type="submit" name="reporte" value="empleado_pdf" class="btn btn-primary">Generar PDF</button>
    </form>

    <h3>Estado de Inventario</h3>
    <form action="../../Controllers/ReportesController.php" method="POST" target="_blank">
      <button type="submit" name="reporte" value="inventario_pdf" class="btn btn-primary">Exportar Inventario PDF</button>
    </form>
  </div>
  <?php require_once __DIR__ . '/../partials/footer.php'; ?>
</body>
</html>
