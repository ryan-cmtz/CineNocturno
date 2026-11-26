<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

function escapar($texto) {
    return htmlspecialchars((string)$texto, ENT_QUOTES, 'UTF-8');
}

function requiereSesion() {
    if (empty($_SESSION['usuario'])) {
        header('Location: ../login.php');
        exit;
    }
}

function requiereAdmin() {
    requiereSesion();
    if ($_SESSION['usuario']['rol'] !== 'admin') {
        http_response_code(403);
        exit('No tienes permiso para entrar a esta sección.');
    }
}

function tokenCsrf() {
    if (empty($_SESSION['token'])) {
        $_SESSION['token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['token'];
}

function limpiarHtml($html) {
    $permitidas = '<p><br><strong><b><em><i><u><ul><ol><li><blockquote><h2><h3><a>';
    $html = strip_tags($html, $permitidas);
    $html = preg_replace("~\\s+on[a-z]+\\s*=\\s*(\"[^\"]*\"|'[^']*'|[^\\s>]+)~i", '', $html);
    $html = preg_replace("~href\\s*=\\s*([\"'])\\s*javascript:[^\"']*\\1~i", 'href="#"', $html);
    return $html;
}