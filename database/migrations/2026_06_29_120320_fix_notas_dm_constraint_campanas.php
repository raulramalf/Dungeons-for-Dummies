<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared("ALTER TABLE campanas MODIFY `notas_dm` longtext DEFAULT NULL");
    }

    public function down(): void {}
};