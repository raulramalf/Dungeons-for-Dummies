<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create("minijuego_intentos", function (Blueprint $table) {
            $table->id();
            $table
                ->foreignId("usuario_id")
                ->constrained("usuarios")
                ->cascadeOnDelete();
            $table
                ->foreignId("minijuego_reto_id")
                ->constrained("minijuego_retos")
                ->cascadeOnDelete();
            $table->unsignedInteger("ronda_actual")->default(0);
            $table->integer("vida_restante");
            $table->json("opciones_elegidas")->nullable();
            $table
                ->enum("estado", ["en_curso", "ganado", "perdido"])
                ->default("en_curso");
            $table->timestamp("completado_en")->nullable();
            $table->timestamps();

            $table->unique(["usuario_id", "minijuego_reto_id"]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists("minijuego_intentos");
    }
};
