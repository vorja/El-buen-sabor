<!-- Views/admin/cobrar.php -->
<?php 
session_start();
// Solo admins (rol = 2)
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 2) {
    header("Location: ../login.php");
    exit;
}
// Cargar modelo y obtener información del pedido
require_once __DIR__ . '/../../Models/PedidoModel.php';
require_once __DIR__ . '/../../Models/DetallePedidoModel.php';
require_once __DIR__ . '/../../Models/Database.php';
// Id del pedido que se va a cobrar
$pedidoId = $_GET['pedido'] ?? null;
if (!$pedidoId) {
    header("Location: ventas.php");
    exit;
}
$pedido = Models\PedidoModel::obtenerPedido($pedidoId);
if (!$pedido || $pedido['estado'] != 'entregado') {
    // Mostrar mensaje si el pedido no está pendiente de pago
    $pageTitle = "Cobrar Pedido";
    require_once __DIR__ . '/../partials/admin_header.php';
    echo "<div class='alert alert-warning'>El pedido no está pendiente de pago.</div>";
    require_once __DIR__ . '/../partials/admin_footer.php';
    exit;
}
$detalles = Models\DetallePedidoModel::obtenerDetalles($pedidoId);
// Obtener datos del cliente principal del pedido
$cliente = Models\Database::queryOne(
    "SELECT c.* FROM clientes c 
     JOIN sesiones_mesa s ON c.id = s.cliente_id 
     JOIN pedidos pd ON pd.sesion_id = s.id 
     WHERE pd.id = ?", [ $pedidoId ]
);

// Configurar título y cabecera de administración
$pageTitle = "Cobrar Pedido #$pedidoId";
require_once __DIR__ . '/../partials/admin_header.php';
?>

<div class="container-fluid">
  <h2 class="mb-4">Cobrar Pedido #<?= htmlspecialchars($pedidoId) ?></h2>
  <div class="row g-4">
    <!-- Sección de detalles del pedido -->
    <div class="col-lg-6">
      <div class="card shadow-sm h-100">
        <div class="card-header fw-semibold">Resumen del pedido</div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-sm align-middle mb-3">
              <thead class="table-light">
                <tr>
                  <th>Producto</th>
                  <th>Cant.</th>
                  <th>Precio</th>
                  <th>Subtotal</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($detalles as $item): ?>
                <?php $subtotal = $item['cantidad'] * $item['precio_unit']; ?>
                <tr>
                  <td><?= htmlspecialchars($item['nombre']) ?></td>
                  <td><?= $item['cantidad'] ?></td>
                  <td>$<?= number_format($item['precio_unit'], 2) ?></td>
                  <td>$<?= number_format($subtotal, 2) ?></td>
                </tr>
                <?php endforeach; ?>
              </tbody>
              <tfoot>
                <tr>
                  <th colspan="3" class="text-end">Total</th>
                  <th>$<?= number_format($pedido['total'], 2) ?></th>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </div>
    </div>
    <!-- Sección de datos del cliente y pago -->
    <div class="col-lg-6">
      <div class="card shadow-sm h-100">
        <div class="card-header fw-semibold">Datos del cliente y pago</div>
        <div class="card-body">
          <form action="Controllers/AdminController.php" method="POST" class="row g-3">
            <?php if ($cliente): ?>
            <div class="col-12">
              <label class="form-label">Nombre registrado</label>
              <input type="text" name="nombre_cliente" value="<?= htmlspecialchars($cliente['nombre']) ?>" class="form-control" required>
            </div>
            <div class="col-12">
              <label class="form-label">Email</label>
              <input type="email" name="email_cliente" value="<?= htmlspecialchars($cliente['email']) ?>" class="form-control" required>
            </div>
            <div class="col-12">
              <label class="form-label">Documento</label>
              <input type="text" name="documento_cliente" value="<?= htmlspecialchars($cliente['documento'] ?? '') ?>" class="form-control" required>
            </div>
            <input type="hidden" name="cliente_id" value="<?= $cliente['id'] ?>">
            <?php else: ?>
            <div class="col-12">
              <div class="alert alert-info">No hay cliente registrado para este pedido.</div>
            </div>
            <?php endif; ?>
            <div class="col-12">
              <label class="form-label">Método de pago</label>
              <select name="metodo_pago" class="form-select" readonly>
                <option value="efectivo" selected>Efectivo</option>
              </select>
            </div>
            <input type="hidden" name="cerrar_pedido_id" value="<?= $pedidoId ?>">
            <div class="col-12 d-flex justify-content-between">
              <a href="views/ admin/ventas.php" class="btn btn-outline-secondary">Cancelar</a>
              <button type="submit" class="btn btn-success" onclick="return confirm('¿Confirmar registro de pago?');">
                Registrar Pago
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../partials/admin_footer.php'; ?>
