<?php
require __DIR__ . '/config/database.php';
require __DIR__ . '/includes/funciones.php';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $correo = trim($_POST['correo'] ?? '');
    $clave = $_POST['contrasena'] ?? '';
    if (strlen($nombre) < 2 || !filter_var($correo, FILTER_VALIDATE_EMAIL) || strlen($clave) < 8) {
        $error = 'Escribe un nombre, un correo válido y una contraseña de 8 caracteres o más.';
    } else {
        try {
            $guardar = $pdo->prepare('INSERT INTO usuarios (nombre, correo, contrasena, rol) VALUES (?, ?, ?, ?)');
            $guardar->execute([$nombre, $correo, password_hash($clave, PASSWORD_DEFAULT), 'usuario']);
            header('Location: login.php');
            exit;
        } catch (PDOException $e) {
            $error = 'No se pudo crear la cuenta. Revisa si el correo ya está registrado.';
        }
    }
}
?>
<!doctype html><html lang="es"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Crear cuenta | CineNocturno</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"><link rel="stylesheet" href="../css/style.css"></head><body><main class="container seccion" style="max-width:600px"><h1>Crear cuenta</h1><?php if ($error): ?><p class="mensaje-error"><?= escapar($error) ?></p><?php endif; ?><form method="post" class="formulario"><label class="form-label" for="nombre">Nombre</label><input class="form-control mb-3" id="nombre" name="nombre" required minlength="2"><label class="form-label" for="correo">Correo</label><input class="form-control mb-3" id="correo" name="correo" type="email" required><label class="form-label" for="contrasena">Contraseña (mínimo 8 caracteres)</label><input class="form-control mb-3" id="contrasena" name="contrasena" type="password" required minlength="8"><button class="btn boton-principal">Registrarme</button></form></main></body></html>