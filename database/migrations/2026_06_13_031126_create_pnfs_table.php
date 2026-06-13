<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pnfs', function (Blueprint $table) {
            $table->id('id_pnf');
            $table->string('nombre_pnf', 100)->unique();
            $table->text('descripcion_pnf')->nullable();
            $table->boolean('vigencia_pnf')->default(true); // true = activo, false = inactivo
            $table->timestamps(); // Solicitado en tu esquema
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pnfs');
    }
};