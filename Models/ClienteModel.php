<?php
// repository/Models/ClienteModel.php
// Modelo para gestionar la tabla `clientes`. Se encarga de crear
// nuevos registros de clientes y consultar sus datos. Implementa
// métodos mínimos necesarios para el flujo de ingreso mediante QR.

namespace Models;

require_once __DIR__ . '/Database.php';
use Models\Database;

class ClienteModel {
    /**
     * Inserta un nuevo cliente en la base de datos.
     *
     * @param string $nombre Nombre completo del cliente.
     * @param string $email  Correo electrónico del cliente.
     * @return int           ID generado para el nuevo cliente.
     */
    public static function crearCliente(string $nombre, string $email): int {
        $sql = "INSERT INTO clientes (nombre, email, creado) VALUES (?, ?, NOW())";
        Database::execute($sql, [ $nombre, $email ]);
        return (int)Database::getConnection()->lastInsertId();
    }

    /**
     * Obtiene los datos de un cliente por su ID.
     *
     * @param int $id Identificador del cliente.
     * @return array|null Arreglo asociativo con los datos o null si no existe.
     */
    public static function obtenerCliente(int $id): ?array {
        $sql = "SELECT * FROM clientes WHERE id = ?";
        return Database::queryOne($sql, [ $id ]);
    }
}