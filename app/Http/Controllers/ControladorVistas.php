<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Faltaba esta importación
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Session;  // Faltaba esta importación
use Illuminate\Support\Facades\Log;

class ControladorVistas extends Controller
{
    public function index()
    {
        return view('index');
    }
    
    public function actividades()
    {
        return view('actividades');
    }

    public function progreso()
    {
        return view('progreso');
    }

    public function configuracion()
    {
        return view('configuracion');
    }

       public function verEstadisticas()
    {
        // Verificar si el usuario está logueado
        if (!Session::has('usuario_id')) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión primero');
        }

        $usuarioId = Session::get('usuario_id');

        try {
            // Obtener datos del ranking
            $ranking = DB::table('ranking')
                        ->where('usuario_id', $usuarioId)
                        ->first();
            
            // Obtener actividades completadas
            $actividadesCompletadas = DB::table('completed_activities')
                                      ->where('usuario_id', $usuarioId)
                                      ->count();
            
            // Obtener logros obtenidos
            $logrosObtenidos = DB::table('usuario_logros')
                               ->where('usuario_id', $usuarioId)
                               ->count();
            
            // Calcular porcentaje
            $puntosTotales = $ranking ? ($ranking->total_points ?? 0) : 0;
            $meta = 1000; // Meta de puntos
            $porcentajeCompletado = min(round(($puntosTotales / max($meta, 1)) * 100, 2), 100);

            return view('progreso.ver-estadisticas', [
                'porcentajeCompletado' => $porcentajeCompletado,
                'logrosObtenidos' => $logrosObtenidos,
                'puntosTotales' => $puntosTotales,
                'actividadesCompletadas' => $actividadesCompletadas,
                'nombreUsuario' => Session::get('nombre') // Agregar nombre del usuario
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error en verEstadisticas: ' . $e->getMessage());
            
            return view('progreso.ver-estadisticas', [
                'porcentajeCompletado' => 0,
                'logrosObtenidos' => 0,
                'puntosTotales' => 0,
                'actividadesCompletadas' => 0,
                'nombreUsuario' => Session::get('nombre')
            ]);
        }
    }

  public function logros()
{
    if (!session()->has('usuario_id')) {
        return redirect()->route('login')->with('error', 'Debes iniciar sesión primero');
    }

    $usuarioId = session('usuario_id');

    try {
        // Obtener todos los logros disponibles
        $todosLogros = DB::table('logros')->get();
        
        // Obtener logros desbloqueados por el usuario
        $logrosDesbloqueados = DB::table('usuario_logros')
            ->where('usuario_id', $usuarioId)
            ->pluck('logro_id')
            ->toArray();

        // Preparar datos para la vista con estructura consistente
        $logrosData = [];
        foreach ($todosLogros as $logro) {
            $logrosData[] = [
                'id' => $logro->id,
                'nombre' => $logro->nombre,
                'descripcion' => $logro->descripcion,
                'icono' => $this->obtenerIconoLogro($logro->id),
                'desbloqueado' => in_array($logro->id, $logrosDesbloqueados) // Asegurar que esta clave existe
            ];
        }

        return view('progreso.logros', [
            'logros' => $logrosData,
            'totalLogros' => count($todosLogros),
            'logrosDesbloqueados' => count($logrosDesbloqueados)
        ]);

    } catch (\Exception $e) {
        \Log::error('Error en logros: ' . $e->getMessage());
        return view('progreso.logros', [
            'logros' => [],
            'totalLogros' => 0,
            'logrosDesbloqueados' => 0
        ]);
    }
}

private function obtenerIconoLogro($logroId)
{
    $iconos = [
        1 => 'fa-medal text-warning',
        2 => 'fa-trophy text-success',
        3 => 'fa-star text-primary',
        4 => 'fa-award text-danger',
        5 => 'fa-gem text-info',
        6 => 'fa-crown text-gold'
    ];

    return $iconos[$logroId] ?? 'fa-certificate text-secondary';
}

 public function metas()
{
    if (!session()->has('usuario_id')) {
        return redirect()->route('login')->with('error', 'Debes iniciar sesión primero');
    }

    $usuarioId = session('usuario_id');

    try {
        // Obtener todas las metas disponibles (sin la columna objetivo)
        $metas = DB::table('metas')
            ->select('id', 'nombre', 'descripcion', 'puntos') // Solo columnas existentes
            ->get();
        
        // Obtener estadísticas del usuario
        $estadisticas = [
            'puntos' => DB::table('ranking')->where('usuario_id', $usuarioId)->value('total_points') ?? 0,
            'actividades' => DB::table('completed_activities')->where('usuario_id', $usuarioId)->count(),
            'logros' => DB::table('usuario_logros')->where('usuario_id', $usuarioId)->count()
        ];

        // Preparar datos para la vista con valores por defecto
        $metasData = [];
        foreach ($metas as $meta) {
            // Asignar objetivos por defecto basados en el ID de meta
            $objetivo = $this->obtenerObjetivoPorDefecto($meta->id);
            
            // Calcular progreso basado en estadísticas
            $progresoActual = $this->calcularProgreso($meta->id, $estadisticas);
            $porcentaje = $objetivo > 0 ? min(round(($progresoActual / $objetivo) * 100), 100) : 0;
            $completada = $progresoActual >= $objetivo;
            
            $metasData[] = [
                'id' => $meta->id,
                'nombre' => $meta->nombre,
                'descripcion' => $meta->descripcion,
                'objetivo' => $objetivo,
                'actual' => $progresoActual,
                'porcentaje' => $porcentaje,
                'completada' => $completada,
                'puntos' => $meta->puntos
            ];
        }

        return view('progreso.metas', [
            'metas' => $metasData,
            'totalMetas' => count($metas),
            'metasCompletadas' => collect($metasData)->where('completada', true)->count()
        ]);

    } catch (\Exception $e) {
        \Log::error('Error en metas: ' . $e->getMessage());
        return view('progreso.metas', [
            'metas' => [],
            'totalMetas' => 0,
            'metasCompletadas' => 0
        ]);
    }
}

private function obtenerObjetivoPorDefecto($metaId)
{
    // Define objetivos por defecto basados en el ID de meta
    $objetivos = [
        1 => 50,   // Meta 1: 50 puntos
        2 => 5,     // Meta 2: 5 actividades
        3 => 3,     // Meta 3: 3 logros
        4 => 200,   // Meta 4: 200 puntos
        5 => 7,     // Meta 5: 7 días consecutivos
        // Agrega más según tus necesidades
    ];

    return $objetivos[$metaId] ?? 1; // Valor por defecto si no está definido
}

private function calcularProgreso($metaId, $estadisticas)
{
    // Asigna progreso basado en el tipo de meta (puedes ajustar según tu lógica)
    switch ($metaId) {
        case 1: case 4: case 10: // Metas de puntos
            return $estadisticas['puntos'];
        case 2: case 6: // Metas de actividades
            return $estadisticas['actividades'];
        case 3: // Metas de logros
            return $estadisticas['logros'];
        default:
            return 0;
    }
}


    public function quizresultado() {
        return view('actividades.quiz-result');
    }
    
    



}

