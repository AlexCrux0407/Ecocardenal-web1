<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ControladorVistas;
use App\Http\Controllers\RegistroController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ActividadesController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\JuegoController;
use App\Http\Controllers\ManualidadesController;
use App\Http\Controllers\HistoriasController;
use App\Http\Controllers\InfoUsuarioController;
use App\Http\Controllers\RankingController;
use App\Http\Controllers\ProgresoController;
use App\Http\Controllers\TriviaController;
use App\Http\Controllers\ApiDocsController;
use App\Http\Controllers\ApiTesterController;
use App\Http\Controllers\FeedbackController;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\EstadisticasController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\AtencionClienteController;
use App\Http\Controllers\FaqController;

// Rutas públicas
Route::get('/', [LandingController::class, 'index'])->name('landing');
Route::get('/index', [ControladorVistas::class, 'index'])->name('index'); // <-- Mueve el index a otra ruta si lo necesitas
Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::get('/register', [RegistroController::class, 'index'])->name('registrar');
Route::post('/inicio', [LoginController::class, 'login'])->name('iniciar');
Route::post('/enviarusuario', [RegistroController::class, 'store'])->name('enviar');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

//ERP atencion al cliente
Route::get('/atencion-cliente', [AtencionClienteController::class, 'index'])->name('atencion.cliente');
Route::post('/atencion-cliente/enviar', [AtencionClienteController::class, 'enviar'])->name('atencion.cliente.enviar');
Route::get('/faq', [FaqController::class, 'index'])->name('faq.index');


// Análisis de sentimientos
Route::post('/sentiment-analysis', function (Request $request) {
    $comment = $request->input('comment');

    $response = Http::post('http://127.0.0.1:5000/analyze', [
        'text' => $comment
    ]);

    $sentiment = $response->json()['sentiment'];
    Feedback::create([
        'comment' => $comment,
        'sentiment' => $sentiment
    ]);

    return response()->json(['status' => 'success', 'sentiment' => $sentiment]);
});

// Rutas de actividades
Route::prefix('actividades')->group(function () {
    Route::get('/', [ActividadesController::class, 'actividades'])->name('actividades');
    
    // Quiz
    Route::get('/quiz', [QuizController::class, 'index'])->name('quiz.index');
    Route::post('/quiz/result', [QuizController::class, 'result'])->name('quiz.result');
    Route::get('/quiz/resultados', [ControladorVistas::class, 'quizresultado'])->name('quizResultado');
    
    // Juegos
    Route::get('/juego', [JuegoController::class, 'index'])->name('juego.index');
    Route::post('/juego/finish', [JuegoController::class, 'finishGame'])->name('juego.finish');
    
    // Manualidades
    Route::get('/manualidades', [ManualidadesController::class, 'index'])->name('manualidades.index');
    Route::post('/manualidades/completar', [ManualidadesController::class, 'completarManualidad'])->name('manualidades.completar');
    
    // Historias
    Route::get('/historias', [HistoriasController::class, 'index'])->name('historias.index');
    Route::post('/historias', [HistoriasController::class, 'store'])->name('historias.store');
    Route::delete('/historias/{id}', [HistoriasController::class, 'destroy'])->name('historias.destroy');
});

// Rutas de progreso
Route::prefix('progreso')->group(function () {
    Route::get('/', [ControladorVistas::class, 'progreso'])->name('progreso');
    Route::get('/estadisticas', [ProgresoController::class, 'estadisticas'])->name('progreso.estadisticas');
    Route::get('/logros', [ProgresoController::class, 'logros'])->name('progreso.logros');
    Route::get('/ranking', [RankingController::class, 'index'])->name('ranking.index');
    Route::get('/actividades', [ProgresoController::class, 'activities'])->name('progreso.activities');
});

// Configuración de usuario
Route::prefix('configuracion')->group(function () {
    Route::get('/', [ControladorVistas::class, 'configuracion'])->name('configuracion');
    Route::get('/InfoUsuario', [InfoUsuarioController::class, 'index'])->name('informacionUsuario');
    Route::put('/usuarioUpdate/{id}', [InfoUsuarioController::class, 'update'])->name('ActualizarUsuario');
});

// Rutas de administración
Route::prefix('admin')->group(function () {
    Route::get('/', [App\Http\Controllers\AdminController::class, 'index'])->name('admin.index');
    Route::post('/reset-logros', [App\Http\Controllers\AdminController::class, 'resetLogros'])->name('admin.reset-logros');
});

// API Routes
Route::prefix('api')->group(function () {
    // Documentación y testing
    Route::get('/docs', [ApiDocsController::class, 'index'])->name('api.docs');
    Route::get('/test-ui', [ApiTesterController::class, 'index'])->name('api.test');
    Route::get('/test', function () {
        return response()->json([
            'success' => true,
            'message' => 'API funcionando correctamente',
            'timestamp' => now()->toDateTimeString()
        ]);
    });

    // API v1
    Route::prefix('v1')->group(function () {
        Route::post('/auth/register', [App\Http\Controllers\API\AuthController::class, 'register']);
        Route::post('/auth/login', [App\Http\Controllers\API\AuthController::class, 'login']);
        Route::get('/activities', [App\Http\Controllers\API\ActivitiesController::class, 'index']);
        Route::get('/activities/stats', [App\Http\Controllers\API\ActivitiesController::class, 'stats']);
        Route::get('/activities/{id}', [App\Http\Controllers\API\ActivitiesController::class, 'show']);
    });
});

// Trivia
Route::prefix('trivia')->group(function () {
    Route::get('/', [TriviaController::class, 'index'])->name('trivia.index');
    Route::get('/quiz', [TriviaController::class, 'generateQuiz'])->name('trivia.generate');
    Route::post('/submit', [TriviaController::class, 'submitQuiz'])->name('trivia.submit');
});






Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
