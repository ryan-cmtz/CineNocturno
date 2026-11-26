<?php
// Conexión para XAMPP. En phpMyAdmin crea primero la base indicada en sql/cinenocturno.sql.
$host = 'localhost';
$nombreBase = 'cinenocturno';
$usuarioBase = 'root';
$contrasenaBase = '';

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$nombreBase;charset=utf8mb4",
        $usuarioBase,
        $contrasenaBase,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
         PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
    );
} catch (PDOException $error) {
    http_response_code(500);
    exit('No se pudo conectar a MySQL. Revisa que MySQL esté iniciado en XAMPP e importa la base de datos.');
}