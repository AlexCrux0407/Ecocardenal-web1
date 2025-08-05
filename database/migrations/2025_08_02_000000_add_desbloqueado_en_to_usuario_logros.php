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
        if (Schema::hasTable('usuario_logros') && !Schema::hasColumn('usuario_logros', 'desbloqueado_en')) {
            Schema::table('usuario_logros', function (Blueprint $table) {
                $table->timestamp('desbloqueado_en')->useCurrent();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('usuario_logros') && Schema::hasColumn('usuario_logros', 'desbloqueado_en')) {
            Schema::table('usuario_logros', function (Blueprint $table) {
                $table->dropColumn('desbloqueado_en');
            });
        }
    }
};