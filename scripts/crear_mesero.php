<?php
// scripts/crear_mesero.php

require_once __DIR__ . '/../Models/Database.php';
require_once __DIR__ . '/../Models/EmpleadoModel.php';

// Datos del mesero a crear:
$nombre    = 'Juan Mesero';
$email     = 'mesero@ejemplo.com';
$password  = 'mesero123';  // contraseña de prueba
$rolId     = 1;            // 1 = mesero

$ok = Models\EmpleadoModel::crearEmpleado($nombre, $email, $password, $rolId);
if ($ok) {
    echo "✅ Mesero creado: $email / $password";
} else {
    echo "❌ Error al crear el mesero. ¿Revisaste que no exista ya un email igual?";
}
