<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('enemigos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->string('tipo');                            // Bestia, Humanoide, No-muerto, Dragón...
            $table->string('tamaño');                          // Diminuto, Pequeño, Mediano, Grande, Enorme, Gargantuesco
            $table->string('alineamiento')->nullable();
            $table->decimal('clase_de_desafio', 4, 2);        // CR: 0, 0.125, 0.25, 0.5, 1-30
            $table->integer('puntos_de_experiencia');
            $table->integer('clase_de_armadura');
            $table->string('tipo_armadura')->nullable();
            $table->string('puntos_de_golpe');                 // Ej: "5d8+10"
            $table->string('velocidad')->default('30 ft.');
            $table->json('velocidades_especiales')->nullable(); // {"vuelo": 60, "nado": 30}
            // Características
            $table->integer('fuerza')->default(10);
            $table->integer('destreza')->default(10);
            $table->integer('constitucion')->default(10);
            $table->integer('inteligencia')->default(10);
            $table->integer('sabiduria')->default(10);
            $table->integer('carisma')->default(10);
            // Competencias y resistencias
            $table->json('tiradas_salvacion')->nullable();
            $table->json('competencias')->nullable();
            $table->json('resistencias')->nullable();
            $table->json('inmunidades_daño')->nullable();
            $table->json('vulnerabilidades')->nullable();
            $table->json('inmunidades_condicion')->nullable();
            $table->json('sentidos')->nullable();               // {"vision_oscura": 60, "percepcion_pasiva": 12}
            $table->json('idiomas')->nullable();
            $table->json('rasgos_especiales')->nullable();
            $table->json('acciones')->nullable();
            $table->json('acciones_adicionales')->nullable();
            $table->json('reacciones')->nullable();
            $table->json('acciones_legendarias')->nullable();
            $table->string('imagen')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enemigos');
    }
};