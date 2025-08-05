<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class JuegoController extends Controller
{
    /**
     * Muestra la vista principal del juego.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('actividades.juego');
    }

    public function saveScore(Request $request)
    {

        return response()->json(['message' => 'Puntuación guardada correctamente']);
    }
    public function finishGame(Request $request)
    {
        $usuarioId = auth()->id();
        
        // Si no hay usuario autenticado, usar el ID de sesión
        if (!$usuarioId) {
            $usuarioId = session('usuario_id');
            
            if (!$usuarioId) {
                return response()->json(['error' => 'Usuario no autenticado'], 401);
            }
        }
        
        $gameName = "Juego de Memoria";
        $points = 50; // Puntos fijos asignados al juego

        // Registrar en la base de datos
        DB::table('completed_activities')->insert([
            'usuario_id' => $usuarioId,
            'activity_type' => 'game',
            'activity_name' => $gameName,
            'points' => $points,
            'completed_at' => now(),
        ]);
        
        // Actualizar el ranking
        DB::table('ranking')->updateOrInsert(
            ['usuario_id' => $usuarioId],
            [
                'total_points' => DB::raw('total_points + ' . $points),
                'updated_at' => now(),
            ]
        );

        return redirect()->route('progreso.activities')->with('success', '¡Juego completado! Has ganado ' . $points . ' puntos.');
    }

}
