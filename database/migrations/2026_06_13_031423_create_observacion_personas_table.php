<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('observacion_personas', function (Blueprint $table) {
            $table->id('id_observacion_personas');
            $table->foreignId('id_personas')->constrained('personas', 'id_personas')->onDelete('cascade');
            $table->text('observacion_personas');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('observacion_personas');
    }
};