<?php
// Desactivar la salida de errores de PHP
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Asegurar que siempre se envíe JSON
header('Content-Type: application/json');

require_once '../models/MySQL.php';
require_once '../models/Inventario.php';

class InventarioController
{
    //Funcion para manejar las respuestas
    public static function handleResponse($success, $message, $data = [], $httpCode = 200) //en el parametro $data se pasan los DATOS OBTENIDOS
                                                                                           //YO DESPUES USO EL CONTROLADOR Y LOS RENDERIZO
                                                                                           //USTED SOLO CREE EL CONTROLADOR Y EL MODELO         
    {
        http_response_code($httpCode);
        echo json_encode([
            'success' => $success,
            'message' => $message,
            'data' => $data
        ]);
        exit;
    }
     public static function getInventarioPrincipales()
    {
        try {
            $mysql = new MySQL();
            $mysql->connect();
            if (!$mysql->getStatusConexion()) {
                self::handleResponse(false, 'Error al conectar con la base de datos', [], 500);
                return;
            }

            $stats = new Inventario($mysql);
            $data = array_merge(
                $stats->getCantidadProductos(),
                $stats->getProductosStockNormal(),
                $stats->getProductosStockBajo(),
                $stats->getProductosStockCritico()
            );
            self::handleResponse(true, 'Inventario obtenido con éxito', $data);
        } catch (Exception $e) {
            self::handleResponse(false, 'Error al obtener las estadísticas Inventario ' . $e->getMessage(), [], 500);
        }
    }

    public static function getIngredientes()
    {
        try {
            $mysql = new MySQL();
            $mysql->connect();
            if (!$mysql->getStatusConexion()) {
                self::handleResponse(false, 'Error al conectar con la base de datos', [], 500);
                return;
            }

            $estado_stock = isset($_GET['estado_stock']) && $_GET['estado_stock'] !== '' ? $_GET['estado_stock'] : null;
            $search = isset($_GET['search']) && $_GET['search'] !== '' ? $_GET['search'] : null;

            $stats = new Inventario($mysql);
            $data = $stats->getIngredientes($estado_stock, $search);
            
            self::handleResponse(true, 'Inventario obtenido con éxito', $data);
        } catch (Exception $e) {
            self::handleResponse(false, 'Error al obtener las estadísticas Inventario ' . $e->getMessage(), [], 500);
        }
    }

    public static function getInventarioPorCategoriayStock()
    {
        try {
            $mysql = new MySQL();
            $mysql->connect();
            if (!$mysql->getStatusConexion()) {
                self::handleResponse(false, 'Error al conectar con la base de datos', [], 500);
                return;
            }
            $categoria_id = isset($_GET['categoria_id']) && $_GET['categoria_id'] !== '' ? $_GET['categoria_id'] : null;
            $estado_stock = isset($_GET['estado_stock']) && $_GET['estado_stock'] !== '' ? $_GET['estado_stock'] : null;
            $stats = new Inventario($mysql);
            $data = $stats->getInventarioPorCategoriayStock($categoria_id, $estado_stock);
            self::handleResponse(true, 'Inventario filtrado obtenido con éxito', $data);
        } catch (Exception $e) {
            self::handleResponse(false, 'Error al obtener inventario filtrado: ' . $e->getMessage(), [], 500);
        }
    }

    public static function getCategoriasProductos()
    {
        try {
            $mysql = new MySQL();
            $mysql->connect();
            if (!$mysql->getStatusConexion()) {
                self::handleResponse(false, 'Error al conectar con la base de datos', [], 500);
                return;
            }
            $query = "SELECT * FROM categorias";
            $result = $mysql->efectuarConsulta($query);
            $categorias = [];
            if ($result) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $categorias[] = [
                        'id' => $row['id'],
                        'nombre' => $row['nombre']
                    ];
                }
            }
            self::handleResponse(true, 'Categorías obtenidas con éxito', $categorias);
        } catch (Exception $e) {
            self::handleResponse(false, 'Error al obtener categorías: ' . $e->getMessage(), [], 500);
        }
    }

    public static function getProductosPorCategoriaYStock()
    {
        try {
            $mysql = new MySQL();
            $mysql->connect();
            if (!$mysql->getStatusConexion()) {
                self::handleResponse(false, 'Error al conectar con la base de datos', [], 500);
                return;
            }
            $categoria_id = isset($_GET['categoria_id']) && $_GET['categoria_id'] !== '' ? $_GET['categoria_id'] : null;
            $estado_stock = isset($_GET['estado_stock']) && $_GET['estado_stock'] !== '' ? $_GET['estado_stock'] : null;
            $search = isset($_GET['search']) && $_GET['search'] !== '' ? $_GET['search'] : null;
            $where = [];
            if ($categoria_id !== null) {
                $where[] = "p.categoria_id = " . intval($categoria_id);
            }
            if ($estado_stock !== null) {
                if ($estado_stock === 'normal') {
                    $where[] = "ip.stock >= ip.stock_minimo";
                } elseif ($estado_stock === 'bajo') {
                    $where[] = "ip.stock < ip.stock_minimo AND ip.stock > ip.stock_minimo/2";
                } elseif ($estado_stock === 'critico') {
                    $where[] = "ip.stock <= ip.stock_minimo/2";
                }
            }
            if ($search !== null) {
                $escaped_search = $mysql->escape_string($search);
                $where[] = "p.nombre LIKE '%{$escaped_search}%'";
            }
            $where_sql = '';
            if (count($where) > 0) {
                $where_sql = 'WHERE ' . implode(' AND ', $where);
            }
            $query = "SELECT p.id, p.nombre, p.categoria_id, c.nombre AS categoria, ip.stock, ip.stock_minimo, ip.actualizado, ip.precio_unitario FROM productos p LEFT JOIN inventario_producto ip ON p.id = ip.producto_id INNER JOIN categorias c ON p.categoria_id = c.id " . $where_sql . ";";
            $result = $mysql->efectuarConsulta($query);
            $productos = [];
            if ($result) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $productos[] = $row;
                }
            }
            self::handleResponse(true, 'Productos obtenidos con éxito', $productos);
        } catch (Exception $e) {
            self::handleResponse(false, 'Error al obtener productos: ' . $e->getMessage(), [], 500);
        }
    }
}

if (isset($_GET['action']) && $_GET['action'] === 'getCategoriasProductos') {
    InventarioController::getCategoriasProductos();
} else if (isset($_GET['action']) && $_GET['action'] === 'getProductosPorCategoriaYStock') {
    InventarioController::getProductosPorCategoriaYStock();
} else if (isset($_GET['action']) && $_GET['action'] === 'getIngredientes') {
    InventarioController::getIngredientes();
} else if (isset($_GET['action']) && $_GET['action'] === 'getInventarioPrincipales') {
    InventarioController::getInventarioPrincipales();
} else if (isset($_GET['action']) && $_GET['action'] === 'getInventarioPorCategoriayStock') {
    InventarioController::getInventarioPorCategoriayStock();
} else {
    InventarioController::handleResponse(false, 'Acción no válida', [], 400);
}