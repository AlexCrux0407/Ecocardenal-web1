<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

return new class extends Migration {
    public function up()
    {
        // Primero modificamos la columna para que acepte cualquier valor (temporal)
        Schema::table('completed_activities', function (Blueprint $table) {
            // En MySQL, necesitamos modificar la columna a un tipo que acepte cualquier valor
            DB::statement("ALTER TABLE completed_activities MODIFY activity_type VARCHAR(20) NOT NULL");
        });

        // Luego recreamos la columna enum con los valores actualizados
        Schema::table('completed_activities', function (Blueprint $table) {
            // Recreamos la columna enum con todos los valores posibles
            DB::statement("ALTER TABLE completed_activities MODIFY activity_type ENUM('quiz', 'quizzes', 'juego', 'game', 'trivia', 'manualidades', 'historias', 'experimentos', 'experimento') NOT NULL");
        });

        // Registramos en el log que se ha actualizado la migración
        \Log::info('Migración completada: Se ha actualizado el enum activity_type en la tabla completed_activities');
    }

    public function down()
    {
        // Revertimos a los valores originales
        Schema::table('completed_activities', function (Blueprint $table) {
            DB::statement("ALTER TABLE completed_activities MODIFY activity_type ENUM('quiz', 'juego', 'manualidades', 'historias', 'experimentos') NOT NULL");
        });
    }
};