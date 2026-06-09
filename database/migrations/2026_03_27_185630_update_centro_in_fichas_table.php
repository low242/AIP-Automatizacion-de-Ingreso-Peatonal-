<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('fichas', function (Blueprint $table) {
            if (Schema::hasColumn('fichas', 'centro')) {
                $table->dropColumn('centro');
            }

            if (!Schema::hasColumn('fichas', 'centro_id')) {
                $table->foreignId('centro_id')->after('id')
                    ->constrained('centros')->onDelete('cascade');
            }
        });
    }

    public function down(): void
    {
        Schema::table('fichas', function (Blueprint $table) {
            $table->dropForeign(['centro_id']);
            $table->dropColumn('centro_id');
            $table->string('centro')->nullable();
        });
    }
};
