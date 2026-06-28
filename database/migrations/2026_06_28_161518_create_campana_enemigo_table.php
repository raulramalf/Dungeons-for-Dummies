<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('campana_enemigo', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('campana_id');
            $table->unsignedBigInteger('enemigo_id');
            $table->boolean('visible_jugadores')->default(false);
            $table->text('notas_dm')->nullable();
            $table->timestamps();

            $table->foreign('campana_id')->references('id')->on('campanas')->onDelete('cascade');
            $table->foreign('enemigo_id')->references('id')->on('enemigos')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campana_enemigo');
    }
};