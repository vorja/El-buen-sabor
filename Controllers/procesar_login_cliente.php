<?php
require_once __DIR__ . '/../models/MySQL.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../views/logincliente.php');
    exit;
}

$nombre = trim($_POST['nombre'] ?? '');
if ($nombre === '') {
    header('Location: ../views/logincliente.php?error=no_data');
    exit;
}

$email = trim($_POST['email'] ?? '');
if ($nombre === '') {
    header('Location: ../views/logincliente.php?error=no_data');
    exit;
}


$mysql = new MySQL();
$conn  = $mysql->getConexion();

$stmt = $conn->prepare("
    INSERT INTO clientes (nombre, email, creado)
    VALUES (?, ?, NOW())
");
$stmt->bind_param('ss', $nombre, $email);

if ($stmt->execute()) {
    $_SESSION['cliente_id'] = $stmt->insert_id;
    header('Location: ../index.php');
    exit;
} else {
    error_log('Error al registrar cliente: ' . $stmt->error);
    header('Location: ../views/logincliente.php?error=db');
    exit;
}

