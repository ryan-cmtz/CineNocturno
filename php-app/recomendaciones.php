<?php
require __DIR__ . '/config/database.php';
require __DIR__ . '/includes/funciones.php';
$tituloPagina = 'Recomendaciones';
$paginaActiva = 'recomendaciones';
require __DIR__ . '/includes/cabecera.php';
?>
<section class="encabezado"><div class="container py-4"><h1>Recomendaciones</h1><p>Elige el género que quieres explorar.</p></div></section><section class="container seccion"><div class="row g-4"><?php foreach (['Terror y suspenso','Fantasía oscura','Animación gótica','Comedia de terror'] as $genero): ?><article class="col-12 col-md-6 col-lg-3"><div class="tarjeta h-100"><h2><?= escapar($genero) ?></h2><a href="catalogo.php?genero=<?= urlencode($genero) ?>">Ver películas</a></div></article><?php endforeach; ?></div></section><section class="container seccion"><h2>Tus favoritas</h2><ul id="listaFavoritas" class="lista-favoritas" aria-live="polite"></ul><p id="estadoFavoritas" role="status" aria-live="polite">Las películas que guardes en el catálogo aparecerán aquí en este navegador.</p></section>
<?php require __DIR__ . '/includes/pie.php'; ?>