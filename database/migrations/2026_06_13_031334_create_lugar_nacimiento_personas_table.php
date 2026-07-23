<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration{
    public function up(): void{
        Schema::create('lugar_nacimiento_personas', function (Blueprint $table) {
            $table->id('id_lugar_nacimiento');
            // CORREGIDO: Se elimina la clave foránea a estados. Solo se conserva la ciudad.
            $table->foreignId('id_ciudad')->constrained('ciudades', 'id_ciudad')->onDelete('restrict');
            $table->string('detalles_adicionales', 255)->nullable();
            $table->timestamps();
        });
    }
    public function down(): void{
        Schema::dropIfExists('lugar_nacimiento_personas');
    }
};

// use Illuminate\Database\Migrations\Migration;
// use Illuminate\Database\Schema\Blueprint;
// use Illuminate\Support\Facades\Schema;

// return new class extends Migration
// {
//     public function up(): void
//     {
//         Schema::create('lugar_nacimiento_personas', function (Blueprint $table) {
//             $table->id('id_lugar_nacimiento');
            
//             // Llaves foráneas con restricción de integridad
//             $table->foreignId('id_estado')->constrained('estados', 'id_estado')->onDelete('restrict');
//             $table->foreignId('id_ciudad')->constrained('ciudades', 'id_ciudad')->onDelete('restrict');
            
//             $table->string('detalles_adicionales', 255)->nullable();
//             $table->timestamps();
//         });
//     }

//     public function down(): void
//     {
//         Schema::dropIfExists('lugar_nacimiento_personas');
//     }
// };