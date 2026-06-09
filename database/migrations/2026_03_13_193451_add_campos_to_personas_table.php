<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('personas', function (Blueprint $table) {

            $table->string('centro')->nullable()->after('tipo');

            $table->string('tipo_sangre', 3)->nullable()->after('centro');

        });
    }

    public function down(): void
    {
        Schema::table('personas', function (Blueprint $table) {

            $table->dropColumn([
                'centro',
                'tipo_sangre',
                'ficha'
            ]);

        });
    }
};
