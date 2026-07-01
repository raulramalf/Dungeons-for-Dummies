<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Añade los campos que trae el dataset de trasfondos de la edición 5.5
     * (SRD 5.2.1, Wizards of the Coast, CC BY 4.0) y que no existían en el
     * esquema original, pensado solo para los datos de la API 2014
     * (www.dnd5eapi.co).
     *
     * En 5.5e los trasfondos ya no dan idiomas ni rasgos de personalidad
     * (eso pasó a las especies y quedó como algo narrativo libre); en
     * cambio ahora otorgan una mejora de característica (+2/+1 o 3x+1 a
     * elegir entre 3 atributos fijos) y una dote de origen obligatoria.
     */
    public function up(): void
    {
        Schema::table('trasfondos', function (Blueprint $table) {
            if (!Schema::hasColumn('trasfondos', 'mejora_caracteristicas')) {
                $table->json('mejora_caracteristicas')->nullable()->after('competencias_herramientas');
                // Ej: {"opciones": ["Inteligencia", "Sabiduría", "Carisma"], "modo": "2_1_o_3x1"}
            }

            if (!Schema::hasColumn('trasfondos', 'dote_origen')) {
                $table->string('dote_origen')->nullable()->after('caracteristica_especial');
                // Nombre de la dote de origen que otorga el trasfondo (p. ej. "Iniciado en la Magia").
            }

            if (!Schema::hasColumn('trasfondos', 'edicion')) {
                $table->string('edicion', 10)->default('2014')->after('dote_origen');
                // '2014' = importado de la API dnd5eapi.co · '5.5' = importado del SRD 5.2.1
            }
        });
    }

    public function down(): void
    {
        Schema::table('trasfondos', function (Blueprint $table) {
            foreach (['mejora_caracteristicas', 'dote_origen', 'edicion'] as $col) {
                if (Schema::hasColumn('trasfondos', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
