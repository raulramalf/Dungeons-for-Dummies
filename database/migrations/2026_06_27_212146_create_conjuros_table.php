<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('conjuros', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->integer('nivel');                          // 0 = Truco, 1-9 = Nivel del conjuro
            $table->string('escuela');                         // Abjuración, Conjuración, Adivinación...
            $table->string('tiempo_lanzamiento');              // 1 acción, 1 acción adicional, 1 minuto...
            $table->string('alcance');                         // Personal, Toque, 60 pies...
            $table->json('componentes');                       // ["V", "S", "M"]
            $table->string('material')->nullable();            // Descripción del componente material
            $table->string('duracion');                        // Instantánea, 1 minuto, Concentración...
            $table->boolean('concentracion')->default(false);
            $table->boolean('ritual')->default(false);
            $table->text('descripcion');
            $table->text('a_niveles_superiores')->nullable();  // Descripción de casteo a niveles superiores
            $table->json('clases')->nullable();                // ["Mago", "Hechicero", "Brujo"]
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('conjuros');
    }
};