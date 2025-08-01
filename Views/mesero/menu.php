<?php
// Views/mesero/menu.php
// Página de inicio para los meseros con un menú de acciones principales.

session_start();
// Verificar que el usuario sea un mesero (rol = 1)
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 1) {
    header("Location: ../login.php");
    exit;
}

// Establecer el título de la página para el header
$pageTitle = "Menú Mesero";

// Incluir la cabecera personalizada del mesero
require_once __DIR__ . '/../partials/header_mesero.php';
?>

<div class="d-flex flex-column align-items-center">
  <?php if (isset($_SESSION['nombre_empleado'])): ?>
    <h2 class="mb-4" style="color: var(--coffee-text-dark);">Bienvenido, <?= htmlspecialchars($_SESSION['nombre_empleado']) ?></h2>
  <?php else: ?>
    <h2 class="mb-4" style="color: var(--coffee-text-dark);">Bienvenido</h2>
  <?php endif; ?>
  <div class="menu-grid">
    <!-- Opción Mesas -->
    <a href="/mesas.php" class="menu-btn">
      <i class="fas fa-table"></i>
      <span>Mesas</span>
    </a>
    <!-- Opción Mis Pedidos -->
    <a href="./pedido.php" class="menu-btn">
      <i class="fas fa-receipt"></i>
      <span>Mis&nbsp;Pedidos</span>
    </a>
    <!-- Opción Cerrar Sesión -->
    <a href="./Controllers/logout.php" class="menu-btn">
      <i class="fas fa-sign-out-alt"></i>
      <span>Cerrar&nbsp;Sesión</span>
    </a>
  </div>
</div>

<!-- Estilos específicos para el menú del mesero -->
<style>
  /* Contenedor del grid de opciones */
  .menu-grid {
    width: 100%;
    max-width: 400px;
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
    margin: 2rem auto;
  }
  /* Botón de cada opción del menú */
  .menu-btn {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.25rem 1.5rem;
    border-radius: 0.75rem;
    text-decoration: none;
    font-weight: 600;
    font-size: 1.2rem;
    color: var(--coffee-text-dark);
    background-color: var(--coffee-medium);
    transition: background-color 0.2s, transform 0.1s, color 0.2s;
  }
  .menu-btn:hover {
    background-color: var(--coffee-dark);
    color: #ffffff;
    transform: translateY(-2px);
  }
  .menu-btn i {
    font-size: 1.5rem;
  }
</style>

<?php
// Incluir el pie de página del mesero
require_once __DIR__ . '/../partials/footer_mesero.php';
?>