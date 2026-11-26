<?php
require __DIR__ . '/config/database.php';
require __DIR__ . '/includes/funciones.php';
requiereAdmin();
$archivo = __DIR__ . '/../catalogo.html';
if (!is_file($archivo)) exit('No se encontró el catálogo HTML original.');
libxml_use_internal_errors(true);
$dom = new DOMDocument('1.0', 'UTF-8');
$contenido = file_get_contents($archivo);
$dom->loadHTML('<?xml encoding="UTF-8">' . $contenido);
$ruta = new DOMXPath($dom);
$tarjetas = $ruta->query('//article[contains(concat(" ", normalize-space(@class), " "), " tarjeta-pelicula ")]');
$insertar = $pdo->prepare('INSERT INTO peliculas (titulo, genero, sinopsis, imagen) VALUES (?, ?, ?, ?)');
$buscar = $pdo->prepare('SELECT id FROM peliculas WHERE titulo = ?');
$generos = ['terror'=>'Terror y suspenso','fantasia'=>'Fantasía oscura','animacion'=>'Animación gótica','comedia'=>'Comedia de terror'];
$agregadas = 0;
foreach ($tarjetas as $tarjeta) {
    $titulo = trim($ruta->query('.//h2', $tarjeta)->item(0)?->textContent ?? '');
    $sinopsis = trim($ruta->query('.//h2/following-sibling::p[1]', $tarjeta)->item(0)?->textContent ?? '');
    $codigoGenero = $tarjeta->getAttribute('data-genero');
    $src = trim($ruta->query('.//img/@src', $tarjeta)->item(0)?->nodeValue ?? '');
    if ($titulo === '' || $sinopsis === '' || !isset($generos[$codigoGenero])) continue;
    $buscar->execute([$titulo]);
    if ($buscar->fetch()) continue;
    $imagen = $src ? '../' . $src : null;
    $insertar->execute([$titulo, $generos[$codigoGenero], escapar($sinopsis), $imagen]);
    $agregadas++;
}
?>
<!doctype html><html lang="es"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Importar catálogo</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"><link rel="stylesheet" href="../css/style.css"></head><body><main class="container seccion"><h1>Importación del catálogo</h1><p>Se agregaron <?= (int)$agregadas ?> películas. Si vuelves a ejecutar esta página, no duplica títulos existentes.</p><p><a href="index.php">Ver catálogo</a> | <a href="admin/peliculas.php">Administrar</a></p><p>Cuando confirmes que terminó la importación, elimina el archivo <strong>importar_catalogo.php</strong>.</p></main></body></html>