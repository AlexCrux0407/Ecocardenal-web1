<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CompletedActivity;
use App\Models\Usuario;
use App\Models\Ranking;
use App\Models\Logro;
use App\Models\UsuarioLogro;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class UserProgressController extends Controller
{
    /**
     * Obtener las actividades completadas por el usuario autenticado
     */
    public function activities(Request $request)
    {
        // En una implementación completa, usarías Auth::id()
        // Por ahora usamos ID de sesión como ejemplo
        $userId = session('usuario_id');
        
        if (!$userId) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no autenticado'
            ], 401);
        }
        
        $activities = CompletedActivity::where('usuario_id', $userId)
            ->orderBy('completed_at', 'desc')
            ->get();
            
        return response()->json([
            'success' => true,
            'data' => $activities
        ]);
    }
    
    /**
     * Registrar una actividad como completada
     */
    public function completeActivity(Request $request)
    {
        // Validación de datos
        $validator = Validator::make($request->all(), [
            'activity_type' => 'required|string|in:quiz,quizzes,game,juego,trivia,manualidades,historias,experimentos,experimento',
            'activity_name' => 'required|string|max:255',
            'points' => 'required|integer|min:0'
        ]);
        
        if ($validator->fails()) {
            \Log::warning('API - Validación fallida para completeActivity: ' . json_encode($validator->errors()));
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        
        // En una implementación completa, usarías Auth::id()
        $userId = session('usuario_id');
        
        if (!$userId) {
            \Log::warning('API - Intento de completar actividad sin autenticación');
            return response()->json([
                'success' => false,
                'message' => 'Usuario no autenticado'
            ], 401);
        }
        
        try {
            \Log::info('API - Registrando actividad completada - Usuario: ' . $userId . 
                      ' - Tipo: ' . $request->input('activity_type') . 
                      ' - Nombre: ' . $request->input('activity_name') . 
                      ' - Puntos: ' . $request->input('points'));
            
            // Guardar la actividad completada
            $activity = CompletedActivity::create([
                'usuario_id' => $userId,
                'activity_type' => $request->input('activity_type'),
                'activity_name' => $request->input('activity_name'),
                'points' => $request->input('points'),
                'completed_at' => now()
            ]);
            
            \Log::info('API - Actividad registrada con ID: ' . $activity->id);
            
            // Actualizar el ranking
            $puntosAnteriores = 0;
            $ranking = Ranking::where('usuario_id', $userId)->first();
            if ($ranking) {
                $puntosAnteriores = $ranking->total_points;
            }
            
            Ranking::updateOrInsert(
                ['usuario_id' => $userId],
                [
                    'total_points' => \DB::raw('total_points + ' . ($request->input('points') ?: 0)),
                    'updated_at' => now()
                ]
            );
            
            $puntosNuevos = $puntosAnteriores + $request->input('points');
            \Log::info('API - Puntos actualizados - Usuario: ' . $userId . 
                      ' - Anterior: ' . $puntosAnteriores . 
                      ' - Sumados: ' . $request->input('points') . 
                      ' - Nuevo total: ' . $puntosNuevos);
            
            // Verificar y desbloquear logros
            $unlockedAchievements = $this->verificarLogros($userId);
            
            return response()->json([
                'success' => true,
                'message' => 'Actividad completada con éxito',
                'data' => $activity,
                'achievements_unlocked' => $unlockedAchievements
            ]);
            
        } catch (\Exception $e) {
            \Log::error('API - Error al registrar actividad: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar la actividad',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Obtener el progreso general del usuario
     */
    public function getProgress()
    {
        $userId = session('usuario_id');
        
        if (!$userId) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no autenticado'
            ], 401);
        }
        
        // Obtener puntos totales
        $ranking = Ranking::where('usuario_id', $userId)->first();
        $totalPoints = $ranking ? $ranking->total_points : 0;
        
        // Calcular actividades completadas
        $activitiesCount = CompletedActivity::where('usuario_id', $userId)->count();
        
        // Calcular distribución por tipo
        $distribution = CompletedActivity::where('usuario_id', $userId)
            ->select('activity_type', \DB::raw('count(*) as count'))
            ->groupBy('activity_type')
            ->get()
            ->pluck('count', 'activity_type')
            ->toArray();
            
        // Actividad más reciente
        $latestActivity = CompletedActivity::where('usuario_id', $userId)
            ->orderBy('completed_at', 'desc')
            ->first();
        
        // Contar logros desbloqueados
        $achievementsUnlocked = UsuarioLogro::where('usuario_id', $userId)->count();
        $totalAchievements = Logro::count();
            
        return response()->json([
            'success' => true,
            'data' => [
                'total_points' => $totalPoints,
                'activities_completed' => $activitiesCount,
                'type_distribution' => $distribution,
                'latest_activity' => $latestActivity,
                'achievements' => [
                    'unlocked' => $achievementsUnlocked,
                    'total' => $totalAchievements,
                    'percentage' => $totalAchievements > 0 ? round(($achievementsUnlocked / $totalAchievements) * 100) : 0
                ]
            ]
        ]);
    }
    
    /**
     * Obtener los logros del usuario
     */
    public function getAchievements()
    {
        $userId = session('usuario_id');
        
        if (!$userId) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no autenticado'
            ], 401);
        }
        
        // Obtener todos los logros disponibles
        $logros = Logro::all();
        $result = [];
        
        foreach ($logros as $logro) {
            // Verificar si el logro está desbloqueado
            $desbloqueado = $logro->estaDesbloqueado($userId);
            
            // Calcular el progreso actual
            $progreso = $logro->calcularProgreso($userId);
            $porcentaje = $logro->porcentajeProgreso($userId);
            
            $result[] = [
                'id' => $logro->id,
                'name' => $logro->nombre,
                'description' => $logro->descripcion,
                'icon' => $logro->icono,
                'unlocked' => $desbloqueado,
                'progress' => $porcentaje,
                'current_progress' => $progreso,
                'target' => $logro->objetivo,
                'reward_points' => $logro->puntos_recompensa,
                'type' => $logro->tipo
            ];
        }
        
        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }
    
    /**
     * Verificar y desbloquear logros para un usuario
     */
    private function verificarLogros($userId)
    {
        \Log::info('API - Iniciando verificación de logros para usuario: ' . $userId);
        
        $logros = Logro::all();
        $desbloqueados = [];
        
        foreach ($logros as $logro) {
            // Registrar información de depuración antes de verificar
            $progreso = $logro->calcularProgreso($userId);
            $objetivo = $logro->objetivo;
            $tipo = $logro->tipo;
            
            \Log::info('API - Verificando logro: ' . $logro->nombre . ' (ID: ' . $logro->id . ')' . 
                      ' - Usuario: ' . $userId . 
                      ' - Tipo: ' . $tipo . 
                      ' - Progreso actual: ' . $progreso . 
                      ' - Objetivo: ' . $objetivo);
            
            $desbloqueado = $logro->verificarYDesbloquear($userId);
            
            \Log::info('API - Resultado verificación: ' . ($desbloqueado ? 'DESBLOQUEADO' : 'No desbloqueado'));
            
            if ($desbloqueado) {
                $desbloqueados[] = [
                    'id' => $logro->id,
                    'name' => $logro->nombre,
                    'description' => $logro->descripcion,
                    'icon' => $logro->icono,
                    'reward_points' => $logro->puntos_recompensa,
                    'progreso' => $progreso,
                    'objetivo' => $objetivo
                ];
                
                // Si el logro otorga puntos, actualizar el ranking
                if ($logro->puntos_recompensa > 0) {
                    \Log::info('API - Otorgando ' . $logro->puntos_recompensa . ' puntos por logro desbloqueado: ' . $logro->nombre);
                    
                    Ranking::updateOrInsert(
                        ['usuario_id' => $userId],
                        [
                            'total_points' => \DB::raw('total_points + ' . ($logro->puntos_recompensa ?: 0)),
                            'updated_at' => now()
                        ]
                    );
                }
            }
        }
        
        \Log::info('API - Logros desbloqueados en esta sesión: ' . json_encode($desbloqueados));
        return $desbloqueados;
    }
}