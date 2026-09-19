<?php
require_once 'middleware/AuthMiddleware.php';
require_once 'models/Articulo.php';

AuthMiddleware::verificar();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $categoria = trim($_POST['categoria'] ?? '');
    $marca = trim($_POST['marca'] ?? '');
    $precio =
        filter_var(
            $_POST['precio'] ?? null,
            FILTER_VALIDATE_FLOAT
        );
    $stock =
        filter_var(
            $_POST['stock'] ?? null,
            FILTER_VALIDATE_INT
        );

    if ($nombre === '' || $categoria === '') {
        $error = 'Nombre y categoría son obligatorios.';
    } elseif ($precio === false || $precio < 0) {
        $error = 'El precio no es válido.';
    } elseif ($stock === false || $stock < 0) {
        $error = 'El stock no es válido.';
    } else {
        try {
            $articuloModel = new Articulo();

            $articuloModel->crear(
                $nombre,
                $descripcion,
                $categoria,
                (float) $precio,
                (int) $stock,
                $marca
            );

            header(
                'Location: articulos.php?mensaje=' .
                urlencode(
                    'Artículo creado correctamente.'
                )
            );

            exit;
        } catch (Exception $e) {
            $error = 'No se pudo crear el artículo.';
        }
    }
}

$titulo = 'Nuevo artículo';

require 'views/layouts/header.php';
?>

<div class="form-container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>
            Nuevo artículo
        </h1>
        <a href="articulos.php" class="btn btn-secondary">
            Volver
        </a>
    </div>

    <?php if ($error): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars(
                $error,
                ENT_QUOTES,
                'UTF-8'
            ) ?>
        </div>
    <?php endif; ?>
    <div class="card shadow">
        <div class="card-body p-4">

            <form action="crear_articulo.php" method="POST">
                <div class="mb-3">
                    <label for="nombre" class="form-label">
                        Nombre *
                    </label>
                    <input
                        type="text"
                        class="form-control"
                        id="nombre"
                        name="nombre"
                        maxlength="150"
                        value="<?= htmlspecialchars(
                            $_POST['nombre'] ?? '',
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>"
                        required>
                </div>
                <div class="mb-3">
                    <label for="descripcion" class="form-label">
                        Descripción
                    </label>
                    <textarea
                        class="form-control"
                        id="descripcion"
                        name="descripcion"
                        rows="4"
                    ><?= htmlspecialchars(
                        $_POST['descripcion'] ?? '',
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?></textarea>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="categoria" class="form-label">
                            Categoría *
                        </label>
                        <select
                            class="form-select"
                            id="categoria"
                            name="categoria"
                            required>

                            <option value="">
                                Seleccione...
                            </option>

                            <?php
                            $categorias = [
                                'Laptop',
                                'Smartphone',
                                'Tablet',
                                'Monitor',
                                'Componentes',
                                'Accesorios',
                                'Impresora'
                            ];

                            foreach ($categorias as $categoriaOpcion): ?>
                                <option
                                    value="<?= htmlspecialchars(
                                        $categoriaOpcion,
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>"
                                    <?= (
                                        ($_POST['categoria'] ?? '')
                                        ===
                                        $categoriaOpcion
                                    )
                                        ? 'selected'
                                        : ''
                                    ?>
                                >
                                    <?= htmlspecialchars(
                                        $categoriaOpcion,
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="marca" class="form-label">
                            Marca
                        </label>
                        <input
                            type="text"
                            class="form-control"
                            id="marca"
                            name="marca"
                            maxlength="100"
                            value="<?= htmlspecialchars(
                                $_POST['marca'] ?? '',
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="precio" class="form-label">
                            Precio *
                        </label>
                        <input
                            type="number"
                            class="form-control"
                            id="precio"
                            name="precio"
                            step="0.01"
                            min="0"
                            value="<?= htmlspecialchars(
                                $_POST['precio'] ?? '',
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>"
                            required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="stock" class="form-label">
                            Stock *
                        </label>
                        <input
                            type="number"
                            class="form-control"
                            id="stock"
                            name="stock"
                            min="0"
                            value="<?= htmlspecialchars(
                                $_POST['stock'] ?? '',
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>"
                            required>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        Guardar artículo
                    </button>
                    <a href="articulos.php" class="btn btn-secondary">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require 'views/layouts/footer.php'; ?>