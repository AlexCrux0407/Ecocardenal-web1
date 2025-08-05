<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Helpers\LogHelper;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class TriviaController extends Controller
{
    private $baseUrl = 'https://opentdb.com/api.php';
    
    /**
     * Mostrar la página principal de Trivia
     */
    public function index()
    {
        // Obtener las categorías disponibles
        try {
            $categoriesResponse = Http::get('https://opentdb.com/api_category.php');
            $categories = $categoriesResponse->successful() 
                ? $categoriesResponse->json()['trivia_categories'] 
                : [];
                
            return view('trivia.index', compact('categories'));
        } catch (\Exception $e) {
            Log::error('Error al obtener categorías de Trivia: ' . $e->getMessage());
            return view('trivia.index', ['categories' => [], 'error' => 'No se pudieron cargar las categorías.']);
        }
    }
    
    /**
     * Generar un nuevo quiz basado en los parámetros seleccionados
     */
    public function generateQuiz(Request $request)
    {
        $amount = $request->input('amount', 10);
        $category = $request->input('category', '');
        $difficulty = $request->input('difficulty', '');
        $type = $request->input('type', '');
        
        try {
            $params = [
                'amount' => $amount
            ];
            
            if (!empty($category) && $category != 'any') {
                $params['category'] = $category;
            }
            
            if (!empty($difficulty) && $difficulty != 'any') {
                $params['difficulty'] = $difficulty;
            }
            
            if (!empty($type) && $type != 'any') {
                $params['type'] = $type;
            }
            
            $response = Http::get($this->baseUrl, $params);
            
            if ($response->successful()) {
                $quizData = $response->json();
                
                if ($quizData['response_code'] == 0) {
                    // Guardar el quiz en la sesión para acceder posteriormente
                    session(['current_quiz' => $quizData['results']]);
                    return view('trivia.quiz', [
                        'questions' => $quizData['results'],
                        'amount' => $amount,
                        'category' => $this->getCategoryName($category),
                        'difficulty' => ucfirst($difficulty)
                    ]);
                } else {
                    // Manejar códigos de respuesta de error
                    $errorMessage = $this->getErrorMessage($quizData['response_code']);
                    return redirect()->route('trivia.index')->with('error', $errorMessage);
                }
            } else {
                return redirect()->route('trivia.index')->with('error', 'Error al conectar con la API de Trivia.');
            }
        } catch (\Exception $e) {
            Log::error('Error al generar quiz: ' . $e->getMessage());
            return redirect()->route('trivia.index')->with('error', 'Error al generar el quiz: ' . $e->getMessage());
        }
    }
    
    /**
     * Procesar los resultados del quiz
     */
    public function submitQuiz(Request $request)
    {
        $questions = session('current_quiz');
        $userAnswers = $request->except('_token');
        
        $score = 0;
        $results = [];
        
        if (!$questions) {
            return redirect()->route('trivia.index')->with('error', 'No se encontró ningún quiz activo.');
        }
        
        foreach ($questions as $index => $question) {
            $questionNumber = $index + 1;
            $userAnswer = $userAnswers['question_' . $questionNumber] ?? null;
            $correctAnswer = htmlspecialchars_decode($question['correct_answer']);
            
            $isCorrect = $userAnswer === $correctAnswer;
            if ($isCorrect) {
                $score++;
            }
            
            $results[] = [
                'question' => htmlspecialchars_decode($question['question']),
                'user_answer' => $userAnswer,
                'correct_answer' => $correctAnswer,
                'is_correct' => $isCorrect
            ];
        }
        
        // Calcular porcentaje
        $percentage = count($questions) > 0 ? round(($score / count($questions)) * 100) : 0;
        
        // Guardar resultados en bd si quisieras
        // Ver si el usuario está logeado
        $userId = session('usuario_id');
        if ($userId) {
            \DB::table('completed_activities')->insert([
                'usuario_id' => $userId,
                'activity_type' => 'trivia',
                'activity_name' => 'Quiz de Trivia',
                'points' => $score,
                'completed_at' => now(),
            ]);
            
            // Actualizar ranking
            \DB::table('ranking')->updateOrInsert(
                ['usuario_id' => $userId],
                [
                    'total_points' => \DB::raw('total_points + ' . ($score ?: 0)),
                    'updated_at' => now(),
                ]
            );
        }
        
        return view('trivia.results', compact('results', 'score', 'percentage', 'questions'));
    }
    
    /**
     * Obtener el nombre de la categoría a partir de su ID
     */
    private function getCategoryName($categoryId)
    {
        $categories = [
            '9' => 'Conocimiento General',
            '10' => 'Libros',
            '11' => 'Películas',
            '12' => 'Música',
            '13' => 'Musicales y Teatros',
            '14' => 'Televisión',
            '15' => 'Videojuegos',
            '16' => 'Juegos de Mesa',
            '17' => 'Ciencia y Naturaleza',
            '18' => 'Informática',
            '19' => 'Matemáticas',
            '20' => 'Mitología',
            '21' => 'Deportes',
            '22' => 'Geografía',
            '23' => 'Historia',
            '24' => 'Política',
            '25' => 'Arte',
            '26' => 'Celebridades',
            '27' => 'Animales',
            '28' => 'Vehículos',
            '29' => 'Comics',
            '30' => 'Gadgets',
            '31' => 'Anime y Manga',
            '32' => 'Dibujos Animados'
        ];
        
        return $categories[$categoryId] ?? 'Cualquier Categoría';
    }
    
    /**
     * Obtener mensaje de error basado en el código de respuesta
     */
    private function getErrorMessage($responseCode)
    {
        $errorMessages = [
            1 => 'No hay suficientes preguntas para los parámetros seleccionados. Intenta reducir la cantidad o cambiar la categoría/dificultad.',
            2 => 'Parámetros de consulta inválidos. Por favor, verifica tu selección.',
            3 => 'Token no encontrado.',
            4 => 'Token vacío. Reinicia el token.',
            5 => 'Error de conexión al servidor.'
        ];
        
        return $errorMessages[$responseCode] ?? 'Error desconocido. Por favor, intenta nuevamente.';
    }
}