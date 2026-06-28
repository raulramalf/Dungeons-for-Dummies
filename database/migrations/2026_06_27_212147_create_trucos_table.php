<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trucos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('personaje_id')->constrained('personajes')->onDelete('cascade');
            $table->foreignId('conjuro_id')->nullable()->constrained('conjuros')->nullOnDelete();
            $table->string('nombre');                          // Por si es un truco personalizado
            $table->text('descripcion')->nullable();
            $table->string('fuente')->nullable();              // Clase, Raza, Dote, Objeto...
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trucos');
    }
};