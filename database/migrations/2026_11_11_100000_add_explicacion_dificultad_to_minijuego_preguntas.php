<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table("minijuego_preguntas", function (Blueprint $table) {
            $table->text("explicacion")->nullable()->after("categoria");
            $table->string("dificultad", 10)->default("media")->after("explicacion");
        });
    }

    public function down(): void
    {
        Schema::table("minijuego_preguntas", function (Blueprint $table) {
            $table->dropColumn(["explicacion", "dificultad"]);
        });
    }
};
