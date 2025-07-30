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
      // Directorio de subida dentro de assets/img/productos
      $uploadDir = __DIR__ . '/../assets/img/productos/';
      if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
      }
      $extension = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
      $fileName = uniqid('prod_') . '.' . $extension;
      $destino = $uploadDir . $fileName;
      move_uploaded_file($_FILES['imagen']['tmp_name'], $destino);
      // Guardar ruta relativa para mostrar
      $data['imagen'] = 'assets/img/productos/' . $fileName;
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
    // Procesar imagen si la hay, si no mantener imagen actual
    if (!empty($_FILES['imagen']['tmp_name'])) {
      $uploadDir = __DIR__ . '/../assets/img/productos/';
      if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
      }
      $extension = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
      $fileName = uniqid('prod_') . '.' . $extension;
      $destino = $uploadDir . $fileName;
      move_uploaded_file($_FILES['imagen']['tmp_name'], $destino);
      $data['imagen'] = 'assets/img/productos/' . $fileName;
    } else {
      // Mantener la imagen existente si no se sube una nueva
      // Utilizar el modelo Database fuera de espacio de nombres
      $existing = \Models\Database::queryOne("SELECT imagen FROM productos WHERE id = ?", [ $id ]);
      $data['imagen'] = $existing['imagen'];
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
