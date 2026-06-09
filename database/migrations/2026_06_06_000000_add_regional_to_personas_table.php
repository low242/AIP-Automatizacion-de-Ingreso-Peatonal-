<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasColumn('personas', 'regional')) {
            Schema::table('personas', function (Blueprint $table) {
                $table->string('regional')->nullable();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('personas', 'regional')) {
            Schema::table('personas', function (Blueprint $table) {
                $table->dropColumn('regional');
            });
        }
    }
};
