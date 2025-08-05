<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
   public function up()
   {
       if (!Schema::hasTable('ranking')) {
           Schema::create('ranking', function (Blueprint $table) {
               $table->id();
               $table->foreignId('usuario_id')->constrained('usuario')->cascadeOnDelete();
               $table->integer('puntos_semana')->default(0);
               $table->integer('puntos_mes')->default(0);
               $table->integer('puntos_totales')->default(0);
               $table->timestamps();
               
               $table->index('puntos_totales');
               $table->index('puntos_semana');
           });
       }
   }
   
   public function down()
   {
       Schema::dropIfExists('ranking');
   }
};
