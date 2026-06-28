<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('campanas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dungeon_master_id')->constrained('usuarios')->onDelete('cascade');
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->string('ambientacion')->nullable();         // Forgotten Realms, Eberron, custom...
            $table->string('estado')->default('activa');        // activa, pausada, finalizada
            $table->integer('nivel_inicial')->default(1);
            $table->integer('nivel_maximo')->nullable();
            $table->string('imagen')->nullable();
            $table->json('notas_dm')->nullable();               // Notas privadas del DM
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campanas');
    }
};