<?php
// repository/Models/Database.php
// A very small PDO wrapper used by the rest of the application.  This
// implementation is copied from the upstream project so that the rest of
// the models can run without modification.  If you need to change the
// connection parameters, update the properties below.

namespace Models;

use PDO;
use PDOException;

class Database {
    private static ?PDO $pdo = null;
    private static string $host = '127.0.0.1';
    private static string $db   = 'elbuensabor';
    private static string $user = 'root';
    private static string $pass = '';

    /**
     * Returns a shared PDO instance.  The connection is lazily
     * initialised the first time this method is called.  Subsequent
     * calls return the previously created PDO instance.
     */
    public static function getConnection(): PDO {
        if (self::$pdo === null) {
            $dsn = 'mysql:host=' . self::$host . ';dbname=' . self::$db . ';charset=utf8';
            try {
                self::$pdo = new PDO($dsn, self::$user, self::$pass, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                ]);
            } catch (PDOException $e) {
                // in production you might want to log this instead of
                // dumping it to the user – dying here prevents the rest
                // of the application from executing if the DB is
                // misconfigured.
                die('Error de conexión a BD: ' . $e->getMessage());
            }
        }
        return self::$pdo;
    }

    /**
     * Executes a SELECT query and returns all results as an array of
     * associative arrays.  Parameters may be passed to the prepared
     * statement using the optional $params argument.
     */
    public static function queryAll(string $sql, array $params = []): array {
        $stmt = self::getConnection()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Executes a SELECT query and returns the first row or null if no
     * rows were returned.  Parameters may be passed to the prepared
     * statement using the optional $params argument.
     */
    public static function queryOne(string $sql, array $params = []): ?array {
        $stmt = self::getConnection()->prepare($sql);
        $stmt->execute($params);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    /**
     * Executes an INSERT/UPDATE/DELETE statement.  Returns true on
     * success, false on failure.
     */
    public static function execute(string $sql, array $params = []): bool {
        $stmt = self::getConnection()->prepare($sql);
        return $stmt->execute($params);
    }
}