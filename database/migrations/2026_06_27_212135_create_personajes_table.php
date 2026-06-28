<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('personajes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('usuarios')->onDelete('cascade');
            $table->foreignId('raza_id')->constrained('razas');
            $table->foreignId('clase_id')->constrained('clases');
            $table->foreignId('subclase_id')->nullable()->constrained('subclases')->nullOnDelete();
            $table->foreignId('trasfondo_id')->nullable()->constrained('trasfondos')->nullOnDelete();
            $table->string('nombre');
            $table->string('alineamiento')->nullable();         // Legal Bueno, Neutral, Caótico Malvado...
            $table->integer('nivel')->default(1);
            $table->integer('experiencia')->default(0);
            $table->string('avatar')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('personajes');
    }
};