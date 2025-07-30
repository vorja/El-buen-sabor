<?php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 2) {
  header('Location: ../login.php');
  exit;
}

require_once __DIR__ . '/../../Models/Database.php';
use Models\Database;

// Cargar categorías y productos
$categorias = Database::queryAll("SELECT id,nombre FROM categorias ORDER BY nombre");
$productos  = Database::queryAll("
  SELECT p.*, c.nombre AS categoria
    FROM productos p
    LEFT JOIN categorias c ON c.id = p.categoria_id
   ORDER BY p.nombre
");

$pageTitle = "Inventario";
// Cargar cabecera de administrador con barra lateral
$pageTitle = "Inventario";
require_once __DIR__ . '/../partials/admin_header.php';
?>

<!--
  Diseño de Inventario
  Se utiliza una tarjeta para agrupar la tabla y un encabezado con acción flotante. La tabla es responsiva y
  muestra acciones de edición y eliminación para cada producto. Utiliza íconos FontAwesome para mejorar la
  claridad visual y espacios consistentes para un diseño más profesional.
-->
<div class="container-fluid">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="mb-0">Inventario de Productos</h2>
    <button class="btn btn-primary d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#modalAgregarProducto">
      <i class="fa fa-plus me-2"></i> Nuevo producto
    </button>
  </div>
  <div class="card shadow-sm">
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead class="table-light">
            <tr>
              
              <th>Nombre</th>
              <th>Categoría</th>
              <th>Stock/Disponibilidad</th>
              <th>Precio</th>
              <th>Imagen</th>
              <th class="text-center" style="width:140px">Acciones</th>
            </tr>
          </thead>
          <tbody>
          <?php foreach($productos as $p): ?>
            <tr>
              
              <td><?= htmlspecialchars($p['nombre']) ?></td>
              <td><?= htmlspecialchars($p['categoria']) ?></td>
              <td>
                <?= $p['tipo_inventario'] === 'cantidad'
                     ? (int)$p['stock']
                     : htmlspecialchars($p['disponibilidad']) ?>
              </td>
              <td>$<?= number_format($p['precio_unitario'],2) ?></td>
              <td>
                <?php if (!empty($p['imagen'])): ?>
                  <img src="/El-buen-sabor/<?= $p['imagen'] ?>" alt="<?= htmlspecialchars($p['nombre']) ?>" class="rounded" style="width:50px;height:50px;object-fit:cover;">
                <?php else: ?>
                  <span class="text-muted">—</span>
                <?php endif; ?>
              </td>
              <td class="text-center">
                <button class="btn btn-sm btn-warning me-1"
                        data-bs-toggle="modal"
                        data-bs-target="#modalEditarProducto<?= $p['id'] ?>"
                        title="Editar">
                  <i class="fa fa-edit"></i>
                </button>
                <a href="/El-buen-sabor/Controllers/ProductoController.php?accion=borrar&id=<?= $p['id'] ?>"
                   class="btn btn-sm btn-danger"
                   title="Eliminar"
                   onclick="return confirm('¿Eliminar <?= addslashes($p['nombre']) ?>?')">
                  <i class="fa fa-trash"></i>
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<?php include __DIR__ . '/modals_producto.php'; ?>
<?php require_once __DIR__ . '/../partials/admin_footer.php'; ?>
