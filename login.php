<?php
require_once 'models/Usuario.php';

session_start();

if (isset($_SESSION['usuario_id'])) {
    header('Location: dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        $error = 'Debe completar todos los campos.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'El correo electrónico no es válido.';
    } else {
        try {
            $usuarioModel = new Usuario();

            $usuario = $usuarioModel->obtenerPorEmail($email);

            if (
                $usuario &&
                password_verify($password,$usuario['password'])
            ) {
                session_regenerate_id(true);

                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['usuario_nombre'] = $usuario['nombre'];
                $_SESSION['usuario_email'] = $usuario['email'];

                header('Location: dashboard.php');
                exit;
            } else {
                $error = 'Correo o contraseña incorrectos.';
            }
        } catch (Exception $e) {
            $error = 'Ocurrió un error al iniciar sesión.' . $e->getMessage();
        }
    }
}

$titulo = 'Iniciar sesión';

require 'views/layouts/header.php';
?>

<div class="login-container">
    <div class="card shadow">
        <div class="card-body p-4">
            <h2 class="text-center mb-4">
                TechStore
            </h2>
            <p class="text-center text-muted">
                Iniciar sesión
            </p>
            <?php if ($error): ?>
                <div class="alert alert-danger">
                    <?= htmlspecialchars(
                        $error,
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>
                </div>
            <?php endif; ?>

            <form action="login.php" method="POST">
                <div class="mb-3">
                    <label for="email" class="form-label">
                        Correo electrónico
                    </label>

                    <input
                        type="email"
                        class="form-control"
                        id="email"
                        name="email"
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
                        required>
                </div>
                <button type="submit" class="btn btn-primary w-100">
                    Iniciar sesión
                </button>
            </form>

            <div class="text-center mt-3">
                ¿No tienes una cuenta?
                <a href="registro.php">
                    Registrarse
                </a>
            </div>
        </div>
    </div>
</div>

<?php require 'views/layouts/footer.php'; ?>