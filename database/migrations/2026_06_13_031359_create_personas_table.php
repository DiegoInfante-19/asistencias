<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('personas', function (Blueprint $table) {
            $table->id('id_personas');
            $table->string('cedula_personas', 20)->unique();

            // Agregamos index() para optimizar las búsquedas en las tablas (DataTables)
            $table->string('primer_nombre_personas', 50)->index()->comment('Se almacena en MAYÚSCULAS');
            $table->string('segundo_nombre_personas', 50)->nullable();
            $table->string('primer_apellido_personas', 50)->index()->comment('Se almacena en MAYÚSCULAS');
            $table->string('segundo_apellido_personas', 50)->nullable();

            $table->char('sexo_personas', 1); // 'M' o 'F'
            $table->date('fecha_nacimiento_personas')->nullable();

            // Llave Foránea hacia Lugar de Nacimiento
            $table->foreignId('id_lugar_nacimiento')->nullable()->constrained('lugar_nacimiento_personas', 'id_lugar_nacimiento')->onDelete('restrict');

            $table->string('email_personas')->nullable()->unique();

            // --- NUEVO: Sello de ingreso de Cohorte ---
            $table->foreignId('id_cohortes')->nullable()->constrained('cohortes', 'id_cohortes')->onDelete('restrict');
            
            // Auditoría y Seguridad
            $table->softDeletes(); // Columna deleted_at
            $table->timestamps();  // created_at y updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('personas');
    }
};
