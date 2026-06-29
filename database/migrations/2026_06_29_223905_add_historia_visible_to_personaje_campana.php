<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('personaje_campana', function (Blueprint $table) {
            $table->boolean('historia_visible')->default(false)->after('notas');
        });
    }

    public function down(): void
    {
        Schema::table('personaje_campana', function (Blueprint $table) {
            $table->dropColumn('historia_visible');
        });
    }
};
