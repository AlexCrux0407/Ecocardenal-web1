<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Historia;
use App\Models\CompletedActivity;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class HistoriasController extends Controller
{
    /**
     * Muestra la vista principal con todas las historias.
     */
    public function index()
    {
        $historias = Historia::orderBy('created_at', 'desc')->get();
        return view('historias.index', compact('historias'));
    }

    /**
     * Guarda una nueva historia en la base de datos.
     */
    /**
     * Lista de palabras inapropiadas para filtrar
     */
    private $palabrasInapropiadas = [
        'maldito', 'maldita', 'idiota', 'estúpido', 'estupido', 'estúpida', 'estupida',
        'pendejo', 'pendeja', 'imbécil', 'imbecil', 'cabrón', 'cabron', 'puta', 'puto',
        'mierda', 'joder', 'jodido', 'jodida', 'carajo', 'coño', 'cono', 'verga',
        'pinche', 'chinga', 'chingar', 'culero', 'culera', 'marica', 'maricón', 'maricon',
        'perra', 'perro', 'zorra', 'zorro', 'pene', 'vagina', 'culo', 'tetas', 'chichis',
        'follar', 'coger', 'putear', 'putamadre', 'putada', 'maldición', 'maldicion',
        'bastardo', 'bastarda', 'cabrona', 'mamón', 'mamon', 'mamona', 'cagar', 'cagada',
        'cagado', 'cagada', 'meco', 'puñetas', 'punetas', 'puñetero', 'punetero', 'gilipollas'
    ];
    
    /**
     * Verifica si el texto contiene palabras inapropiadas
     */
    private function contienePalabrasInapropiadas($texto)
    {
        $textoLower = mb_strtolower($texto, 'UTF-8');
        
        foreach ($this->palabrasInapropiadas as $palabra) {
            if (strpos($textoLower, $palabra) !== false) {
                return true;
            }
        }
        
        return false;
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'nullable|string|max:255',
            'contenido' => 'required|string',
        ]);
        
        // Verificar si el contenido tiene palabras inapropiadas
        if ($this->contienePalabrasInapropiadas($request->input('contenido'))) {
            return redirect()->route('historias.index')
                ->with('error', 'Tu historia contiene palabras inapropiadas. Por favor, modifica el contenido.');
        }
        
        // Verificar si el nombre tiene palabras inapropiadas
        $nombre = $request->input('nombre');
        if ($nombre && $this->contienePalabrasInapropiadas($nombre)) {
            return redirect()->route('historias.index')
                ->with('error', 'El nombre contiene palabras inapropiadas. Por favor, modifica el nombre.');
        }
        
        // Crear la historia
        $historia = Historia::create([
            'nombre' => $nombre ?: 'Anónimo',
            'contenido' => $request->input('contenido'),
        ]);
        
        // Registrar la actividad completada
        $usuarioId = session('usuario_id');
        
        if ($usuarioId) {
            // Registrar en la tabla de actividades completadas
            $points = 10; // Puntos por crear una historia
            
            DB::table('completed_activities')->insert([
                'usuario_id' => $usuarioId,
                'activity_type' => 'historias',
                'activity_name' => 'Historia: ' . substr($historia->contenido, 0, 30) . '...',
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
            
            return redirect()->route('historias.index')
                ->with('success', '¡Historia publicada exitosamente! Has ganado ' . $points . ' puntos.');
        }
        
        return redirect()->route('historias.index')->with('success', '¡Historia publicada exitosamente!');
    }

    /**
     * Elimina una historia específica.
     */
    public function destroy($id)
    {
        $historia = Historia::findOrFail($id);
        $historia->delete();

        return redirect()->route('historias.index')->with('success', '¡Historia eliminada exitosamente!');
    }
}
