<?php
require __DIR__ . '/../config/database.php';
require __DIR__ . '/../includes/funciones.php';
header('Content-Type: application/json; charset=utf-8');

function responder($ok, $mensaje, $datos = null, $codigo = 200) {
    http_response_code($codigo);
    echo json_encode(['ok' => $ok, 'mensaje' => $mensaje, 'datos' => $datos], JSON_UNESCAPED_UNICODE);
    exit;
}

if (empty($_SESSION['usuario'])) responder(false, 'Inicia sesión primero.', null, 401);
if ($_SESSION['usuario']['rol'] !== 'admin') responder(false, 'Solo administración puede cambiar películas.', null, 403);
$metodo = $_SERVER['REQUEST_METHOD'];

try {
    if ($metodo === 'GET') {
        $peliculas = $pdo->query('SELECT id, titulo, genero, sinopsis, imagen, archivo_pdf FROM peliculas ORDER BY titulo')->fetchAll();
        responder(true, 'Catálogo cargado.', $peliculas);
    }

    if (!hash_equals($_SESSION['token'] ?? '', $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '')) {
        responder(false, 'Recarga la página e intenta otra vez.', null, 403);
    }

    if ($metodo === 'DELETE') {
        $entrada = json_decode(file_get_contents('php://input'), true) ?? [];
        $id = filter_var($entrada['id'] ?? null, FILTER_VALIDATE_INT);
        if (!$id) responder(false, 'Falta el identificador de la película.', null, 400);
        $borrar = $pdo->prepare('DELETE FROM peliculas WHERE id = ?');
        $borrar->execute([$id]);
        responder(true, 'Película eliminada.');
    }

    if ($metodo !== 'POST') responder(false, 'Método no permitido.', null, 405);
    $id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT) ?: null;
    $titulo = trim($_POST['titulo'] ?? '');
    $genero = trim($_POST['genero'] ?? '');
    $sinopsis = limpiarHtml($_POST['sinopsis'] ?? '');
    if ($titulo === '' || mb_strlen($titulo) > 150 || $genero === '' || $sinopsis === '') {
        responder(false, 'Completa título, género y sinopsis. El título admite hasta 150 caracteres.', null, 422);
    }

    $imagen = null;
    $pdf = null;
    foreach (['imagen' => ['image/jpeg'=>'jpg','image/png'=>'png','image/webp'=>'webp'], 'pdf' => ['application/pdf'=>'pdf']] as $campo => $tipos) {
        if (empty($_FILES[$campo]['name'])) continue;
        if ($_FILES[$campo]['error'] !== UPLOAD_ERR_OK || $_FILES[$campo]['size'] > 5 * 1024 * 1024) responder(false, 'Cada archivo debe pesar menos de 5 MB.', null, 422);
        $mime = (new finfo(FILEINFO_MIME_TYPE))->file($_FILES[$campo]['tmp_name']);
        if (!isset($tipos[$mime])) responder(false, 'Formato no permitido. Imagen: JPG, PNG o WEBP; documento: PDF.', null, 422);
        $nombreArchivo = bin2hex(random_bytes(12)) . '.' . $tipos[$mime];
        $carpeta = __DIR__ . '/../uploads/';
        if (!is_dir($carpeta)) mkdir($carpeta, 0755, true);
        if (!move_uploaded_file($_FILES[$campo]['tmp_name'], $carpeta . $nombreArchivo)) responder(false, 'No se pudo guardar el archivo.', null, 500);
        if ($campo === 'imagen') $imagen = 'uploads/' . $nombreArchivo;
        if ($campo === 'pdf') $pdf = 'uploads/' . $nombreArchivo;
    }

    if ($id) {
        $actualizar = $pdo->prepare('UPDATE peliculas SET titulo=?, genero=?, sinopsis=?, imagen=COALESCE(?, imagen), archivo_pdf=COALESCE(?, archivo_pdf) WHERE id=?');
        $actualizar->execute([$titulo, $genero, $sinopsis, $imagen, $pdf, $id]);
        responder(true, 'Película actualizada.');
    }
    $insertar = $pdo->prepare('INSERT INTO peliculas (titulo, genero, sinopsis, imagen, archivo_pdf, usuario_id) VALUES (?, ?, ?, ?, ?, ?)');
    $insertar->execute([$titulo, $genero, $sinopsis, $imagen, $pdf, $_SESSION['usuario']['id']]);
    responder(true, 'Película agregada.');
} catch (PDOException $error) {
    responder(false, 'Ocurrió un error al guardar en la base de datos.', null, 500);
} catch (Throwable $error) {
    responder(false, 'No se pudo procesar la solicitud.', null, 500);
}