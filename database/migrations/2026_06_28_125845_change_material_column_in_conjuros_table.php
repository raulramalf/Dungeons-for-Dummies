<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('conjuros', function (Blueprint $table) {
            $table->text('material')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('conjuros', function (Blueprint $table) {
            $table->string('material')->nullable()->change();
        });
    }
};