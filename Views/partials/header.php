<?php


$baseUrl = '/El-buen-sabor';

?><!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <base href="<?= $baseUrl ?>/">  <!-- así todos los href relativos parten desde aquí -->
  <title><?= isset($pageTitle) ? $pageTitle . " | El Buen Sabor" : "El Buen Sabor" ?></title>


  <!-- FontAwesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/  all.min.css" rel="stylesheet"/>
  
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <!-- Estilos propios -->
  <link href="assets/css/style.css" rel="stylesheet"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body class="bg-light">
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary-coffee">
    <div class="container-fluid">
      <a class="navbar-brand" href="index.php">
        <i class="fas fa-mug-hot"></i> El Buen Sabor
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
              data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false"
              aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="mainNav">
        <ul class="navbar-nav ms-auto">
          <?php if (isset($_SESSION['rol']) && $_SESSION['rol']==2): ?>
            <li class="nav-item"><a class="nav-link" href="Views/admin/dashboard.php">Dashboard</a></li>
            <li class="nav-item"><a class="nav-link" href="Views/admin/ventas.php">Ventas</a></li>
            <li class="nav-item"><a class="nav-link" href="Views/admin/inventario.php">Inventario</a></li>
            <li class="nav-item"><a class="nav-link" href="Views/admin/reportes.php">Reportes</a></li>
          <?php elseif (isset($_SESSION['rol']) && $_SESSION['rol']==1): ?>
            <li class="nav-item"><a class="nav-link" href="Views/mesero/mesas.php">Mesas</a></li>
            <li class="nav-item"><a class="nav-link" href="Views/mesero/pedido.php">Mis Pedidos</a></li>
          <?php endif; ?>
          <?php if (isset($_SESSION['empleado_id'])): ?>
            <li class="nav-item">
              <a class="nav-link text-warning" href="Controllers/LoginController.php?action=logout">
                <i class="fas fa-sign-out-alt"></i>
              </a>
            </li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </nav>

  <div class="container py-4">
