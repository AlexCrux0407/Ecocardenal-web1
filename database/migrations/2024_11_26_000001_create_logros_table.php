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
        if (!Schema::hasTable('logros')) {
            Schema::create('logros', function (Blueprint $table) {
                $table->id();
                $table->string('nombre');
                $table->text('descripcion');
                $table->string('icono')->default('fa-trophy');
                $table->string('tipo')->default('actividades'); // actividades, puntos, experimentos, quizzes
                $table->integer('objetivo');
                $table->integer('puntos_recompensa')->default(20);
                $table->timestamps();
            });
            
            // Insertar algunos logros predeterminados
             DB::table('logros')->insert([
            [
                'nombre' => 'Primera Actividad',
                'descripcion' => 'Completaste tu primera actividad',
                'icono' => 'fa-medal',
                'tipo' => 'actividades',
                'objetivo' => 1,
                'puntos_recompensa' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Coleccionista de Puntos',
                'descripcion' => 'Acumulaste 100 puntos en total',
                'icono' => 'fa-trophy',
                'tipo' => 'puntos',
                'objetivo' => 100,
                'puntos_recompensa' => 20,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Maestro de Quizzes',
                'descripcion' => 'Completaste 10 quizzes',
                'icono' => 'fa-star',
                'tipo' => 'quizzes',
                'objetivo' => 10,
                'puntos_recompensa' => 30,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Científico Novato',
                'descripcion' => 'Realizaste 5 experimentos',
                'icono' => 'fa-award',
                'tipo' => 'experimentos',
                'objetivo' => 5,
                'puntos_recompensa' => 25,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Experto en Reciclaje',
                'descripcion' => 'Completaste 20 actividades',
                'icono' => 'fa-gem',
                'tipo' => 'actividades',
                'objetivo' => 20,
                'puntos_recompensa' => 40,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Campeón Ecológico',
                'descripcion' => 'Acumulaste 500 puntos en total',
                'icono' => 'fa-crown',
                'tipo' => 'puntos',
                'objetivo' => 500,
                'puntos_recompensa' => 50,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
        }
        
        if (!Schema::hasTable('usuario_logros')) {
            Schema::create('usuario_logros', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('usuario_id');
                $table->unsignedBigInteger('logro_id');
                $table->timestamp('desbloqueado_en')->useCurrent();
                
                $table->foreign('usuario_id')->references('id')->on('usuario')->onDelete('cascade');
                $table->foreign('logro_id')->references('id')->on('logros')->onDelete('cascade');
                
                $table->unique(['usuario_id', 'logro_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuario_logros');
        Schema::dropIfExists('logros');
    }
};