<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('personajes', function (Blueprint $table) {
            if (!Schema::hasColumn('personajes', 'historia'))
                $table->text('historia')->nullable()->after('avatar');

            if (!Schema::hasColumn('personajes', 'rasgos_personalidad'))
                $table->text('rasgos_personalidad')->nullable()->after('historia');

            if (!Schema::hasColumn('personajes', 'ideales'))
                $table->text('ideales')->nullable()->after('rasgos_personalidad');

            if (!Schema::hasColumn('personajes', 'vinculos'))
                $table->text('vinculos')->nullable()->after('ideales');

            if (!Schema::hasColumn('personajes', 'defectos'))
                $table->text('defectos')->nullable()->after('vinculos');

            if (!Schema::hasColumn('personajes', 'imagenes_personaje'))
                $table->json('imagenes_personaje')->nullable()->after('defectos');

            if (!Schema::hasColumn('personajes', 'imagenes_armas'))
                $table->json('imagenes_armas')->nullable()->after('imagenes_personaje');

            if (!Schema::hasColumn('personajes', 'edad'))
                $table->string('edad', 50)->nullable()->after('imagenes_armas');

            if (!Schema::hasColumn('personajes', 'altura'))
                $table->string('altura', 50)->nullable()->after('edad');

            if (!Schema::hasColumn('personajes', 'peso'))
                $table->string('peso', 50)->nullable()->after('altura');

            if (!Schema::hasColumn('personajes', 'ojos'))
                $table->string('ojos', 100)->nullable()->after('peso');

            if (!Schema::hasColumn('personajes', 'piel'))
                $table->string('piel', 100)->nullable()->after('ojos');

            if (!Schema::hasColumn('personajes', 'pelo'))
                $table->string('pelo', 100)->nullable()->after('piel');

            if (!Schema::hasColumn('personajes', 'divinidad'))
                $table->string('divinidad', 255)->nullable()->after('pelo');

            if (!Schema::hasColumn('personajes', 'competencias_habilidades'))
                $table->json('competencias_habilidades')->nullable()->after('divinidad');

            if (!Schema::hasColumn('personajes', 'competencias_salvaciones'))
                $table->json('competencias_salvaciones')->nullable()->after('competencias_habilidades');

            if (!Schema::hasColumn('personajes', 'idiomas'))
                $table->text('idiomas')->nullable()->after('competencias_salvaciones');

            if (!Schema::hasColumn('personajes', 'ataques'))
                $table->json('ataques')->nullable()->after('idiomas');
        });
    }

    public function down(): void
    {
        $columnas = [
            'historia', 'rasgos_personalidad', 'ideales', 'vinculos', 'defectos',
            'imagenes_personaje', 'imagenes_armas', 'edad', 'altura', 'peso',
            'ojos', 'piel', 'pelo', 'divinidad', 'competencias_habilidades',
            'competencias_salvaciones', 'idiomas', 'ataques',
        ];

        Schema::table('personajes', function (Blueprint $table) use ($columnas) {
            foreach ($columnas as $col) {
                if (Schema::hasColumn('personajes', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};