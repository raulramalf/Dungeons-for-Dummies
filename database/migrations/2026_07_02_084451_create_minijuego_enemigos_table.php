<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create("minijuego_enemigos", function (Blueprint $table) {
            $table->id();
            $table->string("nombre");
            $table->string("titulo")->nullable();
            $table->text("descripcion")->nullable();
            $table->string("imagen")->nullable();
            $table->integer("vida_max");
            $table->string("tipo")->nullable();
            $table->json("rondas");
            $table->boolean("activo")->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists("minijuego_enemigos");
    }
};
