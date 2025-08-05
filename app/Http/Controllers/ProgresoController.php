<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\CompletedActivity;
use App\Models\Ranking;
use App\Models\Logro;
use App\Models\UsuarioLogro;

class ProgresoController extends Controller
{
    public function activities()
    {
        $usuarioId = session('usuario_id'); // Recupera el ID del usuario desde la sesión

        if ($usuarioId) { // Verifica si el usuario está autenticado
            $activities = CompletedActivity::where('usuario_id', $usuarioId)
                ->orderBy('completed_at', 'desc')
                ->get();

            return view('progreso.activities', compact('activities'));
        } else {
            // Redirige al login si no hay usuario en la sesión
            return redirect()->route('login')->with('error', 'Por favor, inicia sesión para ver tus actividades.');
        }
    }
    

    
    /**
     * Muestra la vista de logros del usuario
     */
    public function logros()
    {
        $usuarioId = session('usuario_id');
        
        if (!$usuarioId) {
            return redirect()->route('login')->with('error', 'Por favor, inicia sesión para ver tus logros.');
        }
        
        // Verificar todos los logros para ver si se han desbloqueado nuevos
        $logrosVerificados = $this->verificarLogros($usuarioId);
        \Log::info('Logros verificados: ' . json_encode($logrosVerificados));
        
        // Obtener todos los logros
        $logros = Logro::all();
        
        // Preparar datos para la vista
        $logrosData = [];
        foreach ($logros as $logro) {
            $desbloqueado = $logro->estaDesbloqueado($usuarioId);
            $porcentaje = $logro->porcentajeProgreso($usuarioId);
            $progreso = $logro->calcularProgreso($usuarioId);
            
            \Log::info('Preparando datos para vista - Logro: ' . $logro->nombre . 
                      ' - Desbloqueado: ' . ($desbloqueado ? 'Sí' : 'No') . 
                      ' - Progreso: ' . $progreso . 
                      ' - Objetivo: ' . $logro->objetivo . 
                      ' - Porcentaje: ' . $porcentaje);
            
            $logrosData[] = [
                'id' => $logro->id,
                'nombre' => $logro->nombre,
                'descripcion' => $logro->descripcion,
                'icono' => $logro->icono,
                'desbloqueado' => $desbloqueado,
                'porcentaje' => $porcentaje,
                'progreso' => $progreso,
                'objetivo' => $logro->objetivo
            ];
        }
        
        // Obtener logros desbloqueados
        $logrosDesbloqueados = UsuarioLogro::where('usuario_id', $usuarioId)->count();
        
        // Registrar información de depuración sobre el conteo de logros
        \Log::info('Conteo de logros desbloqueados para usuario ' . $usuarioId . ': ' . $logrosDesbloqueados);
        
        // Verificar si hay discrepancias entre el conteo y los datos preparados
        $logrosDesbloqueadosEnData = collect($logrosData)->where('desbloqueado', true)->count();
        if ($logrosDesbloqueados != $logrosDesbloqueadosEnData) {
            \Log::warning('Discrepancia en el conteo de logros - En DB: ' . $logrosDesbloqueados . 
                         ' - En datos preparados: ' . $logrosDesbloqueadosEnData);
        }
        
        return view('progreso.logros', [
            'logros' => $logrosData,
            'totalLogros' => count($logros),
            'logrosDesbloqueados' => $logrosDesbloqueados
        ]);
    }
    
    /**
     * Verifica si el usuario ha desbloqueado nuevos logros
     */
    private function verificarLogros($usuarioId)
    {
        $logros = Logro::all();
        $logrosVerificados = [];
        
        foreach ($logros as $logro) {
            // Registrar información de depuración antes de verificar
            $progreso = $logro->calcularProgreso($usuarioId);
            $objetivo = $logro->objetivo;
            $tipo = $logro->tipo;
            
            \Log::info('Verificando logro: ' . $logro->nombre . ' (ID: ' . $logro->id . ')' . 
                      ' - Usuario: ' . $usuarioId . 
                      ' - Tipo: ' . $tipo . 
                      ' - Progreso actual: ' . $progreso . 
                      ' - Objetivo: ' . $objetivo);
            
            $desbloqueado = $logro->verificarYDesbloquear($usuarioId);
            
            $logrosVerificados[] = [
                'id' => $logro->id,
                'nombre' => $logro->nombre,
                'desbloqueado' => $desbloqueado
            ];
        }
        
        return $logrosVerificados;
    }
    

    
    /**
     * Muestra las estadísticas del usuario
     */
    public function estadisticas()
    {
        $usuarioId = session('usuario_id');
        
        if (!$usuarioId) {
            return redirect()->route('login')->with('error', 'Por favor, inicia sesión para ver tus estadísticas.');
        }
        
        // Verificar logros para actualizar el progreso
        $this->verificarLogros($usuarioId);
        
        // Obtener datos del ranking
        $ranking = Ranking::where('usuario_id', $usuarioId)->first();
        $puntosTotales = $ranking ? $ranking->total_points : 0;
        
        // Obtener actividades completadas
        $actividadesCompletadas = CompletedActivity::where('usuario_id', $usuarioId)->count();
        
        // Obtener logros obtenidos y total de logros
        $logrosObtenidos = UsuarioLogro::where('usuario_id', $usuarioId)->count();
        $totalLogros = Logro::count();
        
        // Calcular porcentaje de progreso general (basado en puntos)
        $meta = 1000; // Meta de puntos
        $porcentajeCompletado = min(round(($puntosTotales / max($meta, 1)) * 100, 2), 100);
        
        return view('progreso.ver-estadisticas', [
            'porcentajeCompletado' => $porcentajeCompletado,
            'logrosObtenidos' => $logrosObtenidos,
            'puntosTotales' => $puntosTotales,
            'actividadesCompletadas' => $actividadesCompletadas,
            'nombreUsuario' => session('nombre'),
            'totalLogros' => $totalLogros,
            'logrosDesbloqueados' => $logrosObtenidos
        ]);
    }
}
