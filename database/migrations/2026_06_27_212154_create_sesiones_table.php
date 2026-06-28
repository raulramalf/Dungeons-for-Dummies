<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sesiones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campana_id')->constrained('campanas')->onDelete('cascade');
            $table->string('titulo');
            $table->integer('numero_sesion');
            $table->text('resumen')->nullable();
            $table->text('notas_dm')->nullable();              // Notas privadas del DM
            $table->timestamp('fecha_sesion')->nullable();
            $table->integer('duracion_minutos')->nullable();
            $table->string('estado')->default('planificada'); // planificada, en_curso, completada, cancelada
            $table->json('personajes_presentes')->nullable();  // IDs de personajes que asistieron
            $table->json('enemigos_encontrados')->nullable();
            $table->integer('experiencia_otorgada')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sesiones');
    }
};