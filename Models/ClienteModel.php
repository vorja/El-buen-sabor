<?php
namespace Models;
use Database;

class ClienteModel {
    // Crear un nuevo cliente en la BD
    public static function crearCliente($nombre, $email) {
        $sql = "INSERT INTO clientes (nombre, email, creado) VALUES (?, ?, NOW())";
        Database::execute($sql, [ $nombre, $email ]);
        return Database::getConnection()->lastInsertId();  // devuelve el ID insertado
    }

    // Obtener datos de un cliente por ID
    public static function obtenerCliente($id) {
        $sql = "SELECT * FROM clientes WHERE id = ?";
        return Database::queryOne($sql, [ $id ]);
    }
}
