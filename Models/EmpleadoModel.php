<?php
namespace Models;
require_once __DIR__ . '/Database.php';
use Models\Database;

class EmpleadoModel {
    // Verifica credenciales de un empleado (mesero o admin) por correo y contraseña
    public static function verificarLogin($correo, $password) {
        $sql = "SELECT id, nombre, email, password, rol_id 
                FROM empleados 
                WHERE email = ?";
        $empleado = Database::queryOne($sql, [ $correo ]);
        if ($empleado && password_verify($password, $empleado['password'])) {
            // Login exitoso
            return $empleado;  // devuelve array con datos del empleado
        }
        return false;
    }

    // Obtener todos los meseros (rol_id = 1) – para asignar a mesas, etc.
    public static function obtenerMeseros() {
        $sql = "SELECT id, nombre, email FROM empleados WHERE rol_id = 1";
        return Database::queryAll($sql);
    }

    // (Opcional) Crear nuevo empleado (mesero o admin)
    public static function crearEmpleado($nombre, $email, $passwordPlano, $rolId) {
        $hash = password_hash($passwordPlano, PASSWORD_DEFAULT);
        $sql = "INSERT INTO empleados (rol_id, nombre, email, password, creado) 
                VALUES (?, ?, ?, ?, NOW())";
        return Database::execute($sql, [ $rolId, $nombre, $email, $hash ]);
    }
}
