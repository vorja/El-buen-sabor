<?php
namespace Models;

use PDO;
use PDOException;

class Database {
    private static $pdo = null;
    private static $host = '127.0.0.1';
    private static $db   = 'elbuensabor';
    private static $user = 'root';
    private static $pass = '';

    public static function getConnection() {
        if (self::$pdo === null) {
            $dsn = "mysql:host=".self::$host.";dbname=".self::$db.";charset=utf8";
            try {
                self::$pdo = new PDO($dsn, self::$user, self::$pass, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                ]);
            } catch (PDOException $e) {
                die("Error de conexión: " . $e->getMessage());
            }
        }
        return self::$pdo;
    }

    public static function queryOne($sql, $params = []) {
        $stmt = self::getConnection()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function queryAll($sql, $params = []) {
        $stmt = self::getConnection()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function execute($sql, $params = []) {
        $stmt = self::getConnection()->prepare($sql);
        return $stmt->execute($params);
    }
}
