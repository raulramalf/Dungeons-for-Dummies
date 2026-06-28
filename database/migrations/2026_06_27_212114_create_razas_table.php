<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('razas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');                           // Humano, Elfo, Enano...
            $table->text('descripcion')->nullable();
            $table->integer('velocidad')->default(30);          // Velocidad en pies
            $table->string('tamaño')->default('Mediano');       // Pequeño, Mediano, Grande
            $table->json('bonificadores_caracteristica')->nullable(); // {"fuerza": 2, "destreza": 1}
            $table->json('rasgos')->nullable();                 // Rasgos raciales especiales
            $table->json('idiomas')->nullable();                // Idiomas que conoce
            $table->json('vision')->nullable();                 // vision_oscura, vision_verdadera...
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('razas');
    }
};