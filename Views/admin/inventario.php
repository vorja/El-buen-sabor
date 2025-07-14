<!-- Views/admin/inventario.php -->
<?php 
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 2) {
    header("Location: ../login.php");
    exit;
}
require_once __DIR__ . '/../../Models/ProductoModel.php';
$productos = Models\ProductoModel::obtenerProductos(false);
?>
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
    <h1>Inventario de Productos</h1>
    <button onclick="document.getElementById('formAgregar').style.display='block'" class="btn btn-success">
      + Agregar Producto
    </button>
    <table class="table table-bordered mt-3">
      <thead><tr>
        <th>Producto</th><th>Categoría</th><th>Stock</th><th>Estado</th><th>Precio (USD)</th>
      </tr></thead>
      <tbody>
        <?php foreach ($productos as $prod): ?>
        <tr>
          <td><?= htmlspecialchars($prod['nombre']) ?></td>
          <td><?= htmlspecialchars($prod['categoria'] ?? '') ?></td>
          <td>
            <?= $prod['tipo_inventario']=='cantidad' ? $prod['stock'] . ' uds' 
                                                    : ($prod['disponibilidad']=='disponible' ? 'Disponible' : 'Agotado') ?>
          </td>
          <td>
            <?php 
              if ($prod['tipo_inventario']=='cantidad') {
                // Stock crítico/bajo: podríamos definir thresholds, aquí simplificamos
                if ($prod['stock'] <= ($prod['stock_minimo'] ?? 0)) {
                  echo "<span class='text-danger'>Crítico</span>";
                } else if ($prod['stock'] > ($prod['stock_minimo'] ?? 0)) {
                  echo "<span class='text-success'>Normal</span>";
                }
              } else {
                echo $prod['disponibilidad']=='disponible' ? 'En venta' : 'No disponible';
              }
            ?>
          </td>
          <td>$<?= number_format($prod['precio_unitario'], 2) ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <!-- Formulario para agregar nuevo producto -->
    <div id="formAgregar" style="display:none; margin-top:20px;">
      <h3>Nuevo Producto</h3>
      <form action="../../Controllers/AdminController.php" method="POST">
        <div class="mb-2">
          <label>Nombre: <input type="text" name="nombre" required></label>
        </div>
        <div class="mb-2">
          <label>Categoría: 
            <select name="categoria_id">
              <option value="1">Cafés</option>
              <option value="2">Tés</option>
              <option value="3">Pastelería</option>
              <option value="4">Bebidas frías</option>
            </select>
          </label>
        </div>
        <div class="mb-2">
          <label>Descripción: <input type="text" name="descripcion"></label>
        </div>
        <div class="mb-2">
          <label>Precio Unitario: <input type="number" step="0.01" name="precio" required></label>
        </div>
        <div class="mb-2">
          <label>Tipo Inventario: 
            <select name="tipo_inventario" onchange="if(this.value=='cantidad'){ document.getElementById('stockField').style.display='block'; } else { document.getElementById('stockField').style.display='none'; }">
              <option value="cantidad">Cantidad (stock numérico)</option>
              <option value="disponibilidad">Solo disponibilidad</option>
            </select>
          </label>
        </div>
        <div class="mb-2" id="stockField">
          <label>Stock Inicial: <input type="number" step="0.01" name="stock" value="0"></label>
          <label>Stock Mínimo: <input type="number" step="0.01" name="stock_min" value="0"></label>
        </div>
        <input type="hidden" name="accion" value="agregar_producto">
        <button type="submit" class="btn btn-primary">Guardar</button>
        <button type="button" class="btn btn-secondary" onclick="document.getElementById('formAgregar').style.display='none'">Cancelar</button>
      </form>
    </div>
  </div>
</body>
</html>
