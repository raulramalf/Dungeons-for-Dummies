<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Añade los campos que trae el dataset de dotes de la edición 5.5
     * (SRD 5.2.1, Wizards of the Coast, CC BY 4.0) y que no existían en el
     * esquema original, pensado solo para los datos de la API 2014
     * (www.dnd5eapi.co).
     *
     * En 5.5e las dotes se organizan en 4 categorías nuevas que no
     * existían como concepto formal en 2014: Origen (nivel 1, sin
     * prerrequisitos, las otorgan los trasfondos), Generales (nivel 4+),
     * Estilo de Combate (requieren el rasgo Estilo de Combate) y Épicas /
     * Dones (nivel 19+). Además varias dotes son repetibles.
     */
    public function up(): void
    {
        Schema::table('dotes', function (Blueprint $table) {
            if (!Schema::hasColumn('dotes', 'categoria')) {
                $table->string('categoria', 20)->nullable()->after('nombre');
                // 'origen' | 'general' | 'estilo_combate' | 'epica'
            }

            if (!Schema::hasColumn('dotes', 'repetible')) {
                $table->boolean('repetible')->default(false)->after('incremento_caracteristica');
                // Si la dote se puede escoger más de una vez (p. ej. Habilidoso, Mejora de Característica).
            }

            if (!Schema::hasColumn('dotes', 'edicion')) {
                $table->string('edicion', 10)->default('2014')->after('repetible');
                // '2014' = importado de la API dnd5eapi.co · '5.5' = importado del SRD 5.2.1
            }
        });
    }

    public function down(): void
    {
        Schema::table('dotes', function (Blueprint $table) {
            foreach (['categoria', 'repetible', 'edicion'] as $col) {
                if (Schema::hasColumn('dotes', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
