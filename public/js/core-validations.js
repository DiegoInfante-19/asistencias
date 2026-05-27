window.CoreRules = {
    username: { 
        regex: /^[a-z0-9]{4,20}$/, 
        error: 'Usa solo letras minúsculas y números (entre 4 y 20 caracteres).' 
    },
    email: { 
        regex: /^[a-zA-Z0-9._%+-]{1,64}@[a-zA-Z0-9.-]{1,251}\.com$/, 
        error: 'Ingrese un correo válido de max 320 caracteres (debe terminar en .com).' 
    },
    name: { 
        regex: /^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]{3,50}$/, 
        error: 'Solo letras y espacios (mínimo 3 caracteres).' 
    },
    last_name: { 
        regex: /^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]{3,50}$/, 
        error: 'Solo letras y espacios (mínimo 3 caracteres).' 
    },
    cedula: { 
        regex: /^\d{6,8}$/, 
        error: 'La cédula debe tener entre 6 y 8 números.' 
    },
    phone: { 
        regex: /^\d{10,11}$/, 
        error: 'El teléfono debe tener entre 10 y 11 números, sin guiones.' 
    },
    password: { 
        // 8 caracteres exactos: 2 mayúsculas, 2 minúsculas, 2 números, 2 caracteres especiales (+,*,$,%,&,-)
        regex: /^(?=(?:[^A-Z]*[A-Z]){2}[^A-Z]*$)(?=(?:[^a-z]*[a-z]){2}[^a-z]*$)(?=(?:[^0-9]*[0-9]){2}[^0-9]*$)(?=(?:[^+*$%&-]*[+*$%&-]){2}[^+*$%&-]*$)[A-Za-z0-9+*$%&-]{8}$/, 
        error: 'Exactamente 8 caracteres: 2 mayúsculas, 2 minúsculas, 2 números, 2 especiales (+,*,$,%,&,-).' 
    }
};