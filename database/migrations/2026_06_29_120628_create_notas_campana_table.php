<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notas_campana', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('campana_id');
            $table->string('titulo');
            $table->text('contenido');
            $table->boolean('visible_jugadores')->default(true);
            $table->timestamps();

            $table->foreign('campana_id')->references('id')->on('campanas')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notas_campana');
    }
};