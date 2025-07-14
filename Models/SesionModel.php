<?php
namespace Models;
use Database;

class SesionModel {
    // Crear nueva sesión de mesa cuando un cliente ingresa (devuelve el ID de sesión nuevo)
    public static function crearSesion($qrTokenId, $clienteId) {
        $sql = "INSERT INTO sesiones_mesa (qr_token_id, cliente_id, empieza) VALUES (?, ?, NOW())";
        Database::execute($sql, [ $qrTokenId, $clienteId ]);
        return Database::getConnection()->lastInsertId();
    }

    // Marcar una sesión como cerrada (p. ej. al pagar)
    public static function cerrarSesion($sesionId) {
        $sql = "UPDATE sesiones_mesa SET cerrada = 1, expira = NOW() WHERE id = ?";
        return Database::execute($sql, [ $sesionId ]);
    }

    // Incrementar el contador de dispositivos de la sesión (cuando acompañantes escanean el mismo QR)
    public static function incrementarDispositivos($sesionId) {
        $sql = "UPDATE sesiones_mesa SET dispositivos = dispositivos + 1 WHERE id = ?";
        return Database::execute($sql, [ $sesionId ]);
    }

    // Obtener sesión por ID (para consultar su estado, mesa, cliente, etc.)
    public static function obtenerSesion($sesionId) {
        $sql = "SELECT * FROM sesiones_mesa WHERE id = ?";
        return Database::queryOne($sql, [ $sesionId ]);
    }
}
