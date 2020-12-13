<?php

use App\Mantenimiento\Tabla\General;
use Illuminate\Database\Seeder;

class TablaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         // 1
         $tabla = new General();
         $tabla->descripcion = 'TIPOS DE MONEDA';
         $tabla->sigla = 'TIPOS DE MONEDA';
         $tabla->save();
 
         // 2
         $tabla = new General();
         $tabla->descripcion = 'BANCOS';
         $tabla->sigla = 'BANCOS';
         $tabla->save();
 
         // 3
         $tabla = new General();
         $tabla->descripcion = 'TIPOS DE DOCUMENTO';
         $tabla->sigla = 'TIPOS DE DOCUMENTO';
         $tabla->save();
 
         // 4
         $tabla = new General();
         $tabla->descripcion = 'TIPOS DE SEXO';
         $tabla->sigla = 'SEXO';
         $tabla->save();
 
         // 5
         $tabla = new General();
         $tabla->descripcion = 'TIPOS DE ESTADO CIVIL';
         $tabla->sigla = 'ESTADO CIVIL';
         $tabla->save();
 
         // 6
         $tabla = new General();
         $tabla->descripcion = 'ZONAS';
         $tabla->sigla = 'ZONAS';
         $tabla->save();
 
         // 7
         $tabla = new General();
         $tabla->descripcion = 'AREAS';
         $tabla->sigla = 'AREAS';
         $tabla->save();
 
         // 8
         $tabla = new General();
         $tabla->descripcion = 'CARGOS';
         $tabla->sigla = 'CARGOS';
         $tabla->save();
 
         // 9
         $tabla = new General();
         $tabla->descripcion = 'PROFESIONES';
         $tabla->sigla = 'PROFESIONES';
         $tabla->save();

        // 10
        $tabla = new General();
        $tabla->descripcion = 'PRESENTACIONES';
        $tabla->sigla = 'PRESENTACIONES';
        $tabla->save();

        // 10
        $tabla = new General();
        $tabla->descripcion = 'TIPOS DE PERSONAS';
        $tabla->sigla = 'PERSONAS';
        $tabla->save();
    }
}
