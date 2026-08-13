<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profesor_seccion', function (Blueprint $table) {
            $table->id('id_profesor_seccion');
            $table->foreignId('id_profesor')->constrained('profesores', 'id_profesor')->onDelete('cascade');
            $table->foreignId('id_seccion')->constrained('secciones', 'id_seccion')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['id_profesor', 'id_seccion'], 'uk_prof_seccion');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('profesor_seccion');
    }
};