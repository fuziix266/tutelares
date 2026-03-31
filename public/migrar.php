<?php
// Script de migración temporal para añadir la columna 'fijada'
// Carga la configuración de la base de datos
$config = [
    'host' => 'localhost',
    'dbname' => 'tutelares',
    'user' => 'root',
    'pass' => ''
];

try {
    $pdo = new PDO("mysql:host={$config['host']};dbname={$config['dbname']}", $config['user'], $config['pass']);
    $pdo->exec("ALTER TABLE noticias ADD COLUMN fijada TINYINT(1) DEFAULT 0 AFTER activo");
    echo "<h1>MIGRACIÓN EXITOSA</h1><p>La columna 'fijada' ha sido añadida a la tabla 'noticias'.</p>";
} catch (Exception $e) {
    if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
        echo "<h1>AVISO</h1><p>La columna 'fijada' ya existe en la base de datos.</p>";
    } else {
        echo "<h1>ERROR</h1><p>" . $e->getMessage() . "</p>";
    }
}
?>
<hr>
<a href="/">Volver al Inicio</a>
