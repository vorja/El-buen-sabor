<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Cafetería — Login</title>

  <!-- Precarga de fuentes críticas -->
  <link rel="preload" href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700&display=swap" as="style">
  <link rel="preload" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500&display=swap" as="style">
  <!-- Carga de fuentes -->
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">
  <!-- Bootstrap y Animate.css -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
  <!-- Tu CSS personalizado -->
  <link rel="stylesheet" href="../assets/css/login.css">
</head>
<body>
  <div class="particles" id="particles"></div>
  <div class="coffee-grains" id="coffeeGrains"></div>

  <div class="container-fluid container-md">
    <div class="row justify-content-center">
      <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-5">
        <div class="login-card animate__animated animate__fadeInDown" id="loginCard">
          <h1 class="login-title text-center mb-4">Bienvenido a Cafetería</h1>
          
          <form id="loginForm"
                action="../controllers/procesar_login_empleado.php"
                method="POST"
                novalidate>
            
            <div class="mb-4">
              <label for="email" class="form-label">Email</label>
              <input type="email"
                     class="form-control"
                     id="email"
                     name="correo"
                     required
                     placeholder="tucorreo@ejemplo.com">
              <div class="invalid-feedback">
                Por favor, introduce un email válido.
              </div>
            </div>
            
            <div class="mb-4">
              <label for="password" class="form-label">Contraseña</label>
              <input type="password"
                     class="form-control"
                     id="password"
                     name="password"
                     required
                     placeholder="••••••••">
              <div class="invalid-feedback">
                La contraseña es obligatoria.
              </div>
            </div>
            
            <div class="mb-4 form-check">
              <input type="checkbox"
                     class="form-check-input"
                     id="rememberMe"
                     name="rememberMe">
              <label class="form-check-label" for="rememberMe">Recordarme</label>
            </div>
            
            <button type="submit"
                    class="submit-btn w-100 py-3 rounded-pill"
                    id="submitBtn">
              Iniciar Sesión
            </button>
          </form>
          
          <a href="#"
             class="recover-link d-block text-center mt-3"
             id="recoverBtn">
            Recuperar Contraseña
          </a>
          
          <div class="login-error text-danger text-center mt-3 d-none" id="loginError">
            Usuario o contraseña incorrectos. Por favor, inténtalo de nuevo.
          </div>
        </div>

        <div class="welcome-message animate__animated d-none" id="welcomeMessage">
          <h2>¡Hola, <span id="userName">Usuario</span>!</h2>
          <p>Bienvenido a nuestra cafetería virtual. Disfruta de tu visita.</p>
        </div>
      </div>
    </div>
  </div>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="../assets/js/login.js"></script>
</body>
</html>
