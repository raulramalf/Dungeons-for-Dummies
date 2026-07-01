<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create("personaje_dote", function (Blueprint $table) {
            $table->id();
            $table
                ->foreignId("personaje_id")
                ->constrained("personajes")
                ->cascadeOnDelete();
            $table
                ->foreignId("dote_id")
                ->constrained("dotes")
                ->cascadeOnDelete();
            $table->timestamps();
            $table->unique(["personaje_id", "dote_id"]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists("personaje_dote");
    }
};
