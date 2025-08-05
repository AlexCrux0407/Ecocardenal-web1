<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class Logro extends Model
{
    use HasFactory;

    protected $table = 'logros';

    protected $fillable = [
        'nombre',
        'descripcion',
        'icono',
        'tipo',
        'objetivo',
        'puntos_recompensa',
    ];

    /**
     * Relación con usuarios que han desbloqueado este logro
     */
    public function usuarios()
    {
        return $this->belongsToMany(Usuario::class, 'usuario_logros', 'logro_id', 'usuario_id')
                    ->withPivot('desbloqueado_en');
    }

    /**
     * Verifica si un usuario ha desbloqueado este logro
     */
    public function estaDesbloqueado($usuarioId)
    {
        // Verificar si existe un registro en la tabla usuario_logros
        $desbloqueado = UsuarioLogro::where('usuario_id', $usuarioId)
                          ->where('logro_id', $this->id)
                          ->exists();
        
        // Registrar información de depuración
        \Log::debug('Verificando si logro está desbloqueado - Logro ID: ' . $this->id . 
                  ' - Usuario ID: ' . $usuarioId . 
                  ' - Resultado: ' . ($desbloqueado ? 'Sí' : 'No'));
        
        return $desbloqueado;
    }

    /**
     * Calcula el progreso actual de un usuario para este logro
     */
    public function calcularProgreso($usuarioId)
    {
        if ($this->estaDesbloqueado($usuarioId)) {
            return $this->objetivo; // Ya está desbloqueado, progreso completo
        }

        $usuario = Usuario::find($usuarioId);
        if (!$usuario) return 0;

        switch ($this->tipo) {
            case 'puntos':
                $ranking = Ranking::where('usuario_id', $usuarioId)->first();
                return $ranking ? $ranking->total_points : 0;
                
            case 'actividades':
                return CompletedActivity::where('usuario_id', $usuarioId)->count();
                
            case 'quizzes':
                // Verificar tanto 'quiz' como 'quizzes' para compatibilidad
                return CompletedActivity::where('usuario_id', $usuarioId)
                    ->where(function($query) {
                        $query->where('activity_type', 'quiz')
                              ->orWhere('activity_type', 'quizzes');
                    })
                    ->count();
                
            case 'experimentos':
                // Verificar tanto 'experimentos' como 'experimento' para compatibilidad
                return CompletedActivity::where('usuario_id', $usuarioId)
                    ->where(function($query) {
                        $query->where('activity_type', 'experimentos')
                              ->orWhere('activity_type', 'experimento');
                    })
                    ->count();
                
            default:
                return 0;
        }
    }

    /**
     * Calcula el porcentaje de progreso
     */
    public function porcentajeProgreso($usuarioId)
    {
        if ($this->estaDesbloqueado($usuarioId)) {
            return 100; // Ya está desbloqueado
        }

        $progreso = $this->calcularProgreso($usuarioId);
        return $this->objetivo > 0 ? min(round(($progreso / $this->objetivo) * 100), 100) : 0;
    }

    /**
     * Desbloquea este logro para un usuario si cumple los requisitos
     */
    public function verificarYDesbloquear($usuarioId)
    {
        // Si ya está desbloqueado, no hacer nada
        if ($this->estaDesbloqueado($usuarioId)) {
            return false;
        }

        // Verificar si cumple los requisitos
        $progreso = $this->calcularProgreso($usuarioId);
        
        // Verificación estricta: el progreso debe ser mayor o igual al objetivo
        // y el objetivo debe ser mayor que cero para evitar desbloqueos incorrectos
        if ($progreso >= $this->objetivo && $this->objetivo > 0) {
            // Registrar información de depuración
            \Log::info('Desbloqueando logro: ' . $this->nombre . ' para usuario: ' . $usuarioId . 
                      ' - Progreso: ' . $progreso . ' - Objetivo: ' . $this->objetivo);
            
            // Desbloquear el logro
            UsuarioLogro::create([
                'usuario_id' => $usuarioId,
                'logro_id' => $this->id,
                'desbloqueado_en' => now(),
            ]);

            // Actualizar puntos en el ranking
            Ranking::updateOrInsert(
                ['usuario_id' => $usuarioId],
                [
                    'total_points' => \DB::raw('total_points + ' . ($this->puntos_recompensa ?: 0)),
                    'updated_at' => now()
                ]
            );

            return true; // Logro desbloqueado
        }

        return false; // No cumple los requisitos
    }
}