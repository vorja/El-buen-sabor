<?php
// Controllers/ProductoController.php
require_once __DIR__ . '/../Models/Database.php';
require_once __DIR__ . '/../Models/ProductoModel.php';

use Models\ProductoModel;

$accion = $_REQUEST['accion'] ?? '';
switch ($accion) {
  case 'crear':
    // Recoger y sanear
    $data = [
      'nombre'          => trim($_POST['nombre']),
      'categoria_id'    => (int)$_POST['categoria_id'],
      'tipo_inventario' => $_POST['tipo_inventario'],
      'stock'           => $_POST['stock'] !== '' ? (int)$_POST['stock'] : null,
      'disponibilidad'  => $_POST['disponibilidad'],
      'precio_unitario' => (float)$_POST['precio_unitario'],
      'descripcion'     => $_POST['descripcion'] ?? null,
      'imagen'          => null
    ];
    // Procesar imagen si la hay
    if (!empty($_FILES['imagen']['tmp_name'])) {
      $destino = __DIR__ . '/../public/uploads/' . basename($_FILES['imagen']['name']);
      move_uploaded_file($_FILES['imagen']['tmp_name'], $destino);
      $data['imagen'] = 'uploads/' . basename($_FILES['imagen']['name']);
    }
    ProductoModel::crear($data);
    header('Location: ../Views/admin/inventario.php?success=1');
    exit;

  case 'actualizar':
    $id = (int)$_POST['id'];
    $data = [
      'nombre'          => trim($_POST['nombre']),
      'categoria_id'    => (int)$_POST['categoria_id'],
      'tipo_inventario' => $_POST['tipo_inventario'],
      'stock'           => $_POST['stock'] !== '' ? (int)$_POST['stock'] : null,
      'disponibilidad'  => $_POST['disponibilidad'],
      'precio_unitario' => (float)$_POST['precio_unitario'],
      'descripcion'     => $_POST['descripcion'] ?? null,
      'imagen'          => null
    ];
    if (!empty($_FILES['imagen']['tmp_name'])) {
      $destino = __DIR__ . '/../public/uploads/' . basename($_FILES['imagen']['name']);
      move_uploaded_file($_FILES['imagen']['tmp_name'], $destino);
      $data['imagen'] = 'uploads/' . basename($_FILES['imagen']['name']);
    }
    ProductoModel::actualizar($id, $data);
    header('Location: ../Views/admin/inventario.php?updated=1');
    exit;

  case 'borrar':
    $id = (int)$_GET['id'];
    ProductoModel::borrar($id);
    header('Location: ../Views/admin/inventario.php?deleted=1');
    exit;

  default:
    header('Location: ../Views/admin/inventario.php');
    exit;
}
