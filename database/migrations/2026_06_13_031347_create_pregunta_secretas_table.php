<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('preguntas_secretas', function (Blueprint $table) {
            $table->id('id_preguntas_secretas');
            
            // Relación directa con el User
            $table->foreignId('id_users')->constrained('users', 'id_users')->onDelete('cascade');
            
            $table->string('pregunta1', 150);
            $table->string('pregunta2', 150);
            $table->string('respuesta1', 150);
            $table->string('respuesta2', 150);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('preguntas_secretas');
    }
};