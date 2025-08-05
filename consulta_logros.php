<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Consultar todos los registros de usuario_logros
$registros = DB::table('usuario_logros')->get();
echo "Registros en usuario_logros:\n";
print_r($registros);

// Consultar todos los logros
$logros = DB::table('logros')->get();
echo "\nLogros disponibles:\n";
print_r($logros);

// Consultar el conteo de logros desbloqueados por usuario
$usuarioId = 1; // Ajustar según el usuario que está viendo los logros
$conteo = DB::table('usuario_logros')->where('usuario_id', $usuarioId)->count();
echo "\nLogros desbloqueados para usuario $usuarioId: $conteo\n";

// Eliminar todos los registros de usuario_logros (descomentar para ejecutar)
// DB::table('usuario_logros')->delete();
// echo "\nSe han eliminado todos los registros de usuario_logros\n";