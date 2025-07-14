<?php
// Views/admin/inventario.php
session_start();
// Sólo admins (rol = 2)
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 2) {
    header('Location: ../login.php');
    exit;
}

// Carga de PDO y modelos
require_once __DIR__ . '/../../Models/Database.php';
use Models\Database;

// Traer todas las categorías para el <select>
$categorias = Database::queryAll("SELECT id, nombre FROM categorias ORDER BY nombre");

// Traer los productos actuales
$productos = Database::queryAll("
  SELECT p.*, c.nombre AS categoria
    FROM productos p
    LEFT JOIN categorias c ON c.id = p.categoria_id
   ORDER BY p.nombre
");
?>
<?php include __DIR__ . '/../partials/header.php'; ?>

<div class="container mt-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Inventario de Productos</h3>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAgregarProducto">
      <i class="bi bi-plus-lg"></i> Agregar Producto
    </button>
  </div>

  <table class="table table-striped">
    <thead>
      <tr>
        <th>Nombre</th>
        <th>Categoría</th>
        <th>Tipo Inventario</th>
        <th>Stock / Disponibilidad</th>
        <th>Precio Unitario</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($productos as $prod): ?>
      <tr>
        <td><?= htmlspecialchars($prod['nombre']) ?></td>
        <td><?= htmlspecialchars($prod['categoria']) ?></td>
        <td><?= $prod['tipo_inventario'] ?></td>
        <td>
          <?= $prod['tipo_inventario']=='cantidad'
              ? (int)$prod['stock']
              : htmlspecialchars($prod['disponibilidad']) ?>
        </td>
        <td>$<?= number_format($prod['precio_unitario'],2) ?></td>
        <td>
          <!-- Editar -->
          <button class="btn btn-sm btn-warning"
                  data-bs-toggle="modal"
                  data-bs-target="#modalEditarProducto<?= $prod['id'] ?>">
            <i class="bi bi-pencil-square"></i>
          </button>
          <!-- Eliminar -->
          <a href="../../Controllers/ProductoController.php?accion=borrar&id=<?= $prod['id'] ?>"
             class="btn btn-sm btn-danger"
             onclick="return confirm('¿Eliminar \"<?= htmlspecialchars($prod['nombre']) ?>\"?')">
            <i class="bi bi-trash"></i>
          </a>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<?php include __DIR__ . '/modals_producto.php'; ?>
<?php include __DIR__ . '/../partials/footer.php'; ?>
