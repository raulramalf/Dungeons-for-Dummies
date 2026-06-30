<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Añade los campos que trae el dataset de conjuros de la edición 5.5
     * (Jtachan/DnD-5.5-Spells-ES) y que no existían en el esquema original,
     * pensado solo para los datos de la API 2014 (www.dnd5eapi.co).
     */
    public function up(): void
    {
        Schema::table('conjuros', function (Blueprint $table) {
            if (!Schema::hasColumn('conjuros', 'tirada_de_salvacion')) {
                $table->string('tirada_de_salvacion', 10)->nullable()->after('a_niveles_superiores');
                // Atributo de la tirada de salvación contra el conjuro: FUE, DES, CON, INT, SAB, CAR. Null si no requiere ninguna.
            }

            if (!Schema::hasColumn('conjuros', 'requiere_ataque')) {
                $table->boolean('requiere_ataque')->default(false)->after('tirada_de_salvacion');
                // Si lanzar el conjuro exige una tirada de ataque de conjuro (cuerpo a cuerpo o a distancia).
            }

            if (!Schema::hasColumn('conjuros', 'requiere_objetivo_visible')) {
                $table->boolean('requiere_objetivo_visible')->default(true)->after('requiere_ataque');
                // Si el objetivo debe estar a la vista del lanzador.
            }

            if (!Schema::hasColumn('conjuros', 'edicion')) {
                $table->string('edicion', 10)->default('2014')->after('requiere_objetivo_visible');
                // '2014' = importado de la API dnd5eapi.co · '5.5' = importado del dataset DnD-5.5-Spells-ES
            }
        });
    }

    public function down(): void
    {
        Schema::table('conjuros', function (Blueprint $table) {
            foreach (['tirada_de_salvacion', 'requiere_ataque', 'requiere_objetivo_visible', 'edicion'] as $col) {
                if (Schema::hasColumn('conjuros', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};