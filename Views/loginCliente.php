<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Cafetería — Login Cliente</title>

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
  <link rel="stylesheet" href="../assets/css/loginUsuario.css">
</head>
<body>
  <div class="particles" id="particles"></div>
  <div class="coffee-grains" id="coffeeGrains"></div>

  <div class="container-fluid container-md">
    <div class="row justify-content-center">
      <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-5">
        <div class="login-card animate__animated animate__fadeInDown" id="loginCard">
          <h1 class="login-title text-center mb-4">Bienvenido a Cafetería</h1>
          
          <form action="../controllers/procesar_login_cliente.php" method="POST">
            
            <div class="mb-4">
              <label for="nombre" class="form-label">Nombre Completo</label>
              <input type="text"
                     class="form-control"
                     id="nombre"
                     name="nombre"
                     required
                     placeholder="Tu nombre completo">
              <div class="invalid-feedback">
                Por favor, introduce tu nombre.
              </div>
            </div>
            <div class="mb-4">
              <label for="nombre" class="form-label">Email</label>
              <input type="email"
                     class="form-control"
                     id="email"
                     name="email"
                     required
                     placeholder="Email">
              <div class="invalid-feedback">
                Por favor, introduce tu Email.
              </div>
            </div>
            
            <button type="submit"
                    class="submit-btn w-100 py-3 rounded-pill"
                    id="submitBtn">
              <span class="btn-text">Entrar</span>
              <span class="spinner-border spinner-border-sm ms-2 d-none"
                    role="status"
                    aria-hidden="true"
                    id="spinner"></span>
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="../assets/js/loginUsuario.js"></script>
</body>
</html>
