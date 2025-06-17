<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cafeteria</title>
    <!-- Precargar fuentes críticas -->
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700&display=swap" as="style">
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500&display=swap" as="style">
    <!-- Cargar fuentes con display swap -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="../assets/css/login.css">
<body>
  <div class="particles" id="particles"></div>
  <div class="coffee-grains" id="coffeeGrains"></div>

  <div class="container-fluid container-md">
    <div class="row justify-content-center">
      <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-5">
        <div class="login-card" id="loginCard">
          <h1 class="login-title">Bienvenido a Cafetería</h1>
          
          <form id="loginForm" method="POST" action="../controllers/procesar_login_empleado.php">
  <div class="mb-3">
    <label for="email" class="form-label">Email</label>
    <input
      type="email"
      class="form-control"
      id="correo"
      name="correo"
      required
      placeholder="tucorreo@ejemplo.com"
    >
    <div class="invalid-feedback">
      Por favor, introduce un email válido.
    </div>
  </div>
  <div class="mb-3">
    <label for="password" class="form-label">Contraseña</label>
    <input
      type="password"
      class="form-control"
      id="password"
      name="password"
      required
      placeholder="••••••••"
    >
    <div class="invalid-feedback">
      La contraseña es obligatoria.
    </div>
  </div>
  <div class="mb-3 form-check">
    <input type="checkbox" class="form-check-input" id="rememberMe">
    <label class="form-check-label" for="rememberMe">Recordarme</label>
  </div>
  <button type="submit" class="submit-btn" id="submitBtn">
    <span class="btn-text">Iniciar Sesión</span>
    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
  </button>
</form>

          <a href="#" class="recover-link" id="recoverBtn">Recuperar Contraseña</a>
          
          <div class="login-error" id="loginError">
            Usuario o contraseña incorrectos. Por favor, inténtalo de nuevo.
          </div>
        </div>

        <div class="welcome-message animate__animated" id="welcomeMessage">
          <h2>¡Hola, <span id="userName">Usuario</span>!</h2>
          <p>Bienvenido a nuestra cafetería virtual. Disfruta de tu visita.</p>
        </div>
      </div>
    </div>
  </div>
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="../assets/js/login.js"></script>
</body>
</html>