<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\CompletedActivity;
use App\Models\Ranking;

class ManualidadesController extends Controller
{
    /**
     * Muestra la vista de manualidades
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('actividades.manualidades');
    }
    
    /**
     * Registra la finalización de una manualidad y asigna puntos
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function completarManualidad(Request $request)
    {
        $usuarioId = session('usuario_id');
        
        if (!$usuarioId) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión para registrar tu actividad.');
        }
        
        $manualidadId = $request->input('manualidad_id', 1);
        $nombreManualidad = $request->input('nombre_manualidad', 'Manualidad Reciclable');
        $puntos = 15; // Puntos asignados por completar una manualidad
        
        try {
            // Registrar la actividad completada
            CompletedActivity::create([
                'usuario_id' => $usuarioId,
                'activity_type' => 'manualidades',
                'activity_name' => $nombreManualidad,
                'points' => $puntos,
                'completed_at' => now(),
            ]);
            
            // Actualizar el ranking del usuario
            Ranking::updateOrInsert(
                ['usuario_id' => $usuarioId],
                [
                    'total_points' => DB::raw('total_points + ' . $puntos),
                    'updated_at' => now(),
                ]
            );
            
            return redirect()->back()
                ->with('success', '¡Manualidad completada! Has ganado ' . $puntos . ' puntos. Puedes ver tus actividades completadas en la sección de Progreso.');
                
        } catch (\Exception $e) {
            Log::error('Error al registrar manualidad completada: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Ocurrió un error al registrar tu actividad. Por favor, inténtalo de nuevo o contacta a soporte.');
        }
    }
}
