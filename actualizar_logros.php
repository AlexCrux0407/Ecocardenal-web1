<?php

/**
 * Script para actualizar la tabla de logros
 * Este script ejecuta la migración para agregar las columnas faltantes a la tabla logros
 */

// Definir la ruta base de la aplicación
$basePath = __DIR__;

// Función para ejecutar comandos y mostrar la salida
function ejecutarComando($comando) {
    echo "Ejecutando: $comando\n";
    $salida = [];
    $codigo = 0;
    exec($comando, $salida, $codigo);
    
    echo implode("\n", $salida) . "\n";
    
    if ($codigo !== 0) {
        echo "Error al ejecutar el comando (código $codigo)\n";
        return false;
    }
    
    return true;
}

// Función para ejecutar consultas SQL directamente
function ejecutarSQL($consulta) {
    $comando = "mysql -u root -p1917248zzz -e \"USE ecocardenal; $consulta\"";
    return ejecutarComando($comando);
}

echo "=== Actualizando tabla de logros ===\n";

// 1. Verificar si la tabla logros existe
echo "\nVerificando tabla logros...\n";
$resultado = ejecutarSQL("SHOW TABLES LIKE 'logros';");

if (!$resultado) {
    echo "Error al verificar la tabla logros.\n";
    exit(1);
}

// 2. Verificar la estructura actual de la tabla
echo "\nEstructura actual de la tabla logros:\n";
$resultado = ejecutarSQL("DESCRIBE logros;");

if (!$resultado) {
    echo "Error al verificar la estructura de la tabla logros.\n";
    exit(1);
}

// 3. Agregar las columnas faltantes
echo "\nAgregando columnas faltantes...\n";

// Verificar y agregar columna icono
$resultado = ejecutarSQL("SHOW COLUMNS FROM logros LIKE 'icono';");
if (empty($resultado)) {
    echo "Agregando columna 'icono'...\n";
    $resultado = ejecutarSQL("ALTER TABLE logros ADD COLUMN icono VARCHAR(255) DEFAULT 'fa-trophy' AFTER descripcion;");
    if (!$resultado) {
        echo "Error al agregar la columna 'icono'.\n";
    }
}

// Verificar y agregar columna tipo
$resultado = ejecutarSQL("SHOW COLUMNS FROM logros LIKE 'tipo';");
if (empty($resultado)) {
    echo "Agregando columna 'tipo'...\n";
    $resultado = ejecutarSQL("ALTER TABLE logros ADD COLUMN tipo VARCHAR(255) DEFAULT 'actividades' AFTER icono;");
    if (!$resultado) {
        echo "Error al agregar la columna 'tipo'.\n";
    }
}

// Verificar y agregar columna objetivo
$resultado = ejecutarSQL("SHOW COLUMNS FROM logros LIKE 'objetivo';");
if (empty($resultado)) {
    echo "Agregando columna 'objetivo'...\n";
    $resultado = ejecutarSQL("ALTER TABLE logros ADD COLUMN objetivo INT DEFAULT 1 AFTER tipo;");
    if (!$resultado) {
        echo "Error al agregar la columna 'objetivo'.\n";
    }
}

// Verificar y agregar columna puntos_recompensa
$resultado = ejecutarSQL("SHOW COLUMNS FROM logros LIKE 'puntos_recompensa';");
if (empty($resultado)) {
    echo "Agregando columna 'puntos_recompensa'...\n";
    $resultado = ejecutarSQL("ALTER TABLE logros ADD COLUMN puntos_recompensa INT DEFAULT 20 AFTER objetivo;");
    if (!$resultado) {
        echo "Error al agregar la columna 'puntos_recompensa'.\n";
    }
}

// Verificar y agregar columnas de timestamps
$resultado = ejecutarSQL("SHOW COLUMNS FROM logros LIKE 'created_at';");
if (empty($resultado)) {
    echo "Agregando columnas de timestamps...\n";
    $resultado = ejecutarSQL("ALTER TABLE logros ADD COLUMN created_at TIMESTAMP NULL, ADD COLUMN updated_at TIMESTAMP NULL;");
    if (!$resultado) {
        echo "Error al agregar las columnas de timestamps.\n";
    }
}

// 4. Actualizar los datos de los logros existentes
echo "\nActualizando datos de los logros existentes...\n";

$logros = [
    [
        'id' => 1,
        'nombre' => 'Primeros Pasos',
        'descripcion' => 'Completaste tu primera actividad',
        'icono' => 'fa-medal',
        'tipo' => 'actividades',
        'objetivo' => 1,
        'puntos_recompensa' => 10
    ],
    [
        'id' => 2,
        'nombre' => 'Aprendiz Ecológico',
        'descripcion' => 'Completaste 5 actividades',
        'icono' => 'fa-seedling',
        'tipo' => 'actividades',
        'objetivo' => 5,
        'puntos_recompensa' => 20
    ],
    [
        'id' => 3,
        'nombre' => 'Guardian del Planeta',
        'descripcion' => 'Completaste 10 actividades',
        'icono' => 'fa-globe-americas',
        'tipo' => 'actividades',
        'objetivo' => 10,
        'puntos_recompensa' => 30
    ],
    [
        'id' => 4,
        'nombre' => 'Experto en Sostenibilidad',
        'descripcion' => 'Completaste 20 actividades',
        'icono' => 'fa-leaf',
        'tipo' => 'actividades',
        'objetivo' => 20,
        'puntos_recompensa' => 40
    ],
    [
        'id' => 5,
        'nombre' => 'Pionero Verde',
        'descripcion' => 'Alcanzaste 50 puntos',
        'icono' => 'fa-tree',
        'tipo' => 'puntos',
        'objetivo' => 50,
        'puntos_recompensa' => 25
    ],
    [
        'id' => 6,
        'nombre' => 'Héroe Ambiental',
        'descripcion' => 'Alcanzaste 100 puntos',
        'icono' => 'fa-mountain',
        'tipo' => 'puntos',
        'objetivo' => 100,
        'puntos_recompensa' => 35
    ],
    [
        'id' => 7,
        'nombre' => 'Maestro de la Conservación',
        'descripcion' => 'Alcanzaste 200 puntos',
        'icono' => 'fa-water',
        'tipo' => 'puntos',
        'objetivo' => 200,
        'puntos_recompensa' => 45
    ],
    [
        'id' => 8,
        'nombre' => 'Leyenda Ecológica',
        'descripcion' => 'Alcanzaste 500 puntos',
        'icono' => 'fa-crown',
        'tipo' => 'puntos',
        'objetivo' => 500,
        'puntos_recompensa' => 60
    ],
    [
        'id' => 9,
        'nombre' => 'Explorador Natural',
        'descripcion' => 'Completaste todas las actividades de exploración',
        'icono' => 'fa-compass',
        'tipo' => 'experimentos',
        'objetivo' => 5,
        'puntos_recompensa' => 50
    ],
    [
        'id' => 10,
        'nombre' => 'Científico Ciudadano',
        'descripcion' => 'Participaste en 3 proyectos de investigación',
        'icono' => 'fa-flask',
        'tipo' => 'experimentos',
        'objetivo' => 3,
        'puntos_recompensa' => 40
    ]
];

foreach ($logros as $logro) {
    $id = $logro['id'];
    $nombre = $logro['nombre'];
    $descripcion = $logro['descripcion'];
    $icono = $logro['icono'];
    $tipo = $logro['tipo'];
    $objetivo = $logro['objetivo'];
    $puntos_recompensa = $logro['puntos_recompensa'];
    
    $consulta = "UPDATE logros SET "
             . "nombre = '$nombre', "
             . "descripcion = '$descripcion', "
             . "icono = '$icono', "
             . "tipo = '$tipo', "
             . "objetivo = $objetivo, "
             . "puntos_recompensa = $puntos_recompensa, "
             . "created_at = NOW(), "
             . "updated_at = NOW() "
             . "WHERE id = $id;";
    
    $resultado = ejecutarSQL($consulta);
    if (!$resultado) {
        echo "Error al actualizar el logro con ID $id.\n";
    } else {
        echo "Logro con ID $id actualizado correctamente.\n";
    }
}

// 5. Verificar la estructura actualizada de la tabla
echo "\nEstructura actualizada de la tabla logros:\n";
$resultado = ejecutarSQL("DESCRIBE logros;");

if (!$resultado) {
    echo "Error al verificar la estructura actualizada de la tabla logros.\n";
    exit(1);
}

// 6. Verificar los datos actualizados
echo "\nDatos actualizados de la tabla logros:\n";
$resultado = ejecutarSQL("SELECT id, nombre, tipo, objetivo, puntos_recompensa FROM logros;");

if (!$resultado) {
    echo "Error al verificar los datos actualizados de la tabla logros.\n";
    exit(1);
}

echo "\n=== Actualización completada ===\n";
echo "Para activar los logros, reinicie el servidor de Laravel.\n";