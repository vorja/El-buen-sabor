<?php
namespace Models;
require_once __DIR__ . '/Database.php';
use Models\Database;

class MesaModel {
    // Obtener todas las mesas (o las de cierto mesero si se aplica asignación estática)
    public static function obtenerMesas() {
        $sql = "SELECT * FROM mesas ORDER BY id";
        return Database::queryAll($sql);
    }

    // Cambiar estado de una mesa (libre, ocupada, etc.)
    public static function actualizarEstadoMesa($mesaId, $nuevoEstado) {
        $sql = "UPDATE mesas SET estado = ? WHERE id = ?";
        return Database::execute($sql, [ $nuevoEstado, $mesaId ]);
    }

    // Generar un nuevo token QR para una mesa y retornar el token generado
    public static function generarTokenQR($mesaId, $meseroId) {
        // Generar token aleatorio de 64 caracteres (32 bytes hex) 
        $token = bin2hex(random_bytes(32));
        $sql = "INSERT INTO qr_tokens (mesa_id, token, mesero_id, creado) VALUES (?, ?, ?, NOW())";
        Database::execute($sql, [ $mesaId, $token, $meseroId ]);
        return $token;
    }

    // Obtener datos de un token específico
    public static function obtenerToken($token) {
        $sql = "SELECT * FROM qr_tokens WHERE token = ?";
        return Database::queryOne($sql, [ $token ]);
    }
}
