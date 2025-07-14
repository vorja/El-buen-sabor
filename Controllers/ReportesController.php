<?php
// Controllers/ReportesController.php

require_once __DIR__ . '/../Models/MySQL.php';

class ReportesController {
    public static function index() {
        session_start();
        if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 2) {
            header('Location: ../Views/login.php');
            exit;
        }

        $mysql = new MySQL();
        $consulta = $mysql->efectuarConsulta(
            "SELECT m.numero AS mesa, c.nombre AS cliente, p.total, p.estado, p.creado
             FROM pedidos p
             JOIN sesiones_mesa sm ON sm.id = p.sesion_id
             JOIN qr_tokens qt ON qt.id = sm.qr_token_id
             JOIN mesas m ON m.id = qt.mesa_id
             JOIN clientes c ON c.id = sm.cliente_id
             WHERE p.estado = 'cerrado'
             ORDER BY p.creado DESC"
        );

        $ventas = [];
        while ($fila = $consulta->fetch_assoc()) {
            $ventas[] = $fila;
        }
        $mysql->cerrarConexion();

        // Renderizar la vista
        require_once __DIR__ . '/../Views/admin/reportes.php';
    }
}

// Enrutamiento simple
$action = $_GET['action'] ?? 'index';
if ($action === 'index') {
    ReportesController::index();
} else {
    header('Location: ../Views/admin/reportes.php');
    exit;
}
