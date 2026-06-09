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
        if (!Schema::hasColumn('personas', 'genero')) {
            Schema::table('personas', function (Blueprint $table) {
                $table->enum('genero', ['masculino', 'femenino', 'otro'])
                    ->nullable()
                    ->after('tipo');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('personas', 'genero')) {
            Schema::table('personas', function (Blueprint $table) {
                $table->dropColumn('genero');
            });
        }
    }
};
