<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class Meta extends Model
{
    use HasFactory;

    protected $table = 'metas';

    protected $fillable = [
        'nombre',
        'descripcion',
        'puntos',
        'tipo',
        'objetivo',
    ];
    
    /**
     * Relación con usuarios que han completado esta meta
     */
    public function usuarios()
    {
        return $this->belongsToMany(Usuario::class, 'usuario_metas', 'meta_id', 'usuario_id')
                    ->withPivot('completada_en');
    }

    /**
     * Calcula el progreso actual de un usuario para esta meta
     */
    public function calcularProgreso($usuarioId)
    {
        $usuario = Usuario::find($usuarioId);
        if (!$usuario) return 0;

        switch ($this->tipo) {
            case 'puntos':
                $ranking = Ranking::where('usuario_id', $usuarioId)->first();
                return $ranking ? $ranking->total_points : 0;
                
            case 'actividades':
                return CompletedActivity::where('usuario_id', $usuarioId)->count();
                
            case 'logros':
                return UsuarioLogro::where('usuario_id', $usuarioId)->count();
                
            case 'experimentos':
                return CompletedActivity::where('usuario_id', $usuarioId)
                    ->where('activity_type', 'experimentos')
                    ->count();
                
            case 'quizzes':
                return CompletedActivity::where('usuario_id', $usuarioId)
                    ->where('activity_type', 'quiz')
                    ->count();
                
            default:
                return 0;
        }
    }

    /**
     * Verifica si un usuario ha completado esta meta
     */
    public function estaCompletada($usuarioId)
    {
        // Verificar si existe un registro en la tabla usuario_metas
        $completada = UsuarioMeta::where('usuario_id', $usuarioId)
                          ->where('meta_id', $this->id)
                          ->exists();
        
        // Si ya está registrada como completada, retornar true
        if ($completada) {
            return true;
        }
        
        // Si no está registrada, verificar si cumple los requisitos
        $progreso = $this->calcularProgreso($usuarioId);
        return $progreso >= $this->objetivo;
    }

    /**
     * Calcula el porcentaje de progreso
     */
    public function porcentajeProgreso($usuarioId)
    {
        if ($this->estaCompletada($usuarioId)) {
            return 100; // Ya está completada
        }
        
        $progreso = $this->calcularProgreso($usuarioId);
        return $this->objetivo > 0 ? min(round(($progreso / $this->objetivo) * 100), 100) : 0;
    }
    
    /**
     * Verifica y registra la meta como completada si cumple los requisitos
     */
    public function verificarYCompletar($usuarioId)
    {
        // Si ya está completada, no hacer nada
        if (UsuarioMeta::where('usuario_id', $usuarioId)->where('meta_id', $this->id)->exists()) {
            return false;
        }

        // Verificar si cumple los requisitos
        $progreso = $this->calcularProgreso($usuarioId);
        
        // Verificación estricta: el progreso debe ser mayor o igual al objetivo
        // y el objetivo debe ser mayor que cero para evitar completados incorrectos
        if ($progreso >= $this->objetivo && $this->objetivo > 0) {
            // Registrar información de depuración
            \Log::info('Completando meta: ' . $this->nombre . ' para usuario: ' . $usuarioId . 
                      ' - Progreso: ' . $progreso . ' - Objetivo: ' . $this->objetivo);
            
            // Registrar la meta como completada
            UsuarioMeta::create([
                'usuario_id' => $usuarioId,
                'meta_id' => $this->id,
                'completada_en' => now(),
            ]);

            // Actualizar puntos en el ranking
            Ranking::updateOrInsert(
                ['usuario_id' => $usuarioId],
                [
                    'total_points' => \DB::raw('total_points + ' . ($this->puntos ?: 0)),
                    'updated_at' => now()
                ]
            );

            return true; // Meta completada
        }

        return false; // No cumple los requisitos
    }
}