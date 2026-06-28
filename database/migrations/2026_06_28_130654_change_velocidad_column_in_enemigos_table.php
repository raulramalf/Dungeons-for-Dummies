<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
        /**
         * Run the migrations.
         */
        public function up(): void
    {
        Schema::table('enemigos', function (Blueprint $table) {
            $table->string('velocidad')->default('30 ft.')->change();
        });
    }

    public function down(): void
    {
        Schema::table('enemigos', function (Blueprint $table) {
            $table->integer('velocidad')->default(30)->change();
        });
    }
};
