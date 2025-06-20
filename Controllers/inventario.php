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

            $stats = new Inventario($mysql);
            $data = array_merge(
                $stats->getIngredientes(),
             
            );
            self::handleResponse(true, 'Inventario obtenido con éxito', $data);
        } catch (Exception $e) {
            self::handleResponse(false, 'Error al obtener las estadísticas Inventario ' . $e->getMessage(), [], 500);
        }
    }
}

if (isset($_GET['action']) && $_GET['action'] === 'getInventarioPrincipales') {
    InventarioController::getInventarioPrincipales();
} else if (isset($_GET['action']) && $_GET['action'] === 'getIngredientes') {
    InventarioController::getIngredientes();
} else {
    InventarioController::handleResponse(false, 'Acción no válida', [], 400);
}