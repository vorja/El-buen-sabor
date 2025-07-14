<?php
// Views/admin/modals_producto.php
// Utiliza $categorias y $productos definidos en inventario.php
?>

<!-- Modal: Agregar Producto -->
<div class="modal fade" id="modalAgregarProducto" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form action="/El-buen-sabor/Controllers/ProductoController.php" method="POST" enctype="multipart/form-data">
      <input type="hidden" name="accion" value="crear">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Nuevo Producto</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Nombre</label>
            <input name="nombre" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Categoría</label>
            <select name="categoria_id" class="form-select" required>
              <?php foreach($categorias as $c): ?>
              <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['nombre']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Tipo Inventario</label>
            <select name="tipo_inventario" class="form-select" required>
              <option value="cantidad">Cantidad</option>
              <option value="disponibilidad">Disponibilidad</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Stock</label>
            <input type="number" name="stock" class="form-control" value="0" min="0">
          </div>
          <div class="mb-3">
            <label class="form-label">Disponibilidad</label>
            <select name="disponibilidad" class="form-select">
              <option value="disponible">Disponible</option>
              <option value="agotado">Agotado</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Precio Unitario</label>
            <input type="number" step="0.01" name="precio_unitario" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Descripción (opcional)</label>
            <textarea name="descripcion" class="form-control"></textarea>
          </div>
          <div class="mb-3">
            <label class="form-label">Imagen (opcional)</label>
            <input type="file" name="imagen" class="form-control">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Guardar</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Modals: Editar Producto x ID -->
<?php foreach($productos as $p): ?>
<div class="modal fade" id="modalEditarProducto<?= $p['id'] ?>" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form action="/El-buen-sabor/Controllers/ProductoController.php" method="POST" enctype="multipart/form-data">
      <input type="hidden" name="accion" value="actualizar">
      <input type="hidden" name="id" value="<?= $p['id'] ?>">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Editar Producto</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Nombre</label>
            <input name="nombre" class="form-control" value="<?= htmlspecialchars($p['nombre']) ?>" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Categoría</label>
            <select name="categoria_id" class="form-select" required>
              <?php foreach($categorias as $c): ?>
              <option value="<?= $c['id'] ?>" <?= $c['id']==$p['categoria_id']?'selected':'' ?>>
                <?= htmlspecialchars($c['nombre']) ?>
              </option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Tipo Inventario</label>
            <select name="tipo_inventario" class="form-select" required>
              <option value="cantidad" <?= $p['tipo_inventario']=='cantidad'?'selected':'' ?>>Cantidad</option>
              <option value="disponibilidad" <?= $p['tipo_inventario']=='disponibilidad'?'selected':'' ?>>Disponibilidad</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Stock</label>
            <input type="number" name="stock" class="form-control" value="<?= (int)$p['stock'] ?>" min="0">
          </div>
          <div class="mb-3">
            <label class="form-label">Disponibilidad</label>
            <select name="disponibilidad" class="form-select">
              <option value="disponible" <?= $p['disponibilidad']=='disponible'?'selected':'' ?>>Disponible</option>
              <option value="agotado" <?= $p['disponibilidad']=='agotado'?'selected':'' ?>>Agotado</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Precio Unitario</label>
            <input type="number" step="0.01" name="precio_unitario" class="form-control" 
                   value="<?= number_format($p['precio_unitario'],2) ?>" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Descripción (opcional)</label>
            <textarea name="descripcion" class="form-control"><?= htmlspecialchars($p['descripcion']) ?></textarea>
          </div>
          <div class="mb-3">
            <label class="form-label">Imagen (opcional)</label>
            <input type="file" name="imagen" class="form-control">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-warning">Actualizar</button>
        </div>
      </div>
    </form>
  </div>
</div>
<?php endforeach; ?>
