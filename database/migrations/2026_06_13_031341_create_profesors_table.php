<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profesores', function (Blueprint $table) {
            $table->id('id_profesor');
            
            // Relación con User: Si se borra el usuario, se borra su perfil de profesor
            $table->foreignId('id_users')->constrained('users', 'id_users')->onDelete('cascade');
            $table->foreignId('id_pnf')->constrained('pnfs', 'id_pnf')->onDelete('restrict');
            
            // NUEVO: Nivel Académico (TSU o Ingeniería) en string para usar Backed Enums
            $table->string('nivel_asignado', 20);
            
            $table->date('fecha_asignacion_profesor');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profesores');
    }
};