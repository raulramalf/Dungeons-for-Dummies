<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('equipo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('personaje_id')->constrained('personajes')->onDelete('cascade');
            $table->string('nombre');
            $table->string('tipo');                            // arma, armadura, escudo, adventuring_gear, herramienta...
            $table->text('descripcion')->nullable();
            $table->string('rareza')->default('común');        // común, infrecuente, raro, muy_raro, legendario, artefacto
            $table->boolean('magico')->default(false);
            $table->boolean('requiere_sintonizacion')->default(false);
            $table->boolean('sintonizado')->default(false);
            $table->boolean('equipado')->default(false);
            $table->integer('cantidad')->default(1);
            $table->decimal('peso', 8, 2)->nullable();         // En libras
            $table->integer('valor_po')->nullable();           // Valor en piezas de oro
            $table->json('propiedades')->nullable();            // Propiedades mágicas, daño, CA, etc.
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('equipo');
    }
};