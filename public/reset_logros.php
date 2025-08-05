<?php
// Configuración de la base de datos (ajustar según el archivo .env)
$host = 'localhost';
$dbname = 'ecocardenal';
$username = 'root';
$password = '1917248zzz';

// Verificar si se ha enviado el formulario
$mensaje = '';
$tipo = '';

if (isset($_POST['reset'])) {
    try {
        // Conectar a la base de datos
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Eliminar todos los registros de usuario_logros
        $stmt = $pdo->prepare("DELETE FROM usuario_logros");
        $stmt->execute();
        
        $count = $stmt->rowCount();
        $mensaje = "Se han eliminado $count registros de la tabla usuario_logros";
        $tipo = 'success';
        
    } catch (PDOException $e) {
        $mensaje = "Error: " . $e->getMessage();
        $tipo = 'error';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resetear Logros</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            line-height: 1.6;
        }
        .container {
            background-color: #f5f5f5;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 20px;
            margin-top: 20px;
        }
        h1 {
            color: #333;
            border-bottom: 2px solid #eee;
            padding-bottom: 10px;
        }
        .warning {
            background-color: #fff3cd;
            color: #856404;
            border: 1px solid #ffeeba;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 20px;
        }
        .btn {
            display: inline-block;
            background-color: #dc3545;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
            border: none;
        }
        .btn:hover {
            background-color: #c82333;
        }
        .message {
            margin-top: 20px;
            padding: 15px;
            border-radius: 5px;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Herramienta de Reseteo de Logros</h1>
        
        <?php if ($mensaje): ?>
        <div class="message <?php echo $tipo; ?>">
            <?php echo $mensaje; ?>
        </div>
        <?php endif; ?>
        
        <div class="warning">
            <strong>¡Advertencia!</strong> Esta herramienta eliminará todos los registros de logros desbloqueados por los usuarios. Esta acción no se puede deshacer.
        </div>
        
        <p>Utiliza esta herramienta solo si hay problemas con el sistema de logros y necesitas reiniciarlos para todos los usuarios.</p>
        
        <p>Al hacer clic en el botón a continuación, se eliminarán todos los registros de la tabla <code>usuario_logros</code>.</p>
        
        <form method="post" onsubmit="return confirm('¿Estás seguro de que deseas eliminar TODOS los registros de logros desbloqueados? Esta acción no se puede deshacer.');">
            <button type="submit" name="reset" class="btn">Resetear Todos los Logros</button>
        </form>
        
        <p style="margin-top: 20px;">
            <a href="/progreso/logros" style="color: #007bff;">Volver a la página de logros</a>
        </p>
    </div>
</body>
</html>