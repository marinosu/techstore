<?php
require_once 'middleware/AuthMiddleware.php';
require_once 'models/Articulo.php';

AuthMiddleware::verificar();

$id = filter_input(
    INPUT_GET,
    'id',
    FILTER_VALIDATE_INT
);

if (!$id) {
    header('Location: articulos.php');
    exit;
}

$articuloModel = new Articulo();
$articulo = $articuloModel->obtenerPorId($id);

if (!$articulo) {
    header('Location: articulos.php');
    exit;
}

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
            $articuloModel->actualizar(
                $id,
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
                    'Artículo actualizado correctamente.'
                )
            );

            exit;
        } catch (Exception $e) {
            $error = 'No se pudo actualizar el artículo.';
        }
    }

    $articulo['nombre'] = $nombre;
    $articulo['descripcion'] = $descripcion;
    $articulo['categoria'] = $categoria;
    $articulo['marca'] = $marca;
    $articulo['precio'] = $precio;
    $articulo['stock'] = $stock;
}

$titulo = 'Editar artículo';

require 'views/layouts/header.php';
?>

<div class="form-container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>
            Editar artículo
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
        <div class="card-body">

            <form
                action="editar_articulo.php?id=<?= (int) $id ?>"
                method="POST">
                <div class="mb-3">
                    <label for="nombre" class="form-label">
                        Nombre
                    </label>
                    <input
                        type="text"
                        class="form-control"
                        id="nombre"
                        name="nombre"
                        maxlength="150"
                        value="<?= htmlspecialchars(
                            $articulo['nombre'],
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
                        $articulo['descripcion'] ?? '',
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?></textarea>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="categoria" class="form-label">
                            Categoría
                        </label>
                        <select
                            class="form-select"
                            id="categoria"
                            name="categoria"
                            required>
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
                                        $articulo['categoria']
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
                            value="<?= htmlspecialchars(
                                $articulo['marca'] ?? '',
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>"
                        >
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="precio" class="form-label">
                            Precio
                        </label>
                        <input
                            type="number"
                            class="form-control"
                            id="precio"
                            name="precio"
                            step="0.01"
                            min="0"
                            value="<?= htmlspecialchars(
                                (string) $articulo['precio'],
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>"
                            required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="stock" class="form-label">
                            Stock
                        </label>
                        <input
                            type="number"
                            class="form-control"
                            id="stock"
                            name="stock"
                            min="0"
                            value="<?= (int) $articulo['stock'] ?>"
                            required>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">
                    Actualizar artículo
                </button>
                <a href="articulos.php" class="btn btn-secondary">
                    Cancelar
                </a>
            </form>
        </div>
    </div>
</div>

<?php require 'views/layouts/footer.php'; ?>