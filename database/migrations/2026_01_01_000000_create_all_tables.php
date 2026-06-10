<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Users
        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->unique();
                $table->string('foto')->nullable();
                $table->string('compania')->nullable();
                $table->string('ciudad')->nullable();
                $table->string('telefono', 20)->nullable();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');
                $table->rememberToken();
                $table->timestamps();
                $table->enum('role', ['superadmin', 'admin', 'vigilante']);
                $table->enum('estado', ['activo', 'inactivo'])->default('activo');
                $table->softDeletes();
            });
        }

        // Centros
        if (!Schema::hasTable('centros')) {
            Schema::create('centros', function (Blueprint $table) {
                $table->id();
                $table->string('nombre');
                $table->unsignedBigInteger('persona_id');
                $table->timestamps();
            });
        }

        // Fichas
        if (!Schema::hasTable('fichas')) {
            Schema::create('fichas', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('centro_id');
                $table->string('ficha');
                $table->timestamps();
                $table->foreign('centro_id')->references('id')->on('centros')->onDelete('cascade');
            });
        }

        // Personas
        if (!Schema::hasTable('personas')) {
            Schema::create('personas', function (Blueprint $table) {
                $table->id();
                $table->enum('tipo', ['aprendiz', 'instructor', 'visitante', 'administrador', 'funcionario']);
                $table->enum('genero', ['masculino', 'femenino', 'otro'])->nullable();
                $table->string('centro')->nullable();
                $table->string('regional')->nullable();
                $table->string('tipo_sangre', 3)->nullable();
                $table->string('documento', 50)->unique();
                $table->string('nombres', 100);
                $table->string('apellidos', 100);
                $table->string('telefono', 20)->nullable();
                $table->string('ficha', 50)->nullable();
                $table->string('foto')->nullable();
                $table->unsignedBigInteger('ficha_id')->nullable();
                $table->boolean('activo')->default(true);
                $table->timestamp('visitante_expira_en')->nullable();
                $table->unsignedBigInteger('visitante_registrado_por')->nullable();
                $table->timestamps();
                $table->softDeletes();
                $table->foreign('ficha_id')->references('id')->on('fichas')->nullOnDelete();
                $table->foreign('visitante_registrado_por')->references('id')->on('users')->nullOnDelete();
            });
        }

        // Credenciales
        if (!Schema::hasTable('credenciales')) {
            Schema::create('credenciales', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('persona_id');
                $table->enum('tipo', ['codigo_barra', 'rfid', 'huella', 'qr']);
                $table->string('valor')->unique();
                $table->boolean('activa')->default(true);
                $table->timestamps();
                $table->foreign('persona_id')->references('id')->on('personas')->onDelete('cascade');
            });
        }

        // Dispositivos
        if (!Schema::hasTable('dispositivos')) {
            Schema::create('dispositivos', function (Blueprint $table) {
                $table->id();
                $table->string('nombre', 100);
                $table->string('ip', 45)->nullable();
                $table->boolean('activo')->default(true);
                $table->timestamps();
                $table->string('ubicacion')->nullable();
            });
        }

        // Accesos
        if (!Schema::hasTable('accesos')) {
            Schema::create('accesos', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('persona_id');
                $table->unsignedBigInteger('dispositivo_id');
                $table->unsignedBigInteger('autorizado_por')->nullable();
                $table->enum('tipo', ['entrada', 'salida']);
                $table->enum('estado', ['permitido', 'denegado']);
                $table->string('motivo_denegacion')->nullable();
                $table->datetime('fecha_hora')->useCurrent();
                $table->timestamps();
                $table->foreign('persona_id')->references('id')->on('personas')->onDelete('cascade');
                $table->foreign('dispositivo_id')->references('id')->on('dispositivos')->onDelete('cascade');
                $table->foreign('autorizado_por')->references('id')->on('users')->nullOnDelete();
            });
        }

        // Logs sistema
        if (!Schema::hasTable('logs_sistema')) {
            Schema::create('logs_sistema', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->string('accion', 150);
                $table->text('descripcion')->nullable();
                $table->string('ip', 45)->nullable();
                $table->timestamps();
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            });
        }

        // Password reset tokens
        if (!Schema::hasTable('password_reset_tokens')) {
            Schema::create('password_reset_tokens', function (Blueprint $table) {
                $table->string('email')->primary();
                $table->string('token');
                $table->timestamp('created_at')->nullable();
            });
        }

        // Sessions
        if (!Schema::hasTable('sessions')) {
            Schema::create('sessions', function (Blueprint $table) {
                $table->string('id')->primary();
                $table->unsignedBigInteger('user_id')->nullable()->index();
                $table->string('ip_address', 45)->nullable();
                $table->text('user_agent')->nullable();
                $table->longText('payload');
                $table->integer('last_activity')->index();
            });
        }

        // Cache
        if (!Schema::hasTable('cache')) {
            Schema::create('cache', function (Blueprint $table) {
                $table->string('key')->primary();
                $table->mediumText('value');
                $table->integer('expiration');
            });
        }

        // Cache locks
        if (!Schema::hasTable('cache_locks')) {
            Schema::create('cache_locks', function (Blueprint $table) {
                $table->string('key')->primary();
                $table->string('owner');
                $table->integer('expiration');
            });
        }

        // Jobs
        if (!Schema::hasTable('jobs')) {
            Schema::create('jobs', function (Blueprint $table) {
                $table->id();
                $table->string('queue')->index();
                $table->longText('payload');
                $table->unsignedTinyInteger('attempts');
                $table->unsignedInteger('reserved_at')->nullable();
                $table->unsignedInteger('available_at');
                $table->unsignedInteger('created_at');
            });
        }

        // Job batches
        if (!Schema::hasTable('job_batches')) {
            Schema::create('job_batches', function (Blueprint $table) {
                $table->string('id')->primary();
                $table->string('name');
                $table->integer('total_jobs');
                $table->integer('pending_jobs');
                $table->integer('failed_jobs');
                $table->longText('failed_job_ids');
                $table->mediumText('options')->nullable();
                $table->integer('cancelled_at')->nullable();
                $table->integer('created_at');
                $table->integer('finished_at')->nullable();
            });
        }

        // Failed jobs
        if (!Schema::hasTable('failed_jobs')) {
            Schema::create('failed_jobs', function (Blueprint $table) {
                $table->id();
                $table->string('uuid')->unique();
                $table->text('connection');
                $table->text('queue');
                $table->longText('payload');
                $table->longText('exception');
                $table->timestamp('failed_at')->useCurrent();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('accesos');
        Schema::dropIfExists('logs_sistema');
        Schema::dropIfExists('credenciales');
        Schema::dropIfExists('dispositivos');
        Schema::dropIfExists('personas');
        Schema::dropIfExists('fichas');
        Schema::dropIfExists('centros');
        Schema::dropIfExists('users');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('cache');
        Schema::dropIfExists('cache_locks');
        Schema::dropIfExists('jobs');
        Schema::dropIfExists('job_batches');
        Schema::dropIfExists('failed_jobs');
    }
};