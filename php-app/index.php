<?php
require __DIR__ . '/config/database.php';
require __DIR__ . '/includes/funciones.php';
$tituloPagina = 'Inicio';
$paginaActiva = 'inicio';
$peliculas = $pdo->query('SELECT id, titulo, genero, sinopsis, imagen, archivo_pdf FROM peliculas ORDER BY titulo LIMIT 6')->fetchAll();
require __DIR__ . '/includes/cabecera.php';
?>
<section class="portada"><div class="container py-5"><p class="etiqueta">PELÍCULAS PARA DESCUBRIR</p><h1>Bienvenido a CineNocturno</h1><p>Explora historias de terror, fantasía oscura, animación gótica y comedia.</p><a class="btn boton-principal" href="catalogo.php">Ver catálogo</a></div></section>
<section class="container seccion"><h2>Películas para empezar</h2><?php if (!$peliculas): ?><p>Aún no hay películas cargadas. Después de crear tu cuenta administradora, importa el catálogo inicial desde <code>importar_catalogo.php</code>.</p><?php endif; ?><div class="row g-4"><?php foreach ($peliculas as $pelicula): ?><article class="col-12 col-sm-6 col-lg-4"><div class="tarjeta h-100"><?php if ($pelicula['imagen']): ?><img class="img-fluid imagen-tarjeta" src="<?= escapar($pelicula['imagen']) ?>" alt="Portada de <?= escapar($pelicula['titulo']) ?>" loading="lazy"><?php endif; ?><p class="genero"><?= escapar($pelicula['genero']) ?></p><h2><?= escapar($pelicula['titulo']) ?></h2><div><?= limpiarHtml($pelicula['sinopsis']) ?></div><a href="catalogo.php">Explorar catálogo</a></div></article><?php endforeach; ?></div></section>
<?php require __DIR__ . '/includes/pie.php'; ?>