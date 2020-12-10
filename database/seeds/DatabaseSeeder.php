<?php

use App\User;
use App\Mantenimiento\Tabla\General;
use App\Mantenimiento\Tabla\Detalle;
use App\Mantenimiento\Ubigeo\Departamento;
use App\Mantenimiento\Ubigeo\Distrito;
use App\Mantenimiento\Ubigeo\Provincia;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $user = new User();
        $user->usuario = 'Administrador';
        $user->email = 'admin@ecovalle.com';
        $user->password = bcrypt('admin');
        $user->save();

        $tabla = new General();
        $tabla->descripcion = 'TIPOS DE MONEDA';
        $tabla->sigla = 'TIPOS DE MONEDA';
        $tabla->save();

        $tabla = new General();
        $tabla->descripcion = 'BANCOS';
        $tabla->sigla = 'BANCOS';
        $tabla->save();

        //Bancos
        $detalle = new Detalle();
        $detalle->descripcion = "Banco de la Nación";
        $detalle->estado = 'ACTIVO';
        $detalle->tabla_id = 2;
        $detalle->save();

        $detalle = new Detalle();
        $detalle->descripcion = "Intercontinental";
        $detalle->estado = 'ACTIVO';
        $detalle->tabla_id = 2;
        $detalle->save();

        $detalle = new Detalle();
        $detalle->descripcion = "Mi Banco";
        $detalle->estado = 'ACTIVO';
        $detalle->tabla_id = 2;
        $detalle->save();

        // Tipo de Monedas

        $detalle = new Detalle();
        $detalle->descripcion = "SOLES";
        $detalle->simbolo = 'S/.';
        $detalle->estado = 'ACTIVO';
        $detalle->tabla_id = 1;
        $detalle->save();

        $detalle = new Detalle();
        $detalle->descripcion = "DOLARES";
        $detalle->simbolo = '$';
        $detalle->estado = 'ACTIVO';
        $detalle->tabla_id = 1;
        $detalle->save();

        $detalle = new Detalle();
        $detalle->descripcion = "EUROS";
        $detalle->simbolo = '€';
        $detalle->estado = 'ACTIVO';
        $detalle->tabla_id = 1;
        $detalle->save();


        $this->call(DepartamentoSeeder::class);
        $this->call(ProvinciaSeeder::class);
        $this->call(DistritoSeeder::class);

    }
}
