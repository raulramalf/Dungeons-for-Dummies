<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table("clases", function (Blueprint $table) {
            $table->string("nombre_en")->nullable()->after("nombre");
        });
        Schema::table("razas", function (Blueprint $table) {
            $table->string("nombre_en")->nullable()->after("nombre");
        });
    }

    public function down(): void
    {
        Schema::table(
            "clases",
            fn(Blueprint $t) => $t->dropColumn("nombre_en"),
        );
        Schema::table("razas", fn(Blueprint $t) => $t->dropColumn("nombre_en"));
    }
};
