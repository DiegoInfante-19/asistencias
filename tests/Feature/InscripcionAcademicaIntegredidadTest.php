<?php

namespace Tests\Feature;

use App\Models\Persona;
use App\Models\Pnf;
use App\Models\Cohorte;
use App\Models\GrupoAcademico;
use App\Models\TitulacionPersona;
use App\Models\InscripcionCohorte;
use App\Models\User;
use App\Enums\NivelAcademico;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class InscripcionAcademicaIntegredidadTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $persona;
    protected $pnfInformatica;
    protected $pnfMecanica;
    protected $grupoInformatica;
    protected $grupoMecanica;

    protected function setUp(): void
    {
        parent::setUp();

        // 1. Crear el Rol Administrador en la tabla roles (Evita error de FK en users)
        $rolAdminId = DB::table('roles')->insertGetId([
            'id_rol' => User::ROLE_ADMINISTRADOR,
            'nombre_rol' => 'Administrador',
            // Agrega aquí otros campos obligatorios de tu tabla roles si los hubiera (ej: descripcion)
        ]);

        // 2. Creación segura del Usuario de Pruebas
        $this->user = User::first() ?? User::forceCreate([
            'name_users'      => 'Admin',
            'last_name_users' => 'Test',
            'cedula_users'    => '12345678',
            'email_users'     => 'admin@test.com',
            'username'        => 'admintest',
            'status_users'    => 'Activo',
            'id_rol'          => $rolAdminId,
            'password_users'  => bcrypt('password'),
        ]);

        // 3. Creación de PNFs
        $this->pnfInformatica = Pnf::create(['nombre_pnf' => 'Ingeniería en Informática', 'vigencia_pnf' => 1]);
        $this->pnfMecanica = Pnf::create(['nombre_pnf' => 'Ingeniería Mecánica', 'vigencia_pnf' => 1]);

        // 4. Catálogos requeridos por titulacion_personas
        DB::table('titulos')->insertGetId([
            'nombre_titulo_base' => 'Técnico Superior Universitario',
            'nivel_academico' => 'TSU'
        ]);

        DB::table('estatus_expedientes')->insertGetId([
            'nombre_estatus_expediente' => 'Activo'
        ]);

        // 5. Cohorte y Grupos Académicos
        $cohorte = Cohorte::create([
            'numero_cohorte' => '2026-I',
            'fecha_inicio_cohorte' => '2026-01-01',
            'fecha_fin_cohorte' => '2026-12-31',
            'estatus_cohorte' => 'Activo'
        ]);

        $this->grupoInformatica = GrupoAcademico::create([
            'id_cohortes' => $cohorte->id_cohortes,
            'id_pnf' => $this->pnfInformatica->id_pnf,
            'nivel_academico' => NivelAcademico::TSU,
            'estatus_grupo' => 'Activo'
        ]);

        $this->grupoMecanica = GrupoAcademico::create([
            'id_cohortes' => $cohorte->id_cohortes,
            'id_pnf' => $this->pnfMecanica->id_pnf,
            'nivel_academico' => NivelAcademico::TSU,
            'estatus_grupo' => 'Activo'
        ]);

        // 6. Ubicación Geográfica
        $estadoId = DB::table('estados')->insertGetId([
            'nombre_estado' => 'Capital'
        ]);

        $ciudadId = DB::table('ciudades')->insertGetId([
            'id_estado' => $estadoId,
            'nombre_ciudad' => 'Caracas'
        ]);

        $lugarNacimientoId = DB::table('lugar_nacimiento_personas')->insertGetId([
            'id_ciudad' => $ciudadId
        ]);

        // 7. Registro de Estudiante de Prueba
        $this->persona = Persona::create([
            'cedula_personas' => '28123456',
            'primer_nombre_personas' => 'Carlos',
            'primer_apellido_personas' => 'Prueba',
            'sexo_personas' => 'M',
            'fecha_nacimiento_personas' => '2000-01-01',
            'id_lugar_nacimiento' => $lugarNacimientoId,
            'email_personas' => 'carlos@test.com'
        ]);
    }

    public function test_prueba_ruta_feliz_permite_inscripcion_mismo_pnf()
    {
        $this->actingAs($this->user);

        TitulacionPersona::create([
            'id_personas' => $this->persona->id_personas,
            'id_titulacion' => 1,
            'id_pnf' => $this->pnfInformatica->id_pnf,
            'id_estatus_expediente' => 1
        ]);

        $response = $this->post(route('personas.inscripciones.store', $this->persona->id_personas), [
            'id_personas' => $this->persona->id_personas,
            'id_grupo' => $this->grupoInformatica->id_grupo,
            'fecha_inscripcion' => '2026-06-15',
            'estatus_inscripcion_cohortes' => 'Activo'
        ]);

        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('inscripcion_cohortes', [
            'id_personas' => $this->persona->id_personas,
            'id_grupo' => $this->grupoInformatica->id_grupo,
        ]);
    }

    public function test_prueba_brecha_frontend_rechaza_inscripcion_pnf_diferente()
    {
        $this->actingAs($this->user);

        TitulacionPersona::create([
            'id_personas' => $this->persona->id_personas,
            'id_titulacion' => 1,
            'id_pnf' => $this->pnfInformatica->id_pnf,
            'id_estatus_expediente' => 1
        ]);

        $response = $this->post(route('personas.inscripciones.store', $this->persona->id_personas), [
            'id_personas' => $this->persona->id_personas,
            'id_grupo' => $this->grupoMecanica->id_grupo,
            'fecha_inscripcion' => '2026-06-15',
            'estatus_inscripcion_cohortes' => 'Activo'
        ]);

        $response->assertSessionHasErrors(['id_grupo']);

        $this->assertDatabaseMissing('inscripcion_cohortes', [
            'id_personas' => $this->persona->id_personas,
            'id_grupo' => $this->grupoMecanica->id_grupo,
        ]);
    }

    public function test_prueba_integridad_modelos_lanza_excepcion_directa_en_nucleo()
    {
        TitulacionPersona::create([
            'id_personas' => $this->persona->id_personas,
            'id_titulacion' => 1,
            'id_pnf' => $this->pnfMecanica->id_pnf,
            'id_estatus_expediente' => 1
        ]);

        $this->expectException(ValidationException::class);

        InscripcionCohorte::create([
            'id_personas' => $this->persona->id_personas,
            'id_grupo' => $this->grupoInformatica->id_grupo,
            'fecha_inscripcion' => '2026-06-15',
            'estatus_inscripcion_cohortes' => 'Activo'
        ]);
    }
}