<?php
// Desactivar la salida de errores de PHP
error_reporting(0);
ini_set('display_errors', 0);

// Asegurar que siempre se envíe JSON
header('Content-Type: application/json');

require_once '../models/MySQL.php';
require_once '../models/dashboard.php';

class StatsController
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
     public static function getEstadisticasPrincipales()
    {
        try {
            $mysql = new MySQL();
            $mysql->connect();
            if (!$mysql->getStatusConexion()) {
                self::handleResponse(false, 'Error al conectar con la base de datos', [], 500);
                return;
            }

            $stats = new Stats($mysql);
            $data = [];
            $data = array_merge(
                $stats->getVentasHoy(),
                $stats->getPedidosActivos(),
                $stats->getClientesAtendidos(),
                $stats->getProductoPopular(),
                $stats->getVentasHoyComparado(),
                $stats->getPedidosHoy(),

                
            );
            self::handleResponse(true, 'Estadísticas obtenidas con éxito', $data);
        } catch (Exception $e) {
            self::handleResponse(false, 'Error al obtener las estadísticas dashboard ' . $e->getMessage(), [], 500);
        }
    }
}

if (isset($_GET['action']) && $_GET['action'] === 'getEstadisticasPrincipales') {
    StatsController::getEstadisticasPrincipales();
} else {
    StatsController::handleResponse(false, 'Acción no válida', [], 400);
}