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

try {
    $articuloModel = new Articulo();

    $articulo = $articuloModel->obtenerPorId($id);

    if (!$articulo) {
        header(
            'Location: articulos.php?mensaje=' .
            urlencode(
                'Artículo no encontrado.'
            )
        );

        exit;
    }
} catch (Exception $e) {
    die('Error al consultar el artículo.');
}

$titulo = 'Detalle del artículo';

require 'views/layouts/header.php';
?>

<div class="form-container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>
            Detalle del artículo
        </h1>
        <a href="articulos.php" class="btn btn-secondary">
            Volver
        </a>
    </div>

    <div class="card shadow">
        <div class="card-body">
            <h2>
                <?= htmlspecialchars(
                    $articulo['nombre'],
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>
            </h2>
            <hr>
            <p>
                <strong>
                    Descripción:
                </strong>
                <br>
                <?= nl2br(
                    htmlspecialchars(
                        $articulo['descripcion'] ?? '',
                        ENT_QUOTES,
                        'UTF-8'
                    )
                ) ?>
            </p>
            <p>
                <strong>
                    Categoría:
                </strong>
                <?= htmlspecialchars(
                    $articulo['categoria'],
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>
            </p>
            <p>
                <strong>
                    Marca:
                </strong>
                <?= htmlspecialchars(
                    $articulo['marca'] ?? '',
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>
            </p>
            <p>
                <strong>
                    Precio:
                </strong>
                $
                <?= number_format(
                    (float) $articulo['precio'],
                    2
                ) ?>
            </p>
            <p>
                <strong>
                    Stock:
                </strong>
                <?= (int) $articulo['stock'] ?>
            </p>
            <p>
                <strong>
                    Fecha de registro:
                </strong>
                <?= htmlspecialchars(
                    $articulo['created_at'],
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>
            </p>
            <div class="mt-4">
                <a
                    href="editar_articulo.php?id=<?= (int) $articulo['id'] ?>"
                    class="btn btn-warning">
                    Editar
                </a>
                <a
                    href="articulos.php"
                    class="btn btn-secondary">
                    Volver
                </a>
            </div>
        </div>
    </div>
</div>

<?php require 'views/layouts/footer.php'; ?>