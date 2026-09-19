<?php
require_once 'models/Usuario.php';

session_start();

if (isset($_SESSION['usuario_id'])) {
    header('Location: dashboard.php');
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if ( $nombre === '' || $email === '' || $password === '') {
        $error = 'Todos los campos son obligatorios.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'El correo electrónico no es válido.';
    } elseif (strlen($password) < 6) {
        $error = 'La contraseña debe tener al menos 6 caracteres.';
    } elseif ($password !== $confirmPassword) {
        $error = 'Las contraseñas no coinciden.';
    } else {
        try {
            $usuarioModel = new Usuario();

            $usuarioExistente = $usuarioModel->obtenerPorEmail($email);

            if ($usuarioExistente) {
                $error = 'El correo electrónico ya está registrado.';
            } else {
                $usuarioModel->registrar(
                    $nombre,
                    $email,
                    $password
                );

                $success = 'Usuario registrado correctamente.';
            }
        } catch (Exception $e) {
            $error = 'Ocurrió un error al registrar el usuario.';
        }
    }
}

$titulo = 'Registro';

require 'views/layouts/header.php';
?>

<div class="login-container">
    <div class="card shadow">
        <div class="card-body p-4">
            <h2 class="text-center mb-4">
                Crear cuenta
            </h2>

            <?php if ($error): ?>
                <div class="alert alert-danger">
                    <?= htmlspecialchars(
                        $error,
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert alert-success">
                    <?= htmlspecialchars(
                        $success,
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>
                    <br>
                    <a href="login.php">
                        Iniciar sesión
                    </a>
                </div>
            <?php endif; ?>

            <form action="registro.php" method="POST">
                <div class="mb-3">
                    <label for="nombre" class="form-label">
                        Nombre
                    </label>
                    <input
                        type="text"
                        class="form-control"
                        id="nombre"
                        name="nombre"
                        maxlength="100"
                        required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">
                        Correo electrónico
                    </label>
                    <input
                        type="email"
                        class="form-control"
                        id="email"
                        name="email"
                        maxlength="150"
                        required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">
                        Contraseña
                    </label>
                    <input
                        type="password"
                        class="form-control"
                        id="password"
                        name="password"
                        minlength="6"
                        required>
                </div>
                <div class="mb-3">
                    <label for="confirm_password" class="form-label">
                        Confirmar contraseña
                    </label>
                    <input
                        type="password"
                        class="form-control"
                        id="confirm_password"
                        name="confirm_password"
                        minlength="6"
                        required>
                </div>
                <button type="submit" class="btn btn-primary w-100">
                    Registrarse
                </button>
            </form>
            <div class="text-center mt-3">
                ¿Ya tienes una cuenta?

                <a href="login.php">
                    Inicia sesión
                </a>
            </div>
        </div>
    </div>
</div>

<?php require 'views/layouts/footer.php'; ?>