<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('perfil', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('usuarios')->onDelete('cascade');
            $table->string('nombre_display')->nullable();
            $table->text('biografia')->nullable();
            $table->string('avatar')->nullable();
            $table->string('pais')->nullable();
            $table->string('idioma_preferido')->default('es');
            $table->json('preferencias')->nullable();           // Tema, notificaciones, etc.
            $table->integer('partidas_jugadas')->default(0);
            $table->integer('partidas_dm')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('perfil');
    }
};