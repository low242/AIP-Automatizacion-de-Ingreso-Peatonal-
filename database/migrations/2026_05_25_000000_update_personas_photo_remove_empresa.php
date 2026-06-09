<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('personas', 'foto')) {
            Schema::table('personas', function (Blueprint $table) {
                $table->string('foto')->nullable()->after('ficha');
            });
        }

        if (Schema::hasColumn('personas', 'empresa')) {
            Schema::table('personas', function (Blueprint $table) {
                $table->dropColumn('empresa');
            });
        }
    }

    public function down(): void
    {
        if (!Schema::hasColumn('personas', 'empresa')) {
            Schema::table('personas', function (Blueprint $table) {
                $table->string('empresa', 100)->nullable()->after('ficha');
            });
        }

        if (Schema::hasColumn('personas', 'foto')) {
            Schema::table('personas', function (Blueprint $table) {
                $table->dropColumn('foto');
            });
        }
    }
};
