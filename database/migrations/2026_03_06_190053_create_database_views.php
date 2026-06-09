<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        DB::statement("DROP VIEW IF EXISTS v_credenciales_app");
        DB::statement("DROP VIEW IF EXISTS v_personas_segura");

        DB::statement("
        CREATE VIEW v_credenciales_app AS
        SELECT
        credenciales.id,
        credenciales.persona_id,
        credenciales.tipo,
        'CONFIDENCIAL' AS valor_oculto,
        credenciales.activa
        FROM credenciales
    ");

        if (DB::connection()->getDriverName() === 'sqlite') {
            DB::statement("
            CREATE VIEW v_personas_segura AS
            SELECT
            personas.id,
            personas.tipo,
            substr(personas.documento, 1, 3) || '******' AS documento_protegido,
            personas.nombres,
            personas.apellidos,
            '***-' || substr(personas.telefono, -4) AS tel_protegido,
            personas.ficha,
            personas.activo
            FROM personas
    ");
        } else {
            DB::statement("
            CREATE VIEW v_personas_segura AS
            SELECT
            personas.id,
            personas.tipo,
            concat(left(personas.documento,3),'******') AS documento_protegido,
            personas.nombres,
            personas.apellidos,
            concat('***-',right(personas.telefono,4)) AS tel_protegido,
            personas.ficha,
            personas.activo
            FROM personas
    ");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        DB::statement("DROP VIEW IF EXISTS v_credenciales_app");
        DB::statement("DROP VIEW IF EXISTS v_personas_segura");
    }
};
