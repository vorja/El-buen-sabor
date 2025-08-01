<?php
// repository/Views/cliente/menu.php
// Página de menú para clientes registrados desde el QR.  Aquí se
// muestran los productos disponibles organizados por categorías y se
// permite al cliente añadirlos a su pedido mediante modales.  La
// sesión del cliente se valida al cargar esta vista.

session_start();
if (!isset($_SESSION['sesion_mesa_id'])) {
    // Si no existe la sesión, redirigir al formulario de login con el
    // token presente en la URL (si existe) para no perder la mesa.
    $tokenParam = isset($_GET['token']) ? '?token=' . urlencode($_GET['token']) : '';
    header('Location: loginCliente.php' . $tokenParam);
    exit;
}

require_once __DIR__ . '/../../Models/ProductoModel.php';
require_once __DIR__ . '/../../Models/DetallePedidoModel.php';

use Models\ProductoModel;
use Models\DetallePedidoModel;

// Obtener productos activos y disponibles agrupados por categoría
// Obtener productos activos y disponibles agrupados por categoría.  Esta
// variable tendrá forma ['nombre_categoria' => [ ...productos... ]].
$productosPorCategoria = ProductoModel::obtenerProductosPorCategoria(true);

// Determinar la primera categoría del array para mostrarla por defecto.
// En versiones de PHP anteriores a 7.3 no existe array_key_first, así
// que usamos reset/array_key_first según disponibilidad.
if (!function_exists('array_key_first')) {
    $firstCategoryKey = key($productosPorCategoria);
} else {
    $firstCategoryKey = array_key_first($productosPorCategoria);
}

// Obtener los ítems actuales del pedido para mostrar resumen
$detalles = isset($_SESSION['pedido_id'])
            ? DetallePedidoModel::obtenerDetalles($_SESSION['pedido_id'])
            : [];

?><!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Menú – El Buen Sabor</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet" />
  <style>
    body { font-family: 'Poppins', sans-serif; }
    .category-button.active { background-color: #4A5568; color: #fff; }
  </style>
</head>
<body class="bg-light">
  <div class="container py-5">
    <h1 class="text-center mb-4">Menú de Productos</h1>
    <p class="text-center text-muted mb-5">Seleccione los productos que desea ordenar.</p>
    <!-- Categorías -->
    <div class="d-flex justify-content-center flex-wrap gap-2 mb-4">
      <?php $first = true; foreach ($productosPorCategoria as $categoriaNombre => $prods): ?>
        <?php $catId = md5($categoriaNombre); ?>
        <button class="btn btn-outline-secondary category-button <?php echo $first ? 'active' : ''; ?>" data-category="<?php echo $catId; ?>">
          <?php echo htmlspecialchars(ucfirst($categoriaNombre)); ?>
        </button>
      <?php $first = false; endforeach; ?>
    </div>
    <!-- Productos -->
    <div class="row g-4">
      <?php foreach ($productosPorCategoria as $categoriaNombre => $prods): ?>
        <?php $catId = md5($categoriaNombre); ?>
        <?php foreach ($prods as $prod): ?>
          <div class="col-6 col-md-4 product-card" data-category="<?php echo $catId; ?>" <?php echo ($categoriaNombre !== $firstCategoryKey) ? 'style="display:none;"' : ''; ?> >
            <div class="card h-100">
              <?php if (!empty($prod['imagen'])): ?>
                <img src="<?php echo htmlspecialchars($prod['imagen']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($prod['nombre']); ?>" style="object-fit:cover; height:180px;" />
              <?php else: ?>
                <img src="https://images.unsplash.com/photo-1551024506-0bccd828d307?auto=format&fit=crop&w=500&q=60" class="card-img-top" alt="Imagen" style="object-fit:cover; height:180px;" />
              <?php endif; ?>
              <div class="card-body d-flex flex-column">
                <h5 class="card-title"><?php echo htmlspecialchars($prod['nombre']); ?></h5>
                <?php if (!empty($prod['descripcion'])): ?>
                  <p class="card-text small text-muted flex-grow-1"><?php echo htmlspecialchars($prod['descripcion']); ?></p>
                <?php endif; ?>
                <button class="btn btn-sm btn-primary mt-2" data-bs-toggle="modal" data-bs-target="#modal-<?php echo $prod['id']; ?>">Ver detalle</button>
              </div>
            </div>
          </div>
          <!-- Modal de producto -->
          <div class="modal fade" id="modal-<?php echo $prod['id']; ?>" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title"><?php echo htmlspecialchars($prod['nombre']); ?></h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                  <?php if (!empty($prod['imagen'])): ?>
                    <img src="<?php echo htmlspecialchars($prod['imagen']); ?>" class="img-fluid mb-3" alt="<?php echo htmlspecialchars($prod['nombre']); ?>" />
                  <?php endif; ?>
                  <?php if (!empty($prod['descripcion'])): ?>
                    <p><?php echo nl2br(htmlspecialchars($prod['descripcion'])); ?></p>
                  <?php endif; ?>
                </div>
                <div class="modal-footer">
                  <form action="../../Controllers/ClienteController.php" method="POST">
                    <input type="hidden" name="add_product_id" value="<?php echo $prod['id']; ?>" />
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Pedir</button>
                  </form>
                </div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endforeach; ?>
    </div>
    <!-- Resumen del pedido -->
    <hr class="my-5" />
    <h2 class="mb-3">Pedido en curso</h2>
    <?php if (count($detalles) > 0): ?>
      <ul class="list-group mb-3">
        <?php $total = 0; foreach ($detalles as $item): ?>
          <?php $subtotal = $item['cantidad'] * $item['precio_unit']; $total += $subtotal; ?>
          <li class="list-group-item d-flex justify-content-between align-items-center">
            <div>
              <strong><?php echo htmlspecialchars($item['nombre']); ?></strong> x <?php echo intval($item['cantidad']); ?>
            </div>
            <span>$<?php echo number_format($subtotal, 2); ?></span>
          </li>
        <?php endforeach; ?>
        <li class="list-group-item d-flex justify-content-between align-items-center fw-bold">
          Total
          <span>$<?php echo number_format($total, 2); ?></span>
        </li>
      </ul>
      <form action="../../Controllers/ClienteController.php" method="POST" onsubmit="return confirm('¿Desea solicitar la cuenta?');">
        <input type="hidden" name="solicitar_cuenta" value="1" />
        <button type="submit" class="btn btn-danger">Solicitar cuenta</button>
      </form>
    <?php else: ?>
      <p>Aún no ha añadido ningún producto a su pedido.</p>
    <?php endif; ?>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Alterna productos al cambiar de categoría
    document.querySelectorAll('.category-button').forEach(function(btn) {
      btn.addEventListener('click', function() {
        var cat = btn.getAttribute('data-category');
        // activar/desactivar botones
        document.querySelectorAll('.category-button').forEach(function(b){ b.classList.remove('active'); });
        btn.classList.add('active');
        // mostrar productos
        document.querySelectorAll('.product-card').forEach(function(card) {
          if (card.getAttribute('data-category') === cat) {
            card.style.display = '';
          } else {
            card.style.display = 'none';
          }
        });
      });
    });
  </script>
</body>
</html>