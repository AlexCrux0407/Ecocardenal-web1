<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        if (!Schema::hasTable('historias')) {
            Schema::create('historias', function (Blueprint $table) {
                $table->id();
                $table->string('nombre')->default('Anónimo');
                $table->text('contenido');
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('historias');
    }
};
