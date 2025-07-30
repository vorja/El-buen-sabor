<!-- Views/admin/reportes.php -->
<?php 
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 2) {
    header("Location: ../login.php");
    exit;
}
// Cargar cabecera de administrador con barra lateral
$pageTitle = "Reportes";
require_once __DIR__ . '/../partials/admin_header.php';
?>
  <!--
    Diseño de Reportes
    Se organiza el contenido en tarjetas individuales para cada tipo de reporte: ventas por fecha,
    desempeño de empleados y estado de inventario. Cada tarjeta incluye un encabezado y un formulario
    compacto con los controles necesarios para generar el reporte correspondiente. Se emplean
    estructuras de filas y columnas para un ajuste responsivo.
  -->
  <div class="container-fluid">
    <h2 class="mb-4">Reportes</h2>
    <div class="row g-4">
      <!-- Balance de Ventas por Fecha -->
      <div class="col-md-6 col-lg-4">
        <div class="card shadow-sm h-100">
          <div class="card-header bg-success-subtle fw-semibold">Ventas por Fecha</div>
          <div class="card-body">
            <form action="Controllers/ReportesController.php" method="POST" target="_blank" class="row g-2 align-items-end">
              <div class="col-12">
                <label class="form-label">Desde</label>
                <input type="date" name="desde" class="form-control" required>
              </div>
              <div class="col-12">
                <label class="form-label">Hasta</label>
                <input type="date" name="hasta" class="form-control" required>
              </div>
              <div class="col-12">
                <button type="submit" name="reporte" value="ventas_pdf" class="btn btn-primary w-100">
                  <i class="fa fa-file-pdf me-1"></i> Generar PDF
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
      <!-- Desempeño por Empleado -->
      <div class="col-md-6 col-lg-4">
        <div class="card shadow-sm h-100">
          <div class="card-header bg-info-subtle fw-semibold">Desempeño por Empleado</div>
          <div class="card-body">
            <form action="Controllers/ReportesController.php" method="POST" target="_blank" class="row g-2 align-items-end">
              <div class="col-12">
                <label class="form-label">Empleado</label>
                <select name="empleado_id" class="form-select">
                  <?php 
                  require_once __DIR__ . '/../../Models/EmpleadoModel.php';
                  $meseros = Models\EmpleadoModel::obtenerMeseros();
                  foreach ($meseros as $m): ?>
                  <option value="<?= $m['id'] ?>"><?= htmlspecialchars($m['nombre']) ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="col-12">
                <button type="submit" name="reporte" value="empleado_pdf" class="btn btn-primary w-100">
                  <i class="fa fa-file-pdf me-1"></i> Generar PDF
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
      <!-- Estado de Inventario -->
      <div class="col-md-6 col-lg-4">
        <div class="card shadow-sm h-100">
          <div class="card-header bg-warning-subtle fw-semibold">Estado de Inventario</div>
          <div class="card-body d-flex flex-column justify-content-between">
            <p class="mb-3">Descarga un informe del inventario actual en formato PDF.</p>
            <form action="Controllers/ReportesController.php" method="POST" target="_blank">
              <button type="submit" name="reporte" value="inventario_pdf" class="btn btn-primary w-100">
                <i class="fa fa-file-pdf me-1"></i> Exportar Inventario
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php require_once __DIR__ . '/../partials/admin_footer.php'; ?>
