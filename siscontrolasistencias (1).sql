/*

-- Tablas para toda la informacion relacionada  
-- con los usuarios, profesores y sus roles:

cada columan con su distintivo por tabla
cada id con (pk)
cada id que es llave foranea con (fk)
cada tabla con su nombre en plural y en minuscula






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

preguntas_secretas: 
  id_preguntas_secretas(pk), 
  id_users(fk), 
  pregunta1, 
  pregunta2, 
  respuesta1, 
  respueta2.

roles: 
  id_rol(pk), 
  nombre_rol, 
  descripcion_rol, 
  created_at, 
  updated_at.

profesores: 
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

estatus_expediente:
  id_estatus_expediente(pk),
  nombre_estatus_expediente,

personas:
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


telefonos_personas:
  id_telefonos_personas(pk),
  id_personas(fk),
  numero_telefono_personas,
  tipo_telefono, (personal, casa, trabajo, etc)

titulacion_personas:
  id_titulacion_personas(pk),
  id_personas(fk),
  id_titulacion(fk), (contexto: titulo a optar)
  id_pnf(fk),        (contexto: pnf solicitado, del titulo a optar)
  id_estatus_expediente(fk),

estados
  id_estado(pk),
  nombre_estado,

ciudades
  id_ciudad(pk),
  id_estado(fk),
  nombre_ciudad,


lugar_nacimiento_personas 
  id_lugar_nacimiento(pk),
  id_estado(fk),
  id_ciudad(fk), 
  detalles_adicionales
  created_at, 
  updated_at


Observacion_personas:
  id_observacion_personas(pk),
  id_personas(fk),
  observacion_personas,
  softDelete
  created_at, 
  updated_at

Empresa_personas:
  id_empresa_personas(pk),
  id_personas(fk),
  id_empresa(fk),
  id_cargo(fk),
  softDelete
  created_at, 
  updated_at

Cargo:
  id_cargo(pk),
  descripcion_cargo,
  created_at, 
  updated_at

-- Tablas para toda la informacion relacionada con las empresas CVG

empresas:
  id_empresa(pk),
  nombre_empresa,

empresa_pnf:
  id_empresa_pnf(pk),
  id_empresa(fk),
  id_pnf(fk),
  tipo_relacion
  observacion_empresa_pnf

Acreditaciones:
  id_acreditaciones(pk),
  id_personas(fk),
  id_empresa(fk),
  id_pnf(fk),
  estatus_acreditacion 
  softDelete
  created_at, 
  updated_at

-- Tablas para toda la informacion relacionada con con los pnf sus titulos y certificaciones

pnf:
  id_pnf(pk),
  nombre_pnf,
  descripcion_pnf,
  vigenica_pnf,
  created_at,
  updated_at

titulos_pnf:
  id_titulos_pnf(pk),
  id_pnf(fk),
  id_titulo(fk)
  nombre_titulo_pnf,
  created_at, 
  updated_at

titulos:
  id_titulos(pk),
  nombre_titulo_base, (TSU, INGENIERIA, LICENCIATURA, TECNICO MEDIO, BACHILLER. etc)
  nivel_academico,    (media, tecnica, universitaria, postgrado, certificacion. etc)
 
persona_formacion_academica:            (un aspecto tiene esto que se detalla en la observacion numero 6)
  id_persona_formacion_academica(pk),
  id_personas(fk),
  id_titulos_pnf(fk), (nullable)
  id_titulos(fk),     (nullable)
  observacion_formacion_academica,
  softDelete
  created_at, 
  updated_at

-- Tablas para toda la informacion relacionada con las cohortes, meses, inscripciones, asistenicas, etc
   
cohortes:
  id_cohortes(pk),
  numero_cohorte,
  fecha_inicio_cohorte,
  fecha_fin_cohorte,
  descripcion_cohorte,
  estatus_cohorte, (en curso, finalizada, proxima)

periodo de receso:
  id_periodo_receso(pk),
  nombre_periodo_receso,
  fecha_inicio_periodo_receso,
  fecha_fin_periodo_receso,
  descripcion_periodo_receso,
  nivel_periodo_receso (temporario, permanente)

inscripcion_cohortes:
  id_inscripcion_cohortes(pk),
  id_personas(fk),
  id_cohortes(fk),
  fecha_inscripcion,
  estatus_inscripcion_cohortes
  softDelete
  created_at, 
  updated_at


sesiones:
  id_sesiones(pk),
  id_cohortes(fk),
  fecha_sesion,
  id_profesor(fk),
  observacion_sesion
  softDelete
  created_at, 
  updated_at

asistencias:
  id_asistencias(pk),
  id_sesiones(fk),
  id_inscripcion_cohortes(fk),
  estado_asistencia (presente, ausente, justificada),
  observacion_asistencia
  softDelete
  created_at, 
  updated_at


Normalización de Lugares (Paises, Estados, Ciudades)



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


*/