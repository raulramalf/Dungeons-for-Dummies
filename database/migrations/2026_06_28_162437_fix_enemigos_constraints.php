<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared("
            ALTER TABLE enemigos 
                MODIFY `velocidades_especiales` longtext DEFAULT NULL,
                MODIFY `tiradas_salvacion` longtext DEFAULT NULL,
                MODIFY `competencias` longtext DEFAULT NULL,
                MODIFY `resistencias` longtext DEFAULT NULL,
                MODIFY `inmunidades_daño` longtext DEFAULT NULL,
                MODIFY `vulnerabilidades` longtext DEFAULT NULL,
                MODIFY `inmunidades_condicion` longtext DEFAULT NULL,
                MODIFY `sentidos` longtext DEFAULT NULL,
                MODIFY `idiomas` longtext DEFAULT NULL,
                MODIFY `rasgos_especiales` longtext DEFAULT NULL,
                MODIFY `acciones` longtext DEFAULT NULL,
                MODIFY `acciones_adicionales` longtext DEFAULT NULL,
                MODIFY `reacciones` longtext DEFAULT NULL,
                MODIFY `acciones_legendarias` longtext DEFAULT NULL
        ");
    }

    public function down(): void {}
};