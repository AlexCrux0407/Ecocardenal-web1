<?php
namespace App\Http\Controllers;

use App\Models\CompletedActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class QuizController extends Controller
{
    /**
     * Muestra la vista del Quiz.
     */
    public function index()
    {
        return view('actividades.quiz');
    }

    /**
     * Procesa las respuestas del Quiz y muestra los resultados.
     */
    public function result(Request $request)
    {
        // Recuperamos las respuestas del quiz desde el formulario
        $answers = $request->only([
            'question1', 'question2', 'question3', 'question4', 'question5',
            'question6', 'question7', 'question8', 'question9', 'question10'
        ]);

        // Definir las respuestas correctas
        $correctAnswers = [
            'question1' => 'b',
            'question2' => 'b',
            'question3' => 'c',
            'question4' => 'b',
            'question5' => 'a',
            'question6' => 'b',
            'question7' => 'b',
            'question8' => 'b',
            'question9' => 'c',
            'question10' => 'b',
        ];

        // Inicializamos el puntaje
        $score = 0;

        // Calculamos el puntaje comparando las respuestas del usuario con las correctas
        foreach ($answers as $question => $answer) {
            if (isset($correctAnswers[$question]) && $correctAnswers[$question] === $answer) {
                $score++;
            }
        }

        // Guardamos los resultados en la base de datos
        $usuario_id = session('usuario_id');
        CompletedActivity::create([
            'usuario_id' => $usuario_id,
            'activity_type' => 'quiz',
            'activity_name' => 'Quiz de Naturaleza',
            'points' => $score,
            'completed_at' => now(),
        ]);
        

        //mandamos la info al ranking
        \DB::table('ranking')->updateOrInsert(
            ['usuario_id' => $usuario_id], // Si el usuario ya existe en la tabla, se actualiza
            [
                'total_points' => \DB::raw('total_points + ' . $score), // Incrementamos los puntos
                'updated_at' => now(),
            ]
        );
        
        // Calculamos el total de preguntas para la vista
        $total = count($correctAnswers);

        // Retornamos los resultados a la vista
        return view('actividades.quiz-result', compact('score', 'total'));
    }
}
