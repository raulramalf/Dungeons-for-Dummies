<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('enemigos', function (Blueprint $table) {
            if (!Schema::hasColumn('enemigos', 'es_boss')) {
                $table->boolean('es_boss')->default(false)->after('imagen');
            }
        });
    }

    public function down(): void
    {
        Schema::table('enemigos', function (Blueprint $table) {
            $table->dropColumn('es_boss');
        });
    }
};