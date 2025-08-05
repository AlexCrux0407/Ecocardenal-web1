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
        if (!Schema::hasTable('usuario_metas')) {
            Schema::create('usuario_metas', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('usuario_id');
                $table->unsignedBigInteger('meta_id');
                $table->timestamp('completada_en')->useCurrent();
                
                $table->foreign('usuario_id')->references('id')->on('usuario')->onDelete('cascade');
                $table->foreign('meta_id')->references('id')->on('metas')->onDelete('cascade');
                
                $table->unique(['usuario_id', 'meta_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuario_metas');
    }
};