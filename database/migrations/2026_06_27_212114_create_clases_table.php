<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clases', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');                          // Guerrero, Mago, Clérigo...
            $table->text('descripcion')->nullable();
            $table->string('dado_golpe');                      // d6, d8, d10, d12
            $table->json('competencias_armadura')->nullable(); // ligera, media, pesada, escudos
            $table->json('competencias_armas')->nullable();
            $table->json('competencias_herramientas')->nullable();
            $table->json('tiradas_salvacion')->nullable();     // Atributos de tiradas
            $table->integer('puntos_golpe_nivel_1');
            $table->string('habilidad_principal')->nullable(); // Fuerza, Destreza...
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clases');
    }
};