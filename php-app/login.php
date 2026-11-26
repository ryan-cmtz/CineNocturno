<?php
require __DIR__ . '/config/database.php';
require __DIR__ . '/includes/funciones.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $consulta = $pdo->prepare('SELECT id, nombre, correo, contrasena, rol, activo FROM usuarios WHERE correo = ?');
    $consulta->execute([trim($_POST['correo'] ?? '')]);
    $usuario = $consulta->fetch();

    if ($usuario && $usuario['activo'] && password_verify($_POST['contrasena'] ?? '', $usuario['contrasena'])) {
        session_regenerate_id(true);
        $_SESSION['usuario'] = ['id' => $usuario['id'], 'nombre' => $usuario['nombre'], 'correo' => $usuario['correo'], 'rol' => $usuario['rol']];
        header('Location: index.php');
        exit;
    }
    $error = 'Correo o contraseña incorrectos.';
}
?>
<!doctype html><html lang="es"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Iniciar sesión | CineNocturno</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"><link rel="stylesheet" href="../css/style.css"></head><body><main class="container seccion" style="max-width:600px"><h1>Iniciar sesión</h1><?php if ($error): ?><p class="mensaje-error"><?= escapar($error) ?></p><?php endif; ?><form method="post" class="formulario"><label class="form-label" for="correo">Correo</label><input class="form-control mb-3" id="correo" name="correo" type="email" required><label class="form-label" for="contrasena">Contraseña</label><input class="form-control mb-3" id="contrasena" name="contrasena" type="password" required><button class="btn boton-principal">Entrar</button></form><p class="mt-3"><a href="registro.php">Crear una cuenta de usuario</a></p><p><a href="crear_admin.php">Configurar el primer administrador</a></p></main></body></html>