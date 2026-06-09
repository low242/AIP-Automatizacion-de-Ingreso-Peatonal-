<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('personas', function (Blueprint $table) {
            if (!Schema::hasColumn('personas', 'visitante_expira_en')) {
                $table->timestamp('visitante_expira_en')->nullable()->after('activo');
            }

            if (!Schema::hasColumn('personas', 'visitante_registrado_por')) {
                $table->foreignId('visitante_registrado_por')
                    ->nullable()
                    ->after('visitante_expira_en')
                    ->constrained('users')
                    ->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('personas', function (Blueprint $table) {
            if (Schema::hasColumn('personas', 'visitante_registrado_por')) {
                $table->dropForeign(['visitante_registrado_por']);
                $table->dropColumn('visitante_registrado_por');
            }

            if (Schema::hasColumn('personas', 'visitante_expira_en')) {
                $table->dropColumn('visitante_expira_en');
            }
        });
    }
};
