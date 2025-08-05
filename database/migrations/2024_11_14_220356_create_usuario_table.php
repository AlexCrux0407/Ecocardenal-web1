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
       if (!Schema::hasTable('usuario')) {
           Schema::create('usuario', function (Blueprint $table) {
            $table->id();
            $table->string('nombreUsuario')->unique();
            $table->string('nombre');
            $table->string('apellido');
            $table->string('correo')->unique();
            $table->string('password');
            $table->integer('puntos_totales')->default(0); // Para evitar calcular siempre desde actividades
            $table->rememberToken(); // Para funcionalidad "recordar sesión"
            $table->timestamps();
            
            // Índices adicionales
            $table->index('nombre');
            $table->index('apellido');
           });
       }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuario');
    }
};
