<?php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 1) {
    header("Location: ../login.php");
    exit;
}
require_once __DIR__ . '/../../Models/MesaModel.php';
use Models\MesaModel;

$mesas = MesaModel::obtenerMesas();
// Si se ha generado un token QR y se incluye en la URL, obténlo para mostrar el modal
$tokenGenerado = $_GET['token'] ?? null;
$mesaSeleccionadaId = $_GET['mesa'] ?? null;
$mesaSeleccionadaNumero = null;
if ($tokenGenerado && $mesaSeleccionadaId) {
    // Buscar el número de mesa correspondiente al ID seleccionado
    foreach ($mesas as $mx) {
        if ($mx['id'] == $mesaSeleccionadaId) {
            $mesaSeleccionadaNumero = $mx['numero'];
            break;
        }
    }
}
// Título de la página para el header
$pageTitle = "Mesas";
require_once __DIR__ . '/../partials/header_mesero.php';
?>
<div class="container py-5">
  <h1 class="text-center mb-4" style="color:#a38672;">Selecciona Mesa</h1>
  <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">
    <?php foreach ($mesas as $m): ?>
      <div class="col">
        <div class="mesa-card h-100 d-flex flex-column justify-content-between">
          <div>
            <i class="fas fa-table fa-3x"></i>
            <h4 class="mt-2" style="color:#5b4534;">Mesa <?= htmlspecialchars($m['numero']) ?></h4>
          </div>
          <?php if ($m['estado'] === 'libre'): ?>
            <button
              class="btn-coffee"
              onclick="location.href='Controllers/MeseroController.php?accion=generar_qr&mesa=<?= $m['id'] ?>'">
              Seleccionar
            </button>
          <?php else: ?>
            <button class="btn-coffee" disabled>
              Ocupada
            </button>
          <?php endif; ?>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>
<?php require_once __DIR__ . '/../partials/footer_mesero.php'; ?>

<?php if ($tokenGenerado && $mesaSeleccionadaId && $mesaSeleccionadaNumero): ?>
  <!-- Modal para mostrar el código QR al mesero -->
  <div class="modal fade" id="qrModal" tabindex="-1" aria-labelledby="qrModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="qrModalLabel">Código QR para Mesa <?= htmlspecialchars($mesaSeleccionadaNumero) ?></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body text-center">
          <p>Solicite a su cliente que escanee este código QR para ingresar sus datos y comenzar el pedido.</p>
          <?php
            /*
             * Construir la URL absoluta que debe contener el código QR.
             * En el código original se utilizaba una ruta relativa (Views/cliente/loginCliente.php)
             * y un servicio externo para generar la imagen del QR. Eso ocasionaba
             * que, al escanear el QR desde un dispositivo móvil, el enlace no se
             * resolviera correctamente porque faltaba el dominio. Aquí obtenemos
             * el protocolo (http o https), el host (dominio/IP) y concatenamos
             * con la ruta base del proyecto para formar una URL completa. De esta
             * manera, cualquier dispositivo podrá abrir el enlace generado.
             */
            // ID del mesero que genera el QR. Se utiliza para asociar la sesión al mesero.
            $meseroId = $_SESSION['empleado_id'] ?? 0;

            // Determinar protocolo y dominio
            $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
            $host     = $_SERVER['HTTP_HOST'];
            // Calcular la ruta base de la aplicación de forma dinámica a partir del nombre del script.
            // $_SERVER['SCRIPT_NAME'] devuelve la ruta del script actual (por ejemplo,
            // '/El-buen-sabor/Views/mesero/mesas.php'). Utilizando dirname() varias
            // veces podemos subir niveles para quedarnos con la carpeta raíz del proyecto
            // (en este caso '/El-buen-sabor'). De esta forma evitamos problemas de
            // mayúsculas/minúsculas o cambios en el nombre del directorio.
            $scriptName = $_SERVER['SCRIPT_NAME'];
            // Subimos tres niveles: mesas.php -> mesero -> Views -> El-buen-sabor
            $base       = dirname(dirname(dirname($scriptName)));
            // Asegurar que termine con una barra antes de concatenar rutas
            $base       = rtrim($base, '/');
            // Construir enlace absoluto para el QR
            $qrLink   = $protocol . $host . $base . '/Views/cliente/loginCliente.php?token=' . urlencode($tokenGenerado) . '&mesero=' . urlencode($meseroId);

            // Generar el código QR mediante un servicio externo. Se construye la URL
            // de la API de qrserver pasando como datos la URL completa que debe
            // contener el código. De esta forma no dependemos de la librería
            // PHPQrcode y simplificamos la generación.
            $qrImg  = 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' . urlencode($qrLink);
          ?>
          <img src="<?php echo $qrImg; ?>" alt="Código QR" class="img-fluid mb-3" />
          <!-- Mostrar el enlace completo para referencia. Esto facilita la verificación durante el desarrollo
               pero también sirve de respaldo en caso de que el cliente no pueda escanear el QR. -->
          <p class="small">Enlace: <a href="<?php echo htmlspecialchars($qrLink); ?>" target="_blank"><?php echo htmlspecialchars($qrLink); ?></a></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>
  <script>
    // Mostrar automáticamente el modal cuando se ha generado un token
    document.addEventListener('DOMContentLoaded', function () {
      var qrModal = new bootstrap.Modal(document.getElementById('qrModal'));
      qrModal.show();
    });
  </script>
<?php endif; ?>
