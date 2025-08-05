<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Verificar si la tabla logros existe
        if (Schema::hasTable('logros')) {
            // Verificar si las columnas no existen antes de agregarlas
            if (!Schema::hasColumn('logros', 'icono')) {
                Schema::table('logros', function (Blueprint $table) {
                    $table->string('icono')->default('fa-trophy')->after('descripcion');
                });
            }
            
            if (!Schema::hasColumn('logros', 'tipo')) {
                Schema::table('logros', function (Blueprint $table) {
                    $table->string('tipo')->default('actividades')->after('icono'); // actividades, puntos, experimentos, quizzes
                });
            }
            
            if (!Schema::hasColumn('logros', 'objetivo')) {
                Schema::table('logros', function (Blueprint $table) {
                    $table->integer('objetivo')->default(1)->after('tipo');
                });
            }
            
            if (!Schema::hasColumn('logros', 'puntos_recompensa')) {
                Schema::table('logros', function (Blueprint $table) {
                    $table->integer('puntos_recompensa')->default(20)->after('objetivo');
                });
            }
            
            if (!Schema::hasColumn('logros', 'created_at') || !Schema::hasColumn('logros', 'updated_at')) {
                Schema::table('logros', function (Blueprint $table) {
                    $table->timestamps();
                });
            }
            
            // Actualizar los logros existentes con los valores correctos
            $logros = [
                [
                    'id' => 1,
                    'nombre' => 'Primeros Pasos',
                    'descripcion' => 'Completaste tu primera actividad',
                    'icono' => 'fa-medal',
                    'tipo' => 'actividades',
                    'objetivo' => 1,
                    'puntos_recompensa' => 10
                ],
                [
                    'id' => 2,
                    'nombre' => 'Aprendiz Ecológico',
                    'descripcion' => 'Completaste 5 actividades',
                    'icono' => 'fa-seedling',
                    'tipo' => 'actividades',
                    'objetivo' => 5,
                    'puntos_recompensa' => 20
                ],
                [
                    'id' => 3,
                    'nombre' => 'Guardian del Planeta',
                    'descripcion' => 'Completaste 10 actividades',
                    'icono' => 'fa-globe-americas',
                    'tipo' => 'actividades',
                    'objetivo' => 10,
                    'puntos_recompensa' => 30
                ],
                [
                    'id' => 4,
                    'nombre' => 'Experto en Sostenibilidad',
                    'descripcion' => 'Completaste 20 actividades',
                    'icono' => 'fa-leaf',
                    'tipo' => 'actividades',
                    'objetivo' => 20,
                    'puntos_recompensa' => 40
                ],
                [
                    'id' => 5,
                    'nombre' => 'Pionero Verde',
                    'descripcion' => 'Alcanzaste 50 puntos',
                    'icono' => 'fa-tree',
                    'tipo' => 'puntos',
                    'objetivo' => 50,
                    'puntos_recompensa' => 25
                ],
                [
                    'id' => 6,
                    'nombre' => 'Héroe Ambiental',
                    'descripcion' => 'Alcanzaste 100 puntos',
                    'icono' => 'fa-mountain',
                    'tipo' => 'puntos',
                    'objetivo' => 100,
                    'puntos_recompensa' => 35
                ],
                [
                    'id' => 7,
                    'nombre' => 'Maestro de la Conservación',
                    'descripcion' => 'Alcanzaste 200 puntos',
                    'icono' => 'fa-water',
                    'tipo' => 'puntos',
                    'objetivo' => 200,
                    'puntos_recompensa' => 45
                ],
                [
                    'id' => 8,
                    'nombre' => 'Leyenda Ecológica',
                    'descripcion' => 'Alcanzaste 500 puntos',
                    'icono' => 'fa-crown',
                    'tipo' => 'puntos',
                    'objetivo' => 500,
                    'puntos_recompensa' => 60
                ],
                [
                    'id' => 9,
                    'nombre' => 'Explorador Natural',
                    'descripcion' => 'Completaste todas las actividades de exploración',
                    'icono' => 'fa-compass',
                    'tipo' => 'experimentos',
                    'objetivo' => 5,
                    'puntos_recompensa' => 50
                ],
                [
                    'id' => 10,
                    'nombre' => 'Científico Ciudadano',
                    'descripcion' => 'Participaste en 3 proyectos de investigación',
                    'icono' => 'fa-flask',
                    'tipo' => 'experimentos',
                    'objetivo' => 3,
                    'puntos_recompensa' => 40
                ]
            ];
            
            foreach ($logros as $logro) {
                DB::table('logros')
                    ->where('id', $logro['id'])
                    ->update([
                        'nombre' => $logro['nombre'],
                        'descripcion' => $logro['descripcion'],
                        'icono' => $logro['icono'],
                        'tipo' => $logro['tipo'],
                        'objetivo' => $logro['objetivo'],
                        'puntos_recompensa' => $logro['puntos_recompensa'],
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No eliminamos las columnas en caso de rollback para evitar pérdida de datos
    }
};