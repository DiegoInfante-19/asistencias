window.CoreRules = {
    // USUARIO
    username: { 
    regex: /^[A-Z](?=.*\d)[a-zA-Z0-9_]{3,19}$/, 
    error: 'Debe iniciar con mayúscula, usar minúsculas y al menos un número, Sin espacios (4-20 caracteres).' 
},
    
    // CORREOS (Soporta ambos nombres de inputs)
    email: { 
        regex: /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/, 
        error: 'Ingrese un correo electrónico válido.' 
    },
    email_users: { 
        regex: /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/, 
        error: 'Ingrese un correo electrónico válido.' 
    },
    
    // NOMBRES (Soporta ambos nombres de inputs)
    name: { 
        regex: /^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]{3,50}$/, 
        error: 'Solo letras y espacios (mínimo 3 caracteres).' 
    },
    name_users: { 
        regex: /^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]{3,50}$/, 
        error: 'Solo letras y espacios (mínimo 3 caracteres).' 
    },
    
    // APELLIDOS
    last_name: { 
        regex: /^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]{3,50}$/, 
        error: 'Solo letras y espacios (mínimo 3 caracteres).' 
    },
    last_name_users: { 
        regex: /^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]{3,50}$/, 
        error: 'Solo letras y espacios (mínimo 3 caracteres).' 
    },
    
    // CÉDULA
    cedula: { 
        regex: /^\d{6,8}$/, 
        error: 'La cédula debe tener entre 6 y 8 números exactos. Sin espacios' 
    },
    cedula_users: { 
        regex: /^\d{6,8}$/, 
        error: 'La cédula debe tener entre 6 y 8 números exactos. Sin espacios' 
    },
    
    // TELÉFONO
    phone: { 
        regex: /^\d{10,11}$/, 
        error: 'El teléfono debe tener entre 10 y 11 números, sin guiones. Sin espacios' 
    },
    phone_users: { 
        regex: /^\d{10,11}$/, 
        error: 'El teléfono debe tener entre 10 y 11 números, sin guiones. Sin espacios' 
    },
    
    // CONTRASEÑA (Mucho más segura y amigable)
    password: { 
        regex: /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&.+-])[A-Za-z\d@$!%*?&.+-]{8,64}$/, 
        error: 'Mínimo 8 caracteres: al menos una mayúscula, una minúscula, un número y un símbolo. Sin espacios' 
    },

    respuesta1: { 
        // Acepta cualquier carácter, pero exige un mínimo de 3 caracteres.
        regex: /^.{3,150}$/, 
        error: 'La respuesta debe tener entre 3 y 150 caracteres.' 
    },
    
    respuesta2: { 
        regex: /^.{3,150}$/, 
        error: 'La respuesta debe tener entre 3 y 150 caracteres.' 
    }
};