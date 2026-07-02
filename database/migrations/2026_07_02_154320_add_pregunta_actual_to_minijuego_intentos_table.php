<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table("minijuego_intentos", function (Blueprint $table) {
            $table->foreignId("pregunta_actual_id")
                ->nullable()
                ->after("minijuego_reto_id")
                ->constrained("minijuego_preguntas")
                ->nullOnDelete();
            $table->json("pregunta_actual_opciones")->nullable()->after("pregunta_actual_id");
        });
    }

    public function down(): void
    {
        Schema::table("minijuego_intentos", function (Blueprint $table) {
            $table->dropConstrainedForeignId("pregunta_actual_id");
            $table->dropColumn("pregunta_actual_opciones");
        });
    }
};