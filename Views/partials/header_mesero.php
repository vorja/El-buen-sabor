<?php
/**
 * Cabecera específica para las vistas del mesero.
 * Muestra una barra de navegación superior con el título "Área Meseros".
 * Este archivo asume que la sesión ya ha sido iniciada en el script que lo
 * requiere y que el usuario corresponde al rol de mesero (rol = 1).
 */

// Definir base URL para rutas relativas. Ajusta esta variable si tu
// proyecto está en una carpeta distinta a «el-buen-sabor» en tu servidor.
// Se utiliza el nombre exacto del directorio en minúsculas para evitar
// problemas con sistemas sensibles a mayúsculas/minúsculas.
$baseUrl = '/el-buen-sabor';
?><!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <base href="<?= $baseUrl ?>/">
  <title><?= isset($pageTitle) ? $pageTitle . " | El Buen Sabor" : "El Buen Sabor" ?></title>
  <!-- Fuentes e iconos -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <!-- Estilos personalizados comunes -->
  <link href="assets/css/style.css" rel="stylesheet" />
  <style>
    /* Estilos para el botón de retroceso */
    .btn-back {
      background-color: transparent;
      border: none;
      color: var(--coffee-text-dark);
      font-size: 1.2rem;
      padding: 0.5rem 1rem;
      transition: all 0.3s ease;
    }
    
    .btn-back:hover {
      color: var(--coffee-dark);
      transform: translateX(-3px);
    }
    
    .navbar-mesero {
      background-color: var(--coffee-dark);
      padding: 0.5rem 1rem;
    }
    
    .navbar-brand-mesero {
      font-size: 1.25rem;
      font-weight: 600;
    }
  </style>
</head>
<body class="bg-light">
  <!-- Barra de navegación superior para el área de meseros -->
  <nav class="navbar navbar-mesero">
    <div class="container-fluid">
      <?php if(basename($_SERVER['PHP_SELF']) != 'menu.php'): ?>
        <button onclick="window.history.back();" class="btn-back">
          <i class="fas fa-arrow-left me-2"></i>
        </button>
      <?php endif; ?>
      <?php
        // Mostrar el nombre del mesero conectado en la barra de navegación.
        $nombreMesero = isset($_SESSION['nombre_empleado']) ? $_SESSION['nombre_empleado'] : 'Mesero';
      ?>
      <span class="navbar-brand-mesero mx-auto"><?= htmlspecialchars($nombreMesero) ?></span>
    </div>
  </nav>
  <div class="container py-4">
    <!-- Inicio del contenido principal -->
    <div class="mt-2">