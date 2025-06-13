<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - El Buen Sabor</title>
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700&display=swap" as="style">
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500&display=swap" as="style">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="../assets/css/register.css">
</head>
<body>
    <div class="particles" id="particles"></div>
    <div class="coffee-grains" id="coffeeGrains"></div>

    <div class="container-fluid container-md">
        <div class="row justify-content-center">
            <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-5">
                <div class="login-card animate__animated" id="registrationCard">
                    <h1 class="login-title">Registro en El Buen Sabor</h1>
                    
                    <form id="customer-form" class="needs-validation" novalidate method="post" action="../Controllers/registro.php">
                        <div class="form-group">
                            <label for="email" class="form-label">Correo Electrónico</label>
                            <div class="input-group">
                                <span class="input-group-text">@</span>
                                <input type="email" id="email" name="email" placeholder="ejemplo@correo.com" 
                                       class="form-control" required>
                            </div>
                            <div id="email-error" class="error-message invalid-feedback"></div>
                        </div>
                        
                        <div class="form-group">
                            <label for="fullname" class="form-label">Nombre Completo</label>
                            <div class="input-group">
                                <span class="input-group-text">👤</span>
                                <input type="text" id="fullname" name="fullname" placeholder="Juan Pérez Gómez" 
                                       class="form-control" required>
                            </div>
                            <div id="fullname-error" class="error-message invalid-feedback"></div>
                        </div>
                        
                        <div class="form-group">
                            <label for="phone" class="form-label">Teléfono</label>
                            <div class="input-group">
                                <span class="input-group-text">📱</span>
                                <input type="tel" id="phone" name="phone" placeholder="3101234567" 
                                       class="form-control" required>
                            </div>
                            <div id="phone-error" class="error-message invalid-feedback"></div>
                        </div>
                        
                        <div class="form-group">
                            <label for="cedula" class="form-label">Cédula de Ciudadanía</label>
                            <div class="input-group">
                                <span class="input-group-text">🪪</span>
                                <input type="text" id="cedula" name="cedula" placeholder="1234567890" 
                                       class="form-control" required>
                            </div>
                            <div id="cedula-error" class="error-message invalid-feedback"></div>
                        </div>

                        <div class="form-group">
                            <label for="contraseña" class="form-label">Contraseña</label>
                            <div class="input-group">
                                <span class="input-group-text">🔒</span>
                                <input type="password" id="contraseña" name="contraseña" placeholder="********" 
                                       class="form-control" required>
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                    👁️
                                </button>
                            </div>
                            <div id="contraseña-error" class="error-message invalid-feedback"></div>
                        </div>
                        
                        <button type="submit" class="submit-btn w-100" id="submitBtn">
                            <span class="btn-text">☕ Registrarse</span>
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        </button>
                    </form>
                </div>

                <div class="welcome-message animate__animated" id="welcomeMessage" style="display: none;">
                    <h2>¡Bienvenido, <span id="userNameDisplay"></span>!</h2>
                    <p>Tu registro ha sido exitoso. ¡Disfruta de nuestros servicios!</p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/register.js"></script>
</body>
</html>