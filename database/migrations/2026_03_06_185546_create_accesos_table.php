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
        Schema::create('accesos', function (Blueprint $table) {

            $table->id();

            $table->foreignId('persona_id')
                ->constrained('personas')
                ->cascadeOnDelete();

            $table->foreignId('dispositivo_id')
                ->constrained('dispositivos')
                ->cascadeOnDelete();

            $table->foreignId('autorizado_por')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->enum('tipo', ['entrada', 'salida']);
            $table->enum('estado', ['permitido', 'denegado']);
            $table->string('motivo_denegacion')->nullable();

            $table->dateTime('fecha_hora')->useCurrent();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accesos');
    }
};
