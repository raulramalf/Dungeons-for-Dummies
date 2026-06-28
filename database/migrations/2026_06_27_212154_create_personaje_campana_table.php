<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('personaje_campana', function (Blueprint $table) {
            $table->id();
            $table->foreignId('personaje_id')->constrained('personajes')->onDelete('cascade');
            $table->foreignId('campana_id')->constrained('campanas')->onDelete('cascade');
            $table->string('estado')->default('activo');        // activo, retirado, muerto
            $table->timestamp('fecha_ingreso')->useCurrent();
            $table->timestamp('fecha_salida')->nullable();
            $table->text('notas')->nullable();
            $table->timestamps();

            $table->unique(['personaje_id', 'campana_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('personaje_campana');
    }
};