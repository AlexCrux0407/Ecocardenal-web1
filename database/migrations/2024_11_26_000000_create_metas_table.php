<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('metas')) {
            Schema::create('metas', function (Blueprint $table) {
                $table->id();
                $table->string('nombre');
                $table->text('descripcion');
                $table->integer('puntos')->default(10);
                $table->string('tipo')->default('actividades'); // actividades, puntos, logros, experimentos
                $table->integer('objetivo');
                $table->timestamps();
            });
            
            // Insertar algunas metas predeterminadas
            DB::table('metas')->insert([
            [
                'nombre' => 'Primeros 50 puntos',
                'descripcion' => 'Acumula 50 puntos en total',
                'puntos' => 10,
                'tipo' => 'puntos',
                'objetivo' => 50,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Completar 5 actividades',
                'descripcion' => 'Completa 5 actividades de cualquier tipo',
                'puntos' => 15,
                'tipo' => 'actividades',
                'objetivo' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Obtener 3 logros',
                'descripcion' => 'Desbloquea 3 logros diferentes',
                'puntos' => 20,
                'tipo' => 'logros',
                'objetivo' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Experto en puntos',
                'descripcion' => 'Acumula 200 puntos en total',
                'puntos' => 25,
                'tipo' => 'puntos',
                'objetivo' => 200,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Realizar 10 experimentos',
                'descripcion' => 'Completa 10 experimentos en la semana',
                'puntos' => 30,
                'tipo' => 'experimentos',
                'objetivo' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('metas');
    }
};