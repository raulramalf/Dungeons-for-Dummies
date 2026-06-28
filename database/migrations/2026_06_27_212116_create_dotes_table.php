<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dotes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->text('descripcion');
            $table->json('prerequisitos')->nullable();          // {"nivel": 4, "fuerza": 13, "clase": "Guerrero"}
            $table->json('beneficios')->nullable();             // Bonificadores y habilidades que otorga
            $table->boolean('incremento_caracteristica')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dotes');
    }
};