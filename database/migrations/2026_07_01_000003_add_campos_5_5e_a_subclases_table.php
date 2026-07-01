<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Añade la columna que distingue las subclases de la edición 5.5
     * (SRD 5.2.1, Wizards of the Coast, CC BY 4.0) de las importadas de la
     * API 2014 (www.dnd5eapi.co).
     */
    public function up(): void
    {
        Schema::table('subclases', function (Blueprint $table) {
            if (!Schema::hasColumn('subclases', 'edicion')) {
                $table->string('edicion', 10)->default('2014')->after('rasgos');
                // '2014' = importado de la API dnd5eapi.co · '5.5' = importado del SRD 5.2.1
            }
        });
    }

    public function down(): void
    {
        Schema::table('subclases', function (Blueprint $table) {
            if (Schema::hasColumn('subclases', 'edicion')) {
                $table->dropColumn('edicion');
            }
        });
    }
};
