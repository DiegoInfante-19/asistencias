<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id('id_users');
            $table->string('name_users', 100);
            $table->string('last_name_users', 100);
            $table->string('cedula_users', 20)->unique();
            $table->string('email_users')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('phone_users', 20)->nullable();
            $table->string('username', 50)->unique();
            $table->string('status_users', 20)->default('Activo');
            
            // Llave Foránea hacia Roles
            $table->foreignId('id_rol')->constrained('roles', 'id_rol')->onDelete('restrict');
            
            $table->timestamp('last_login_at')->nullable();
            $table->string('password_users');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};