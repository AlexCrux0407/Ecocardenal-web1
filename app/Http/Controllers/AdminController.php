<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    /**
     * Muestra la vista de administración
     */
    public function index()
    {
        // Verificar si el usuario es administrador
        if (!session('es_admin')) {
            return redirect()->route('home')->with('error', 'No tienes permisos para acceder a esta sección.');
        }
        
        return view('admin.index');
    }
    
    /**
     * Resetea todos los logros de los usuarios
     */
    public function resetLogros(Request $request)
    {
        // Verificar si el usuario es administrador
        if (!session('es_admin')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tienes permisos para realizar esta acción.'
                ], 403);
            }
            return redirect()->route('home')->with('error', 'No tienes permisos para realizar esta acción.');
        }
        
        // Registrar el inicio de la operación
        Log::info('Iniciando reset de logros desde panel de administración');

        // Eliminar todos los registros de usuario_logros
        $count = DB::table('usuario_logros')->count();
        DB::table('usuario_logros')->delete();

        // Registrar el resultado
        Log::info("Se han eliminado $count registros de la tabla usuario_logros desde panel de administración");
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Se han eliminado $count registros de logros. Los usuarios deberán volver a desbloquear sus logros correctamente.",
                'count' => $count
            ]);
        }
        
        return redirect()->route('admin.index')->with('success', "Se han eliminado $count registros de logros. Los usuarios deberán volver a desbloquear sus logros correctamente.");
    }
}