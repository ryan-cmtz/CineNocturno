<?php
$tituloPagina = $tituloPagina ?? 'CineNocturno';
$paginaActiva = $paginaActiva ?? '';
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= escapar($tituloPagina) ?> | CineNocturno</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700&family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<header>
    <nav class="navbar">
        <div class="container d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
            <a class="marca" href="index.php">CineNocturno</a>
            <ul class="lista-menu d-flex flex-wrap gap-3 mb-0">
                <li><a <?= $paginaActiva === 'inicio' ? 'aria-current="page"' : '' ?> href="index.php">Inicio</a></li>
                <li><a <?= $paginaActiva === 'catalogo' ? 'aria-current="page"' : '' ?> href="catalogo.php">Catálogo</a></li>
                <li><a <?= $paginaActiva === 'fantasia' ? 'aria-current="page"' : '' ?> href="fantasia.php">Fantasía oscura</a></li>
                <li><a <?= $paginaActiva === 'recomendaciones' ? 'aria-current="page"' : '' ?> href="recomendaciones.php">Recomendaciones</a></li>
                <li><a <?= $paginaActiva === 'contacto' ? 'aria-current="page"' : '' ?> href="contacto.php">Contacto</a></li>
            </ul>
            <div class="d-flex flex-wrap gap-3">
                <?php if (!empty($_SESSION['usuario'])): ?>
                    <span><?= escapar($_SESSION['usuario']['nombre']) ?></span>
                    <?php if ($_SESSION['usuario']['rol'] === 'admin'): ?><a href="admin/peliculas.php">Administrar</a><?php endif; ?>
                    <a href="logout.php">Salir</a>
                <?php else: ?>
                    <a href="login.php">Iniciar sesión</a>
                    <a href="registro.php">Crear cuenta</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>
</header>
<main>