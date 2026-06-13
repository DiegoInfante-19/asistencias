<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('estatus_expedientes', function (Blueprint $table) {
            $table->id('id_estatus_expediente');
            $table->string('nombre_estatus_expediente', 50)->unique();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('estatus_expedientes');
    }
};