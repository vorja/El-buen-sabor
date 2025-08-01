<?php
// repository/Views/partials/header_mesero.php
// Cabecera común para las páginas del mesero. Incluye la estructura
// inicial del documento HTML, carga Bootstrap y define una barra
// de navegación simple con opciones relevantes para el rol del
// mesero.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Definir un título de página si no está ya definido en la vista
$pageTitle = $pageTitle ?? 'Mesero';
?><!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= htmlspecialchars($pageTitle) ?> – El Buen Sabor</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet" />
  <style>
    body { font-family: 'Poppins', sans-serif; }
    .navbar-brand { font-weight: 600; }
  </style>
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
      <a class="navbar-brand" href="mesas.php">Mesero</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMesero" aria-controls="navMesero" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navMesero">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item"><a class="nav-link" href="mesas.php">Mesas</a></li>
          <li class="nav-item"><a class="nav-link" href="pedidos.php">Pedidos</a></li>
        </ul>
        <span class="navbar-text">
          <?= isset($_SESSION['nombre_empleado']) ? htmlspecialchars($_SESSION['nombre_empleado']) : 'Mesero' ?>
        </span>
      </div>
    </div>
  </nav>
  <div class="container my-4">