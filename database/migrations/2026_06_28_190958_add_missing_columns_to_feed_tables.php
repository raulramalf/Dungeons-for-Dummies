<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── posts: añadir etiquetas ──────────────────────────────────────
        Schema::table('posts', function (Blueprint $table) {
            if (!Schema::hasColumn('posts', 'etiquetas')) {
                $table->json('etiquetas')->nullable()->after('contenido');
            }
        });

        // ── comentarios: añadir parent_id para respuestas anidadas ───────
        Schema::table('comentarios', function (Blueprint $table) {
            if (!Schema::hasColumn('comentarios', 'parent_id')) {
                $table->unsignedBigInteger('parent_id')
                      ->nullable()
                      ->after('contenido');

                $table->foreign('parent_id')
                      ->references('id')
                      ->on('comentarios')
                      ->onDelete('cascade');
            }
        });
    }

    public function down(): void
    {
        Schema::table('comentarios', function (Blueprint $table) {
            if (Schema::hasColumn('comentarios', 'parent_id')) {
                $table->dropForeign(['parent_id']);
                $table->dropColumn('parent_id');
            }
        });

        Schema::table('posts', function (Blueprint $table) {
            if (Schema::hasColumn('posts', 'etiquetas')) {
                $table->dropColumn('etiquetas');
            }
        });
    }
};