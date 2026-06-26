<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Diccionario Central de Expresiones Regulares
    |--------------------------------------------------------------------------
    */

    'username' => [
        // (?=.*\d.*\d) fuerza a que haya al menos 2 números
        'php'   => '/^[A-Z](?=.*[a-z])(?=.*\d.*\d)[a-zA-Z0-9_]{3,19}$/',
        'html'  => '^[A-Z](?=.*[a-z])(?=.*\d.*\d)[a-zA-Z0-9_]{3,19}$',
        'error' => 'Debe iniciar con mayúscula, usar minúsculas y al menos 2 números. Sin espacios (4-20 caracteres).'
    ],

    'email' => [
        'php'   => '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
        'html'  => '^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$',
        'error' => 'Ingrese un correo electrónico válido.'
    ],

    // Nota: Cambié la llave a 'name' para que coincida con name="name" de tu formulario
    'name' => [
        'php'   => '/^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]{3,50}$/',
        'html'  => '^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]{3,50}$',
        'error' => 'Solo se permiten letras y espacios (mínimo 3 caracteres).'
    ],

    'last_name' => [ // Nueva entrada
    'php'   => '/^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]{3,50}$/',
    'html'  => '^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]{3,50}$',
    'error' => 'Solo se permiten letras y espacios.'
],

    'cedula' => [
        'php'   => '/^\d{6,8}$/',
        'html'  => '^\d{6,8}$',
        'error' => 'La cédula debe tener entre 6 y 8 números exactos. Sin espacios.'
    ],

    'phone' => [
        // Se agregó ^ y $ para que no acepte 30 caracteres, solo 10 u 11 exactos
        'php'   => '/^[0-9]{10,11}$/',
        'html'  => '^[0-9]{10,11}$',
        'error' => 'El teléfono debe tener entre 10 y 11 números, sin guiones ni espacios.'
    ],

    'password' => [
        'php'   => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&.+-])[A-Za-z\d@$!%*?&.+-]{8,64}$/',
        'html'  => '^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&.+-])[A-Za-z\d@$!%*?&.+-]{8,64}$',
        'error' => 'Mínimo 8 caracteres: al menos una mayúscula, una minúscula, un número y un símbolo. Sin espacios.'
    ],

    'respuesta_seguridad' => [
        'php'   => '/^.{3,150}$/',
        'html'  => '^.{3,150}$',
        'error' => 'La respuesta debe tener entre 3 y 150 caracteres.'
    ]
];
