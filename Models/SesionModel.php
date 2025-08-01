<?php
// repository/Models/SesionModel.php
// Modelo para gestionar las sesiones asociadas a las mesas. Cada
// sesión se crea cuando un cliente inicia el pedido mediante el
// token QR y se cierra cuando el pedido se paga. Gestiona también
// el contador de dispositivos que han escaneado el mismo QR.

namespace Models;

require_once __DIR__ . '/Database.php';
use Models\Database;

class SesionModel {
    /**
     * Crea una nueva sesión de mesa. Guarda el ID del token y del cliente
     * y marca la hora de inicio. Devuelve el ID de la nueva sesión.
     *
     * @param int $qrTokenId ID de la fila en qr_tokens.
     * @param int $clienteId ID del cliente que inicia la sesión.
     * @return int ID de la nueva sesión.
     */
    public static function crearSesion(int $qrTokenId, int $clienteId): int {
        $sql = "INSERT INTO sesiones_mesa (qr_token_id, cliente_id, empieza) VALUES (?, ?, NOW())";
        Database::execute($sql, [ $qrTokenId, $clienteId ]);
        return (int)Database::getConnection()->lastInsertId();
    }

    /**
     * Marca una sesión como cerrada al momento de pagar. Actualiza la
     * columna `cerrada` y la fecha de expiración.
     *
     * @param int $sesionId Identificador de la sesión.
     * @return bool
     */
    public static function cerrarSesion(int $sesionId): bool {
        $sql = "UPDATE sesiones_mesa SET cerrada = 1, expira = NOW() WHERE id = ?";
        return Database::execute($sql, [ $sesionId ]);
    }

    /**
     * Incrementa el contador de dispositivos asociados a una sesión. Esto
     * permite contabilizar cuántos acompañantes han escaneado el mismo QR.
     *
     * @param int $sesionId Identificador de la sesión.
     * @return bool
     */
    public static function incrementarDispositivos(int $sesionId): bool {
        $sql = "UPDATE sesiones_mesa SET dispositivos = dispositivos + 1 WHERE id = ?";
        return Database::execute($sql, [ $sesionId ]);
    }

    /**
     * Obtiene los datos de una sesión por su ID.
     *
     * @param int $sesionId Identificador de la sesión.
     * @return array|null Datos de la sesión o null si no existe.
     */
    public static function obtenerSesion(int $sesionId): ?array {
        $sql = "SELECT * FROM sesiones_mesa WHERE id = ?";
        return Database::queryOne($sql, [ $sesionId ]);
    }
}