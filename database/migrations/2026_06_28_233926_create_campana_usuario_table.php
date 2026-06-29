<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('campana_usuario', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('campana_id');
            $table->unsignedBigInteger('usuario_id');
            $table->string('rol')->default('jugador');
            $table->timestamps();

            $table->foreign('campana_id')->references('id')->on('campanas')->onDelete('cascade');
            $table->foreign('usuario_id')->references('id')->on('usuarios')->onDelete('cascade');
            $table->unique(['campana_id', 'usuario_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campana_usuario');
    }
};