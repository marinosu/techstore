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
    $articuloModel->eliminar($id);

    header(
        'Location: articulos.php?mensaje=' .
        urlencode(
            'Artículo eliminado correctamente.'
        )
    );

    exit;
} catch (Exception $e) {
    header(
        'Location: articulos.php?mensaje=' .
        urlencode(
            'No se pudo eliminar el artículo.'
        )
    );
    
    exit;
}