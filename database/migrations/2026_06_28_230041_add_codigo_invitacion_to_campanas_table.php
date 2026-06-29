<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('campanas', function (Blueprint $table) {
            $table->string('codigo_invitacion', 8)->unique()->nullable()->after('nombre');
        });
    }

    public function down(): void
    {
        Schema::table('campanas', function (Blueprint $table) {
            $table->dropColumn('codigo_invitacion');
        });
    }
};