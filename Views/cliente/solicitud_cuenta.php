<?php
// repository/Views/cliente/solicitud_cuenta.php
// Vista mostrada cuando el cliente solicita la cuenta. Informa que el
// pedido está en proceso de pago y agradece la visita. La sesión
// permanece activa hasta que el mesero cierre la mesa.

session_start();

?><!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Gracias por su visita</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet" />
  <style>
    body { font-family: 'Poppins', sans-serif; }
  </style>
</head>
<body class="bg-light d-flex align-items-center" style="min-height: 100vh;">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-6 text-center">
        <h1 class="mb-4">¡Gracias por su visita!</h1>
        <p class="lead">Su pedido ha sido enviado al personal. Un mesero se acercará a su mesa para procesar el pago en breve.</p>
        <p>Puede cerrar esta ventana si lo desea; su pedido permanece registrado hasta que se realice el pago.</p>
      </div>
    </div>
  </div>
</body>
</html>