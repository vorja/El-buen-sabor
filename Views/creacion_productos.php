
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>El buen Sabor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/estilo_nav.css">
    <link rel="stylesheet" href="../assets/css/estilo_creacion.css">
</head>
<body>

<!-- Botón para abrir sidebar en móvil -->
<button class="btn btn-outline-secondary d-lg-none m-3" id="mobileSidebarToggle">
    <i class="bi bi-list"></i>
</button>

<div class="d-flex">
    
    <!-- Contenido principal -->
    <div class="flex-grow-1 p-4 main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="mb-0" >Creacion de productos</h1>
        </div>


        <!-- Formulario de PRODUCTO -->
        <div id="form-producto" style="display: block; max-width: 100%;">
            <div class="container my-5">
                <h2 class="mb-4 text-center">Crear nuevo producto</h2>
                <form action="../controllers/crear_productos.php" method="POST" enctype="multipart/form-data" class="form-container">
                    
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre del producto</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" title="Solo letras y espacios" pattern="^[0-9a-zA-ZÁÉÍÓÚáéíóúÑñ\s.:]+$" required>
                    </div>

                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" title="Solo letras y espacios" pattern="^[0-9a-zA-ZÁÉÍÓÚáéíóúÑñ\s.:]+$" rows="3" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="precio" class="form-label">Precio ($)</label>
                        <input type="number" class="form-control" id="precio" name="precio" step="0.01" min="1" required>
                    </div>

                    <div class="mb-3">
                        <label for="stock" class="form-label">Stock disponible</label>
                        <input type="number" class="form-control" id="stock" name="stock" min="1" required>
                    </div>
                    
                    <!--ingredientes-->
                    
                    <!--fin ingredientes-->
                    
                    <div class="mb-4">
                        <label for="imagen" class="form-label">Imagen del producto</label>
                        <input class="form-control" type="file" id="imagen" name="imagen" accept=".jpg, .jpeg, .png" required>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-outline-success px-5">Crear producto</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Formulario de ARTÍCULO -->
        <div id="form-articulo" style="display: none;">
            <div class="container my-5">
                <h2 class="mb-4 text-center">Crear nuevo artículo</h2>
                <form action="../controllers/crear_articulos.php" method="POST" enctype="multipart/form-data" class="form-container">
                    
                    <div class="mb-3">
                        <label for="titulo" class="form-label">Título del artículo</label>
                        <input type="text" class="form-control" id="titulo" name="titulo" title="Solo letras y espacios" pattern="^[0-9a-zA-ZÁÉÍÓÚáéíóúÑñ\s.:]+$" required>
                    </div>

                    <div class="mb-3">
                        <label for="contenido" class="form-label">Contenido</label>
                        <textarea class="form-control" id="contenido" name="contenido" title="Solo letras y espacios" pattern="^[0-9a-zA-ZÁÉÍÓÚáéíóúÑñ\s.:]+$" rows="6" required></textarea>
                    </div>

                    <div class="mb-4">
                        <label for="imagen" class="form-label">Imagen de portada</label>
                        <input class="form-control" type="file" id="imagen" name="imagen" accept=".jpg, .jpeg, .png" required>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-outline-success px-5">Crear artículo</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

</body>
</html>