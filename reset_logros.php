<?php

// Configuración de la base de datos (ajustar según el archivo .env)
$host = 'localhost';
$dbname = 'ecocardenal';
$username = 'root';
$password = '';

try {
    // Conectar a la base de datos
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Eliminar todos los registros de usuario_logros
    $stmt = $pdo->prepare("DELETE FROM usuario_logros");
    $stmt->execute();
    
    $count = $stmt->rowCount();
    echo "Se han eliminado $count registros de la tabla usuario_logros\n";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}