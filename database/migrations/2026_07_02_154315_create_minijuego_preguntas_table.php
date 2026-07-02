<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create("minijuego_preguntas", function (Blueprint $table) {
            $table->id();
            $table->text("pregunta");
            $table->json("opciones"); // [{"texto": "...", "correcta": true|false}, x4]
            $table->string("categoria")->nullable();
            $table->boolean("activo")->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists("minijuego_preguntas");
    }
};