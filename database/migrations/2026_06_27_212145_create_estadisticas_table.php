<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('estadisticas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('personaje_id')->constrained('personajes')->onDelete('cascade');
            // Características principales
            $table->integer('fuerza')->default(10);
            $table->integer('destreza')->default(10);
            $table->integer('constitucion')->default(10);
            $table->integer('inteligencia')->default(10);
            $table->integer('sabiduria')->default(10);
            $table->integer('carisma')->default(10);
            // Puntos de golpe
            $table->integer('pg_maximos');
            $table->integer('pg_actuales');
            $table->integer('pg_temporales')->default(0);
            // Combate
            $table->integer('clase_de_armadura');
            $table->integer('iniciativa')->nullable();          // Calculado automáticamente si null
            $table->integer('velocidad')->default(30);
            $table->integer('bonus_competencia')->default(2);
            // Dinero
            $table->integer('monedas_cobre')->default(0);
            $table->integer('monedas_plata')->default(0);
            $table->integer('monedas_electrum')->default(0);
            $table->integer('monedas_oro')->default(0);
            $table->integer('monedas_platino')->default(0);
            // Inspiración y descanso
            $table->boolean('inspiracion')->default(false);
            $table->integer('dados_golpe_disponibles')->nullable();
            // Muerte
            $table->integer('exitos_muerte')->default(0);
            $table->integer('fallos_muerte')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('estadisticas');
    }
};