<?php
require_once 'middleware/AuthMiddleware.php';
require_once 'models/Articulo.php';

AuthMiddleware::verificar();

try {
    $articuloModel = new Articulo();
    $articulos = $articuloModel->obtenerTodos();
} catch (Exception $e) {
    $error = 'No se pudieron cargar los artículos.';
    $articulos = [];
}

$titulo = 'Artículos';

require 'views/layouts/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1>
            Artículos tecnológicos
        </h1>
        <p class="text-muted">
            Administración de productos.
        </p>
    </div>
    <a href="crear_articulo.php" class="btn btn-primary">
        + Nuevo artículo
    </a>
</div>

<?php if (isset($_GET['mensaje'])): ?>
    <div class="alert alert-success">
        <?= htmlspecialchars(
            $_GET['mensaje'],
            ENT_QUOTES,
            'UTF-8'
        ) ?>
    </div>
<?php endif; ?>

<?php if (isset($error)): ?>
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
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Artículo</th>
                        <th>Categoría</th>
                        <th>Marca</th>
                        <th>Precio</th>
                        <th>Stock</th>
                        <th class="text-center">
                            Acciones
                        </th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($articulos)): ?>
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            No hay artículos registrados.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($articulos as $articulo): ?>
                        <tr>
                            <td>
                                <?= (int) $articulo['id'] ?>
                            </td>
                            <td>
                                <strong>
                                    <?= htmlspecialchars(
                                        $articulo['nombre'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>
                                </strong>
                                <br>
                                <small class="text-muted">
                                    <?= htmlspecialchars(
                                        $articulo['descripcion'] ?? '',
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>
                                </small>
                            </td>
                            <td>
                                <?= htmlspecialchars(
                                    $articulo['categoria'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </td>
                            <td>
                                <?= htmlspecialchars(
                                    $articulo['marca'] ?? '',
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </td>
                            <td>
                                $
                                <?= number_format(
                                    (float) $articulo['precio'],
                                    2
                                ) ?>
                            </td>
                            <td>
                                <?php if (
                                    (int) $articulo['stock'] > 0
                                ): ?>
                                    <span class="badge text-bg-success">
                                        <?= (int) $articulo['stock'] ?>
                                    </span>
                                <?php else: ?>
                                    <span class="badge text-bg-danger">
                                        Agotado
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <a
                                    href="ver_articulo.php?id=<?= (int) $articulo['id'] ?>"
                                    class="btn btn-sm btn-info">
                                    Ver
                                </a>
                                <a
                                    href="editar_articulo.php?id=<?= (int) $articulo['id'] ?>"
                                    class="btn btn-sm btn-warning">
                                    Editar
                                </a>
                                <a
                                    href="eliminar_articulo.php?id=<?= (int) $articulo['id'] ?>"
                                    class="btn btn-sm btn-danger"
                                    onclick="return confirm('¿Está seguro de eliminar este artículo?');">
                                    Eliminar
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require 'views/layouts/footer.php'; ?>