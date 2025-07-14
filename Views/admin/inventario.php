 <?php
  session_start();
 // Control de acceso: solo admins (rol=2)
 if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 2) {
     header("Location: ../login.php");
     exit;
 }
 // Carga del wrapper PDO y del modelo
 require_once __DIR__ . '/../../Models/Database.php';
 require_once __DIR__ . '/../../Models/ProductoModel.php';
 use Models\Database;
 use Models\ProductoModel;

// Obtener todos los productos activos
$productos = ProductoModel::obtenerProductos(true);
 ?>
    <?php include __DIR__ . '/../partials/header.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Inventario - Admin</title>
  <link rel="stylesheet" href="../../assets/css/admin.css" />
</head>
<body>
  <div class="container">


<h2>Inventario de Productos</h2>
<a href="#" class="btn btn-success mb-3">+ Agregar Producto</a>

<div class="table-responsive">
  <table class="table table-striped">
    <thead>
      <tr>
        <th>Producto</th>
        <th>Categoría</th>
        <th>Stock</th>
        <th>Estado</th>
        <th>Precio (USD)</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($productos as $prod): ?>
      <tr>
        <td><?= htmlspecialchars($prod['nombre']) ?></td>
        <td><?= htmlspecialchars($prod['categoria']) ?></td>
        <td>
          <?php
           
              echo $prod['stock'];
            
          ?>
        </td>
        <td>
          <?php
            
              echo ($prod['stock'] <= ($prod['stock_minimo'] ?? 0))
                ? '<span class="badge bg-danger">Crítico</span>'
                : '<span class="badge bg-secondary">Normal</span>';
          
          ?>
        </td>
        <td>$<?= number_format($prod['precio_unitario'], 2) ?></td>
        <td>
          <a href="#" class="btn btn-sm btn-primary">Editar</a>
          <a href="#" class="btn btn-sm btn-danger">Eliminar</a>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>