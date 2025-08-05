<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ResetLogros extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'logros:reset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Elimina todos los registros de la tabla usuario_logros para corregir problemas de logros';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Confirmar la acción
        if (!$this->confirm('¿Estás seguro de que deseas eliminar TODOS los registros de logros desbloqueados? Esta acción no se puede deshacer.')) {
            $this->info('Operación cancelada.');
            return;
        }

        // Registrar el inicio de la operación
        Log::info('Iniciando reset de logros mediante comando artisan');

        // Eliminar todos los registros de usuario_logros
        $count = DB::table('usuario_logros')->count();
        DB::table('usuario_logros')->delete();

        // Registrar el resultado
        Log::info("Se han eliminado $count registros de la tabla usuario_logros mediante comando artisan");

        $this->info("Se han eliminado $count registros de la tabla usuario_logros");
        $this->info("Los usuarios deberán volver a desbloquear sus logros correctamente.");
    }
}