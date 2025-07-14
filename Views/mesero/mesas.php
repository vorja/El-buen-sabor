<!-- Views/mesero/mesas.php -->
<?php 
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 1) {
    header("Location: ../login.php");
    exit;
}
require_once __DIR__ . '/../../Models/MesaModel.php';
// Suponiendo que cada mesero puede ver todas sus mesas; 
// Si la asignación de mesas es fija por mesero, podríamos filtrar.
$mesas = Models\MesaModel::obtenerMesas();
$tokenGenerado = $_GET['token'] ?? null;
$mesaToken = $_GET['mesa'] ?? null;
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Mesero - Mesas</title>
  <link rel="stylesheet" href="../../assets/css/mesero.css" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
</head>
<body>
  <div class="container">
    <h1>Mis Mesas</h1>
    <div class="mesas-grid">
      <?php foreach ($mesas as $mesa): 
            // Si existiera un filtro de asignación, aquí comprobaríamos que la mesa corresponde al mesero.
            $estado = $mesa['estado']; ?>
        <div class="mesa-card <?= ($estado == 'ocupada') ? 'ocupada' : 'libre'; ?>">
          <div class="mesa-numero">Mesa <?= htmlspecialchars($mesa['numero']) ?></div>
          <?php if ($estado == 'ocupada'): ?>
            <p>Estado: <strong>Ocupada</strong></p>
            <a href="pedido.php?mesa=<?= $mesa['id'] ?>" class="btn btn-info">Ver Pedido</a>
          <?php elseif ($estado == 'libre'): ?>
            <p>Estado: Libre</p>
            <a href="../../Controllers/MeseroController.php?accion=generar_qr&mesa=<?= $mesa['id'] ?>" class="btn btn-primary">Generar QR</a>
          <?php else: ?>
            <p>Estado: <?= htmlspecialchars($estado) ?></p>
            <?php if ($estado == 'fuera_servicio'): ?>
              <button class="btn btn-secondary" disabled>Fuera de servicio</button>
            <?php endif; ?>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    </div>

    <!-- Modal/Sección para mostrar QR generado -->
    <?php if ($tokenGenerado && $mesaToken): ?>
    <div id="qrModal" class="qr-modal">
      <h2>QR Mesa <?= htmlspecialchars($mesaToken) ?></h2>
      <p>Escanee este código QR con el cliente para iniciar pedido:</p>
      <div id="qrcode"></div>
      <button onclick="document.getElementById('qrModal').style.display='none'">Cerrar</button>
    </div>
    <script>
      // Generar el código QR a partir del token
      var qrToken = "<?= htmlspecialchars($tokenGenerado) ?>";
      var qrUrl = "<?php 
          // Construir la URL absoluta para el cliente
          $host = $_SERVER['HTTP_HOST'];
          $path = dirname($_SERVER['REQUEST_URI'], 3); // subir 3 niveles (desde /Views/mesero/mesas.php)
          echo (isset($_SERVER['HTTPS'])?'https':'http') . '://' . $host . $path . '/Views/cliente/loginCliente.php?token='; 
      ?>";
      qrUrl += qrToken;
      var qrcode = new QRCode(document.getElementById("qrcode"), {
          text: qrUrl,
          width: 200,
          height: 200
      });
    </script>
    <?php endif; ?>
  </div>
</body>
</html>
