<?php
require_once 'middleware/AuthMiddleware.php';

AuthMiddleware::verificar();

$titulo = 'Dashboard';

require 'views/layouts/header.php';
?>

<div class="mb-4">
    <h1>
        Dashboard
    </h1>
    <p class="text-muted">
        Panel de administración de TechStore.
    </p>
</div>

<div class="row g-4">
    <div class="col-md-6">
        <div class="card shadow dashboard-card">
            <div class="card-body">
                <h5 class="card-title">
                    Artículos
                </h5>
                <p class="card-text">
                    Gestiona los artículos tecnológicos
                    registrados en el sistema.
                </p>
                <a href="articulos.php" class="btn btn-primary">
                    Administrar artículos
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card shadow dashboard-card">
            <div class="card-body">
                <h5 class="card-title">
                    Usuario
                </h5>
                <p class="card-text">
                    Sesión iniciada como:
                    <strong>
                        <?= htmlspecialchars(
                            $_SESSION['usuario_nombre'],
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>
                    </strong>
                </p>
                <a href="logout.php" class="btn btn-outline-danger">
                    Cerrar sesión
                </a>
            </div>
        </div>
    </div>
</div>

<?php require 'views/layouts/footer.php'; ?>