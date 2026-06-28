<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trasfondos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');                              // Acolito, Criminal, Héroe del Pueblo...
            $table->text('descripcion')->nullable();
            $table->json('competencias_habilidades')->nullable();  // ["Historia", "Religión"]
            $table->json('competencias_herramientas')->nullable();
            $table->json('idiomas')->nullable();
            $table->json('equipo_inicial')->nullable();            // Equipo que otorga al inicio
            $table->text('rasgo_personalidad')->nullable();
            $table->text('ideal')->nullable();
            $table->text('vinculo')->nullable();
            $table->text('defecto')->nullable();
            $table->string('caracteristica_especial')->nullable(); // Habilidad especial del trasfondo
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trasfondos');
    }
};