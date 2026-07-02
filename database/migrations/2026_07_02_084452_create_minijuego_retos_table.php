<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create("minijuego_retos", function (Blueprint $table) {
            $table->id();
            $table->date("fecha")->unique();
            $table
                ->foreignId("minijuego_enemigo_id")
                ->constrained("minijuego_enemigos");
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists("minijuego_retos");
    }
};
