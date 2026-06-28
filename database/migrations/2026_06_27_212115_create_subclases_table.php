<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subclases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clase_id')->constrained('clases')->onDelete('cascade');
            $table->string('nombre');                          // Camino del Berserker, Escuela de Evocación...
            $table->text('descripcion')->nullable();
            $table->integer('nivel_disponible')->default(3);   // Nivel al que se elige la subclase
            $table->json('rasgos')->nullable();                // Rasgos y habilidades de la subclase
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subclases');
    }
};