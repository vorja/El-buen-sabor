<?php
// Models/MesaModel.php
namespace Models;

require_once __DIR__ . '/Database.php';
use Models\Database;

class MesaModel {
    /**
     * Genera un token QR válido para una mesa y mesero.
     * El token expira en 4 horas.
     */
    public static function generarTokenQR(int $mesaId, int $meseroId): string {
        $token = bin2hex(random_bytes(32));
        $sql = "INSERT INTO qr_tokens (mesa_id, mesero_id, token, expira, creado) \
              VALUES (?, ?, ?, DATE_ADD(NOW(), INTERVAL 4 HOUR), NOW())";
        Database::execute($sql, [ $mesaId, $meseroId, $token ]);
        return $token;
    }

    /**
     * Obtiene datos del token, verifica expiración.
     */
    public static function obtenerToken(string $token): ?array {
        $sql = "SELECT * FROM qr_tokens WHERE token = ?";
        $row = Database::queryOne($sql, [ $token ]);
        if (!$row) return null;
        if (strtotime($row['expira']) < time()) return null;
        return $row;
    }

    public static function obtenerMesas(): array {
        return Database::queryAll("SELECT * FROM mesas ORDER BY numero");
    }

    public static function actualizarEstadoMesa(int $mesaId, string $estado): bool {
        return Database::execute("UPDATE mesas SET estado = ? WHERE id = ?", [ $estado, $mesaId ]);
    }
}
