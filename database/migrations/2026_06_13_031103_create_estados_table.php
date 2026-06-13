<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('estados', function (Blueprint $table) {
            $table->id('id_estado');
            $table->string('nombre_estado', 100)->unique();
            // No requiere timestamps por ser un catálogo fijo
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('estados');
    }
};