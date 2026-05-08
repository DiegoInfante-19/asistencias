<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void{
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
        ]);
    }
}

/*

estas son las tablas conceptuales que tengo planeado para los usuarios de
este sistema, ya que como viste tendremos tres tipos de usuarios 
tecnico(programador yo) administrador(secretari@) profesor(profesor)

1) consideras que esta bien la estructura, para llevar estos tres tipos de usuarios?

 Usuario{
    id
    cedula
    nombres
    apellidos
    correo
    telefono
    usuario
    estatus(activo,inactivo,suspendido)
    fecha_hora_creacion
    fecha_hora_actualizacion
    fecha_hora_ultimo_acceso
    password
}

Profesor{ (luego se le añadiran mas cosas)
    id
    id_usuario
}

Preguntas secretas {
    id
    id_usuario
    pregunta1
    pregunta2
    respuesta1
    respuesta2
}

roles{
    id_rol
    nombre
    descripcion
}


ten en cuenta las tablas que ya he hecho y las que trae laravel por defecto

asistenicas (yo la hice, la borrare/modificare despues pero no ahora)
cahe (laravel)
cache_locks (laravel)
failed_jobs (laravel)
jobs (laravel)
job_batches (laravel)
miembros (la borrare despues pero no ahora)
migrations(laravel)
ministerios (yo la hice, la borrare despues pero no ahora)
password_reset_tokens (laravel)
sessions (laravel)
user (laravel)

consideras que esto es suficiente en cuanto a base de datos para llevar un modulo de usuarios

----------------------------------------------------------------------

la tabla de usuarios de laravel "User" tiene los siguientes campos

    id
    name
    email
    email_verifed_at (para que sirve o que significa ?)
    password
    remember_token (para que sirve o que significa ?)
    created_at (para que sirve o que significa ?)
    updated_at (para que sirve o que significa ?)

tenendo en cuenta eso cuales son las columnas que tengo que agrar 
y que nombres debeo de ponerles par aque sean compatibles y no hacer doble trabajo


partiendo de base a lo que me diste "Table users {
  id integer [primary key]
  cedula string [unique]
  nombres string
  apellidos string
  email string [unique]
  username string [unique]
  password string
  role_id integer // Relación con tabla roles
  status string // activo, inactivo, suspendido
  remember_token string
  created_at timestamp
  updated_at timestamp
  last_login_at timestamp
}

// Tu tabla de roles
Table roles {
  id integer [primary key]
  nombre string // tecnico, administrador, profesor
  descripcion text
}

// Datos específicos si el usuario es profesor
Table profesores {
  id integer [primary key]
  user_id integer [unique] // Relación con users
  // Aquí irán facultad, escalafón, etc.
}"

------------------------------------

a la final mi tabla users quedo asi

	1	id Primaria	bigint(20)		UNSIGNED	AUTO_INCREMENT	
	2	name	varchar(255)	utf8mb4_unicode_ci					
	3	last_name	varchar(255)	utf8mb4_unicode_ci				
	4	cedula	varchar(50)	utf8mb4_unicode_ci			
	5	email 	varchar(255)	utf8mb4_unicode_ci				
	6	email_verified_at	timestamp			Sí	NULL			
	7	phone	varchar(150)	utf8mb4_unicode_ci					
	8	username	varchar(255)	utf8mb4_unicode_ci					
	9	status	int(11)	(no es mejor que este tenga por defecto una lista interna con estas tres opciones "activo,inactivo,suspendido" no me acuerdo como se llama ese tipo de valor)		
	10	role_id	int(11)			esto es una llave foranea acuerdate		
	11	last_login_at	timestamp			Sí	NULL				
	12	password	varchar(255)	utf8mb4_unicode_ci					
	13	remember_token	varchar(100)	utf8mb4_unicode_ci		Sí	NULL				
	14	created_at	timestamp			Sí	NULL				
	15	updated_at	timestamp			Sí	NULL
 
    ayudame a corregirla antes de seguir


*/