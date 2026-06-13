/*

-- Tablas para toda la informacion relacionada  
-- con los usuarios, profesores y sus roles:

Nivel 1 (Catálogos y Entidades Base): Tablas que no tienen llaves foráneas.
  roles, x
  estados, x
  cargos, x
  empresas, x
  pnf, x
  estatus_expediente, x
  titulos, x
  periodo_receso, x
  cohortes.

Nivel 2 (Dependencias de 1er Grado):

  ciudades (depende de estados).
  users (depende de roles).
  titulos_pnf (depende de pnf y titulos).
  empresa_pnf (depende de empresas y pnf).

Nivel 3 (Dependencias de 2do Grado):
  lugar_nacimiento_personas (depende de estados y ciudades).
  profesores (depende de users y pnf).
  preguntas_secretas (depende de users).

Nivel 4 (El Núcleo - Entidad Persona):

  personas (depende de lugar_nacimiento_personas).

Nivel 5 (Satélites de Personas): Tablas que dependen de personas y otras creadas previamente.
  telefonos_personas, 
  titulacion_personas, 
  observacion_personas, 
  empresa_personas, 
  acreditaciones, 
  persona_formacion_academica.

Nivel 6 (El Flujo Transaccional):
  inscripcion_cohortes (depende de personas y cohortes).
  sesiones (depende de cohortes y profesores).
  asistencias (depende de sesiones y inscripcion_cohortes).

users: 
  id_users(pk), 
  name_users, 
  last_name_users, 
  cedula_users, 
  email_users, 
  email_verified_at, 
  phone_users, 
  username, 
  status_users, 
  id_rol(fk), 
  last_login_at, 
  password_users, 
  remember_token, 
  created_at, 
  updated_at

preguntas_secretas: X
  id_preguntas_secretas(pk), 
  id_users(fk), 
  pregunta1, 
  pregunta2, 
  respuesta1, 
  respueta2.

roles: X
  id_rol(pk), 
  nombre_rol, 
  descripcion_rol, 
  created_at, 
  updated_at.

profesores: X
  id_profesor(pk), 
  id_users(fk), 
  id_pnf(fk), 
  fecha_asignacion_profesor.

-- Tablas que trae laravel por defecto para el manejo de sesiones, cache, trabajos, etc:

cache:
  key,
  value,
  expiration

cache_locks:
  key,
  owner,
  expiration

failed_jobs:
  id,
  uuid,
  connection,
  queue,
  payload,
  exception,
  failed_at

jobs:
  id,
  queue,
  payload,
  attempts,
  reserved_at,
  available_at,
  created_at

job_batches:
  id,
  name,
  total_jobs,
  pending_jobs,
  failed_jobs,
  failed_job_ids,
  options,
  cancelled_at,
  created_at,
  finished_at

migrations:
  id,
  migration,
  batch

password_reset_tokens:
  email,
  token,
  created_at

sessions:
  id,
  user_id,
  ip_address,
  user_agent,
  payload,
  last_activity,

-- Tablas para toda la informacion relacionada con las 
   personas / estudientes / trabajadores y su normalizacion:

estatus_expediente: X
  id_estatus_expediente(pk),
  nombre_estatus_expediente,

personas: X
  id_personas(pk),
  cedula_personas,
  primer_nombre_personas,
  segundo_nombre_personas,
  primer_apellido_personas,
  segundo_apellido_personas,
  sexo_personas,
  fecha_nacimiento_personas,
  id_lugar_nacimiento(fk),
  email_personas,
  softDelete
  created_at, 
  updated_at

telefonos_personas: X
  id_telefonos_personas(pk),
  id_personas(fk),
  numero_telefono_personas,
  tipo_telefono, (personal, casa, trabajo, etc)

titulacion_personas: X
  id_titulacion_personas(pk),
  id_personas(fk),
  id_titulacion(fk), (contexto: titulo a optar)
  id_pnf(fk),        (contexto: pnf solicitado, del titulo a optar)
  id_estatus_expediente(fk),

estados X
  id_estado(pk),
  nombre_estado,

ciudades X
  id_ciudad(pk),
  id_estado(fk),
  nombre_ciudad,

lugar_nacimiento_personas X
  id_lugar_nacimiento(pk),
  id_estado(fk),
  id_ciudad(fk), 
  detalles_adicionales
  created_at, 
  updated_at

Observacion_personas: X
  id_observacion_personas(pk),
  id_personas(fk),
  observacion_personas,
  softDelete
  created_at, 
  updated_at

Empresa_personas: X
  id_empresa_personas(pk),
  id_personas(fk),
  id_empresa(fk),
  id_cargo(fk),
  softDelete
  created_at, 
  updated_at

Cargo: X
  id_cargo(pk),
  descripcion_cargo,
  created_at, 
  updated_at

-- Tablas para toda la informacion relacionada con las empresas CVG

empresas: X
  id_empresa(pk),
  nombre_empresa,

empresa_pnf: X
  id_empresa_pnf(pk),
  id_empresa(fk),
  id_pnf(fk),
  tipo_relacion
  observacion_empresa_pnf

Acreditaciones: X
  id_acreditaciones(pk),
  id_personas(fk),
  id_empresa(fk),
  id_pnf(fk),
  estatus_acreditacion 
  softDelete
  created_at, 
  updated_at

-- Tablas para toda la informacion relacionada con con los pnf sus titulos y certificaciones

pnf: X
  id_pnf(pk),
  nombre_pnf,
  descripcion_pnf,
  vigenica_pnf,
  created_at,
  updated_at

titulos_pnf: X
  id_titulos_pnf(pk),
  id_pnf(fk),
  id_titulo(fk)
  nombre_titulo_pnf,
  created_at, 
  updated_at

titulos: X
  id_titulos(pk),
  nombre_titulo_base, (TSU, INGENIERIA, LICENCIATURA, TECNICO MEDIO, BACHILLER. etc)
  nivel_academico,    (media, tecnica, universitaria, postgrado, certificacion. etc)
 
persona_formacion_academica:  X          (un aspecto tiene esto que se detalla en la observacion numero 6)
  id_persona_formacion_academica(pk),
  id_personas(fk),
  id_titulos_pnf(fk), (nullable)
  id_titulos(fk),     (nullable)
  observacion_formacion_academica,
  softDelete
  created_at, 
  updated_at


-- Tablas para toda la informacion relacionada con las cohortes, meses, inscripciones, asistenicas, etc
   
cohortes: X
  id_cohortes(pk),
  numero_cohorte,
  fecha_inicio_cohorte,
  fecha_fin_cohorte,
  descripcion_cohorte,
  estatus_cohorte, (en curso, finalizada, proxima)

periodo de receso: X
  id_periodo_receso(pk),
  nombre_periodo_receso,
  fecha_inicio_periodo_receso,
  fecha_fin_periodo_receso,
  descripcion_periodo_receso,
  nivel_periodo_receso (temporario, permanente)

inscripcion_cohortes: X
  id_inscripcion_cohortes(pk),
  id_personas(fk),
  id_cohortes(fk),
  fecha_inscripcion,
  estatus_inscripcion_cohortes
  softDelete
  created_at, 
  updated_at

sesiones: X
  id_sesiones(pk),
  id_cohortes(fk),
  fecha_sesion,
  id_profesor(fk),
  observacion_sesion
  softDelete
  created_at, 
  updated_at

asistencias: X
  id_asistencias(pk),
  id_sesiones(fk),
  id_inscripcion_cohortes(fk),
  estado_asistencia (presente, ausente, justificada),
  observacion_asistencia
  softDelete
  created_at, 
  updated_at


A tener en cuenta para la implementación de tu base de datos:

  1. Validación de Integridad Referencial (El "vínculo" entre tablas)

  Ya que tu modelo tiene muchas relaciones, asegúrate de que al crear las migraciones, 
  definas correctamente el comportamiento en cascada. Por ejemplo:

  En inscripcion_cohortes: Si una persona es eliminada, ¿debería borrarse su inscripción? 
  Mi recomendación es onDelete('restrict'). No permitas que se borre una persona si tiene 
  inscripciones activas; obliga al sistema a archivar primero la inscripción antes de 
  borrar a la persona.

  En asistencias: Esta tabla debe tener un onDelete('cascade') hacia sesiones. 
  Si se borra la sesión, la asistencia deja de existir.

  2. Optimización en la tabla personas
  Tu tabla personas tiene una relación con lugar_nacimiento_personas. Actualmente, 
  parece que lugar_nacimiento_personas es una tabla aparte que a su vez se conecta 
  a ciudades y estados.

  Sugerencia: Si cada persona tiene exactamente un lugar de nacimiento, podrías 
  simplificar esto poniendo los id_estado e id_ciudad directamente en la tabla 
  personas. Esto ahorra un JOIN pesado cada vez que consultes a una persona. 
  Si prefieres mantener la tabla aparte por diseño, asegúrate de que la relación 
  sea 1:1.

  3. La tabla titulacion_personas
  Esta tabla tiene id_titulacion y id_pnf. Esto es correcto, pero recuerda:

  Si el sistema necesita saber qué título obtuvo el estudiante en cada PNF, asegúrate 
  de que en tu lógica de negocio siempre valides que el id_titulo realmente pertenezca 
  al id_pnf (a través de la tabla titulos_pnf). Esto evita errores de datos donde un 
  estudiante obtiene un título que no corresponde a su carrera.

  4. Estructura visual de tu Base de Datos
  Para que te visualices cómo está conectada toda esta arquitectura, aquí tienes 
  una representación de cómo fluyen los datos en tu sistema:

  5. Recomendación de "Performance" (Indexación)
  Como vas a consultar mucho por cédula y estatus, te sugiero agregar índices 
  explícitos en tus migraciones para mejorar la velocidad:

  $table->index('cedula_personas'); (Esto hará que las búsquedas por cédula 
  sean instantáneas, incluso con 100,000 estudiantes).

  $table->index('id_cohortes'); en la tabla sesiones (es vital para cargar 
  las asistencias rápidamente).


-------------------------------------
pasos para la implementacion inicial de la base de datos:

Paso 1: Establecer la Jerarquía de Migraciones (El paso más crítico)

    En Laravel, las migraciones se ejecutan en orden alfabético/cronológico 
    basado en la fecha de creación del archivo. No puedes crear la tabla 
    personas si antes no existen las tablas estados y ciudades (porque 
    dependen de ellas).

  Debemos agrupar tus tablas en tres niveles para crearlas en este orden exacto:

  Nivel 1 (Tablas Independientes): No dependen de nadie. Aquí crearemos roles, 
  estados, cargos, empresas, pnf, estatus_expediente, titulos y periodo_receso.

  Nivel 2 (Dependencia Simple): Requieren al menos una tabla del Nivel 1. Aquí 
  van ciudades (depende de estados), users (depende de roles), titulos_pnf, 
  cohortes y empresa_pnf.

  Nivel 3 (Alta Dependencia): Son el núcleo transaccional. Aquí entran personas, 
  profesores, sesiones, inscripcion_cohortes, acreditaciones y, por último, 
  asistencias.

Paso 2: Generación de los Archivos de Migración y Modelos

  En lugar de crear la migración por un lado y el modelo por otro, 
  la mejor práctica es decirle a Laravel que haga ambos al mismo tiempo 
  usando la consola (Artisan).

  Deberás ejecutar comandos por cada tabla respetando el orden del 
  Paso 1. Por ejemplo: crear el modelo y la migración para Role, 
  luego para Estado, luego para Ciudad, etc. Esto generará los 
  archivos en blanco listos para ser llenados.

Paso 3: Redacción del "Blueprint" (Estructura de las Migraciones)

  Una vez creados los archivos, deberás entrar a la carpeta 
  database/migrations y traducir tu esquema a código PHP. 

  En este paso debes prestar atención a tres detalles fundamentales:

  Llaves Primarias Personalizadas: Como decidiste usar nombres como 
  id_users en lugar del clásico id, deberás indicarlo 
  explícitamente (ej. $table->id('id_users');).

  Integridad Referencial: Al crear las llaves foráneas, 
  deberás definir las reglas lógicas 
  (ej. $table->foreignId('id_estado')->constrained('estados', 'id_estado')->onDelete('restrict');).

  Auditoría: En las tablas transaccionales (Nivel 3), agregarás los métodos 
  $table->timestamps(); y $table->softDeletes(); que acordamos previamente.

Paso 4: Ejecución y Pruebas de las Migraciones
  
  Con el código redactado, darás la orden a Laravel para que construya 
  las tablas físicas en MySQL.

  Si hay un error de tipeo o de orden (ej. intentaste conectar una llave 
  foránea a una tabla que no existe), el proceso se detendrá. Utilizarás 
  comandos de "rollback" (marcha atrás) para deshacer los cambios, corregir 
  el archivo y volver a intentar hasta que toda la base de datos se construya 
  limpiamente en un solo comando.

Paso 5: Configuración de los Modelos (Eloquent)

  Tener las tablas en la base de datos no es suficiente; debes decirle a los 
  Modelos de Laravel cómo interactuar con ellas. Entrarás a cada archivo dentro 
  de la carpeta app/Models/ para configurar:

  La Tabla y Llave Primaria: Como no usamos las convenciones estándar de Laravel, 
  debes escribir explícitamente en el modelo: protected $table = 'users'; y 
  protected $primaryKey = 'id_users';.

  Seguridad de Asignación Masiva: Definirás el arreglo protected $fillable = [...]; 
  listando exactamente qué columnas se pueden llenar a través de un formulario 
  para evitar inyecciones de datos.

  Borrado Suave: En los modelos transaccionales, importarás y usarás el trait 
  SoftDeletes para habilitar la papelera de reciclaje lógica.

Paso 6: Construcción de las Relaciones (Eloquent Relationships)

  El último paso de la implementación de la base de datos es conectar los modelos. 
  Crearás métodos dentro de cada modelo para explicarle a Laravel cómo llegar de 
  un dato a otro sin escribir SQL manual.

  Por ejemplo, en el modelo Persona, crearás un método llamado asistencias() que 
  retornará un $this->hasMany(...), y en el modelo Asistencia, crearás un método 
  persona() que retornará un $this->belongsTo(...).

  Para empezar a redactar el código real y exacto: ¿Quieres que definamos la lista 
  de comandos del Paso 2 para crear todos los archivos en el orden perfecto, o 
  prefieres que comencemos directamente a redactar el código de migración de las 
  tablas del Nivel 1?


cumpli con todos los pasos hasta ahora
preguntar acerca de lo que hice
seguir con lo que toque


*/<