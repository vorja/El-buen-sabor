<?php
// repository/Models/MesaModel.php
// Modelo para gestionar las mesas y los tokens QR asociados. Permite
// obtener el listado de mesas, actualizar su estado y gestionar los
// tokens utilizados para identificar la mesa cuando un cliente
// escanea el código QR.

namespace Models;

require_once __DIR__ . '/Database.php';
use Models\Database;

class MesaModel {
    /**
     * Genera un token único para una mesa. Este token se inserta en la
     * tabla `qr_tokens` con una expiración de 4 horas. No asigna
     * automáticamente un mesero porque la tabla qr_tokens del esquema
     * base no tiene esa columna. El ID del mesero se pasa como parámetro
     * en la URL del QR.
     *
     * @param int $mesaId Identificador de la mesa.
     * @param int $meseroId Identificador del mesero.
     * @return string Token generado.
     */
    public static function generarTokenQR(int $mesaId, int $meseroId): string {
        $token = bin2hex(random_bytes(32));
        // La tabla qr_tokens contiene: mesa_id, token, expira, creado
        $sql = "INSERT INTO qr_tokens (mesa_id, token, expira, creado) "
             . "VALUES (?, ?, DATE_ADD(NOW(), INTERVAL 4 HOUR), NOW())";
        Database::execute($sql, [ $mesaId, $token ]);
        return $token;
    }

    /**
     * Obtiene los datos de un token QR y verifica que no haya expirado.
     * Devuelve null si el token no existe o está expirado.
     *
     * @param string $token Token QR a buscar.
     * @return array|null Datos de la fila de qr_tokens.
     */
    public static function obtenerToken(string $token): ?array {
        $row = Database::queryOne("SELECT * FROM qr_tokens WHERE token = ?", [ $token ]);
        if (!$row) return null;
        // Verificar expiración
        if (strtotime($row['expira']) < time()) {
            return null;
        }
        return $row;
    }

    /**
     * Devuelve el listado de mesas ordenado por número.
     *
     * @return array Arreglo de mesas.
     */
    public static function obtenerMesas(): array {
        return Database::queryAll("SELECT * FROM mesas ORDER BY numero");
    }

    /**
     * Actualiza el estado de una mesa (libre u ocupada).
     *
     * @param int $mesaId Identificador de la mesa.
     * @param string $estado Nuevo estado ('libre'/'ocupada').
     * @return bool
     */
    public static function actualizarEstadoMesa(int $mesaId, string $estado): bool {
        return Database::execute("UPDATE mesas SET estado = ? WHERE id = ?", [ $estado, $mesaId ]);
    }
}