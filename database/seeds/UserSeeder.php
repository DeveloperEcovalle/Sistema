<?php
use App\User;
use App\Mantenimiento\Persona\Persona;
use App\Mantenimiento\Empleado\Empleado;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $persona = new Persona();
        $persona->tipo_documento = 'DNI';
        $persona->documento = '71114110';
        $persona->codigo_verificacion = 2;
        $persona->nombres = 'CARLOS';
        $persona->apellido_paterno = 'ALVAREZ';
        $persona->apellido_materno = 'LOPEZ';
        $persona->fecha_nacimiento = Carbon::parse('2000-01-01');
        $persona->sexo = 'H';
        $persona->estado_civil = 'S';
        $persona->departamento_id = '02';
        $persona->provincia_id = '0218';
        $persona->distrito_id = '021809';
        $persona->direccion = 'GARATEA';
        $persona->correo_electronico = 'AXELGUTIERREZLOPEZ26@GMAIL.COM';
        $persona->telefono_movil = '99999999999';
        $persona->estado = 'ACTIVO';
        $persona->save();

        $empleado = new empleado();
        $empleado->persona_id = 1;
        $empleado->area = 'COMERCIAL';
        $empleado->profesion = 'ING.SISTEMAS';
        $empleado->cargo = 'GERENTE GENERAL';
        $empleado->telefono_referencia = '2121212';
        $empleado->contacto_referencia = 'LOPEZ';
        $empleado->grupo_sanguineo = 'O-';
        $empleado->numero_hijos = 10;
        $empleado->sueldo = 1200;
        $empleado->sueldo_bruto = 1200;
        $empleado->sueldo_neto = 1200;
        $empleado->moneda_sueldo = 'S/.';
        $empleado->tipo_banco = 'BN';
        $empleado->numero_cuenta = '2020202';
        $empleado->fecha_inicio_actividad = Carbon::parse('2000-01-01');
        $empleado->fecha_fin_actividad = Carbon::parse('2000-01-01');
        $empleado->fecha_inicio_planilla = Carbon::parse('2000-01-01');
        $empleado->fecha_fin_planilla = Carbon::parse('2000-01-01');
        $empleado->estado = 'ACTIVO';
        $empleado->save();

        $user = new User();
        $user->usuario = 'Administrador';
        $user->empleado_id = 1;
        $user->email = 'admin@ecovalle.com';
        $user->password = bcrypt('admin');
        $user->save();

    }


}