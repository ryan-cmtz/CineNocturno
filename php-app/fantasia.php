<?php
require __DIR__ . '/config/database.php';
require __DIR__ . '/includes/funciones.php';
$tituloPagina = 'Fantasía oscura';
$paginaActiva = 'fantasia';
$consulta = $pdo->prepare('SELECT titulo, genero, sinopsis, imagen, archivo_pdf FROM peliculas WHERE genero = ? ORDER BY titulo');
$consulta->execute(['Fantasía oscura']);
$peliculas = $consulta->fetchAll();
require __DIR__ . '/includes/cabecera.php';
?>
<section class="encabezado"><div class="container py-4"><h1>Fantasía oscura</h1><p>Historias con criaturas, misterios y mundos fuera de lo cotidiano.</p></div></section><section class="container seccion"><div class="row g-4"><?php if (!$peliculas): ?><p>Las películas aparecerán aquí después de importar el catálogo.</p><?php endif; ?><?php foreach ($peliculas as $pelicula): ?><article class="col-12 col-sm-6 col-lg-4"><div class="tarjeta h-100"><?php if ($pelicula['imagen']): ?><img class="img-fluid imagen-tarjeta" src="<?= escapar($pelicula['imagen']) ?>" alt="Portada de <?= escapar($pelicula['titulo']) ?>" loading="lazy"><?php endif; ?><p class="genero"><?= escapar($pelicula['genero']) ?></p><h2><?= escapar($pelicula['titulo']) ?></h2><div><?= limpiarHtml($pelicula['sinopsis']) ?></div></div></article><?php endforeach; ?></div><a href="catalogo.php">Volver al catálogo</a></section>
<?php require __DIR__ . '/includes/pie.php'; ?>