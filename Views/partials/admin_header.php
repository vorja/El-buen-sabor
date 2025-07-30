<?php
// Asegurar sesión y rol de administrador
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 2) {
    header('Location: ../login.php');
    exit;
}
$baseUrl = '/El-buen-sabor';
?><!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <base href="<?= $baseUrl ?>/">
  <title><?= isset($pageTitle) ? $pageTitle . " | El Buen Sabor" : "Administración | El Buen Sabor" ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
  <link href="assets/css/style.css" rel="stylesheet" />
  <style>
    body { background-color: #f8f9fa; }
    .sidebar {
      background-color: #2d2d2d;
      color: #fff;
      min-height: 100vh;
      width: 240px;
    }
    .sidebar a {
      color: #ccc;
      display: block;
      padding: 0.75rem 1rem;
      text-decoration: none;
      border-radius: 4px;
      transition: background-color 0.15s;
    }
    .sidebar a.active,
    .sidebar a:hover {
      background-color: #444;
      color: #fff;
    }
  </style>
</head>
<body>
<div class="d-flex">
  <nav class="sidebar p-3 d-flex flex-column">
    <h4 class="text-white mb-4">Administrador</h4>
    <a href="Views/admin/dashboard.php" class="<?= (isset($pageTitle) && $pageTitle==='Dashboard') ? 'active' : '' ?>">Dashboard</a>
    <a href="Views/admin/inventario.php" class="<?= (isset($pageTitle) && $pageTitle==='Inventario') ? 'active' : '' ?>">Inventario</a>
    <a href="Views/admin/ventas.php" class="<?= (isset($pageTitle) && $pageTitle==='Ventas') ? 'active' : '' ?>">Ventas</a>
    <a href="Views/admin/reportes.php" class="<?= (isset($pageTitle) && $pageTitle==='Reportes') ? 'active' : '' ?>">Reportes</a>
    <div class="mt-auto">
      <a href="Controllers/LoginController.php?action=logout" class="d-block text-center text-danger py-2">Cerrar sesión</a>
    </div>
  </nav>
  <main class="flex-grow-1 p-4">
