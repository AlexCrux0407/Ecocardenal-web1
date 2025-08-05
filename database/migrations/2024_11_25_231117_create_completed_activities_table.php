<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        if (!Schema::hasTable('completed_activities')) {
            Schema::create('completed_activities', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('usuario_id');
                $table->enum('activity_type', ['quiz', 'juego', 'manualidades', 'historias', 'experimentos']);
                $table->string('activity_name');
                $table->integer('points');
                $table->timestamp('completed_at')->useCurrent();

                $table->foreign('usuario_id')->references('id')->on('usuario')->onDelete('cascade');
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('completed_activities');
    }
};
