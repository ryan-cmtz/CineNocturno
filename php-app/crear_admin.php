<?php
require __DIR__ . '/config/database.php';
require __DIR__ . '/includes/funciones.php';
$cuantos = (int)$pdo->query("SELECT COUNT(*) FROM usuarios WHERE rol = 'admin'")->fetchColumn();
$error = '';
if ($cuantos > 0) { exit('El administrador ya existe. Elimina este archivo crear_admin.php por seguridad.'); }
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $correo = trim($_POST['correo'] ?? '');
    $clave = $_POST['contrasena'] ?? '';
    if (strlen($nombre) < 2 || !filter_var($correo, FILTER_VALIDATE_EMAIL) || strlen($clave) < 8) {
        $error = 'Completa los datos y usa una contraseña de 8 caracteres o más.';
    } else {
        $guardar = $pdo->prepare('INSERT INTO usuarios (nombre, correo, contrasena, rol) VALUES (?, ?, ?, ?)');
        $guardar->execute([$nombre, $correo, password_hash($clave, PASSWORD_DEFAULT), 'admin']);
        exit('Administrador creado. Elimina crear_admin.php y entra en login.php.');
    }
}
?>
<!doctype html><html lang="es"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Crear administrador</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"><link rel="stylesheet" href="../css/style.css"></head><body><main class="container seccion" style="max-width:600px"><h1>Primer administrador</h1><?php if ($error): ?><p class="mensaje-error"><?= escapar($error) ?></p><?php endif; ?><form method="post" class="formulario"><label class="form-label" for="nombre">Nombre</label><input class="form-control mb-3" id="nombre" name="nombre" required><label class="form-label" for="correo">Correo</label><input class="form-control mb-3" id="correo" name="correo" type="email" required><label class="form-label" for="contrasena">Contraseña</label><input class="form-control mb-3" id="contrasena" name="contrasena" type="password" minlength="8" required><button class="btn boton-principal">Crear administrador</button></form></main></body></html>