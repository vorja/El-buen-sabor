<!-- Views/cliente/menu.php -->
<?php 
session_start();
if (!isset($_SESSION['sesion_mesa_id'])) {
    header("Location: loginCliente.php?token=" . ($_GET['token'] ?? ''));
    exit;
}
require_once __DIR__ . '/../../Models/ProductoModel.php';
require_once __DIR__ . '/../../Models/DetallePedidoModel.php';

$productos = Models\ProductoModel::obtenerProductos(true);
$detalles = isset($_SESSION['pedido_id']) 
            ? Models\DetallePedidoModel::obtenerDetalles($_SESSION['pedido_id']) 
            : [];
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Menú - Café</title>
  <link rel="stylesheet" href="../assets/css/cliente.css" />
</head>
<body>
  <div class="container">
    <h1>Menú de Productos</h1>
    <p>Seleccione los productos que desea ordenar:</p>
    <div class="row">
      <?php foreach ($productos as $prod): ?>
      <div class="col-6 col-sm-4 mb-3">
        <div class="card">
          <!-- Si hubiera imágenes, se podrían mostrar -->
          <div class="card-body">
            <h5 class="card-title"><?= htmlspecialchars($prod['nombre']) ?></h5>
            <p class="card-text"><?= htmlspecialchars($prod['descripcion'] ?? '') ?></p>
            <p><strong>$<?= number_format($prod['precio_unitario'], 2) ?></strong></p>
            <?php if ($prod['disponibilidad'] === 'agotado'): ?>
              <button class="btn btn-secondary" disabled>Agotado</button>
            <?php else: ?>
              <form action="../../Controllers/ClienteController.php" method="POST">
                <input type="hidden" name="add_product_id" value="<?= $prod['id'] ?>">
                <button type="submit" class="btn btn-primary">Pedir</button>
              </form>
            <?php endif; ?>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>

    <hr>
    <h2>Pedido en curso</h2>
    <?php if (count($detalles) > 0): ?>
      <ul>
        <?php foreach ($detalles as $item): ?>
          <li><?= $item['nombre'] ?> x <?= $item['cantidad'] ?> - $<?= number_format($item['precio_unit'], 2) ?> c/u</li>
        <?php endforeach; ?>
      </ul>
      <p><em>Puede seguir agregando productos. Cuando esté listo para pagar, presione "Cerrar Pedido".</em></p>
    <?php else: ?>
      <p>Aún no ha pedido ningún producto.</p>
    <?php endif; ?>

    <form action="../../Controllers/ClienteController.php" method="POST" 
          onsubmit="return confirm('¿Está seguro de cerrar el pedido?')">
      <input type="hidden" name="solicitar_cuenta" value="1">
      <button type="submit" class="btn btn-danger">Cerrar Pedido</button>
    </form>
  </div>
</body>
</html>
