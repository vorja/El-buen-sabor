<?php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 2) {
  header('Location: ../login.php');
  exit;
}

require_once __DIR__ . '/../../Models/Database.php';
use Models\Database;

// Cargar categorías para el select
$categorias = Database::queryAll("SELECT id,nombre FROM categorias ORDER BY nombre");
// Cargar productos para la tabla
$productos  = Database::queryAll("
  SELECT p.*, c.nombre AS categoria
    FROM productos p
    LEFT JOIN categorias c ON c.id = p.categoria_id
   ORDER BY p.nombre
");
$pageTitle = "Inventario";
require_once __DIR__ . '/../partials/header.php';
?>

<div class="container mt-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Inventario de Productos</h3>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAgregarProducto">
      <i class="bi bi-plus-lg"></i> Agregar Producto
    </button>
  </div>

  <table class="table table-hover">
    <thead class="table-light">
      <tr>
        <th>Nombre</th>
        <th>Categoría</th>
        <th>Tipo Inv.</th>
        <th>Stock/Disp.</th>
        <th>Precio</th>
        <th style="width:120px">Acciones</th>
      </tr>
    </thead>
    <tbody>
    <?php foreach($productos as $p): ?>
      <tr>
        <td><?= htmlspecialchars($p['nombre']) ?></td>
        <td><?= htmlspecialchars($p['categoria']) ?></td>
        <td><?= $p['tipo_inventario'] ?></td>
        <td>
          <?= $p['tipo_inventario']=='cantidad'
               ? (int)$p['stock']
               : htmlspecialchars($p['disponibilidad']) ?>
        </td>
        <td>$<?= number_format($p['precio_unitario'],2) ?></td>
        <td>
          <button class="btn btn-sm btn-warning"
                  data-bs-toggle="modal"
                  data-bs-target="#modalEditarProducto<?= $p['id'] ?>">
            <i class="bi bi-pencil-fill"></i>
          </button>
          <a href="../../Controllers/ProductoController.php?accion=borrar&id=<?= $p['id'] ?>"
             class="btn btn-sm btn-danger"
             onclick="return confirm('¿Eliminar <?= addslashes($p['nombre']) ?>?')">
            <i class="bi bi-trash-fill"></i>
          </a>
        </td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div>

<?php include __DIR__ . '/modals_producto.php'; ?>
<?php require_once __DIR__ . '/../partials/footer.php'; ?>
