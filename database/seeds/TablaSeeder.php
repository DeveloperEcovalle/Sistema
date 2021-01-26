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

        // 11
        $tabla = new General();
        $tabla->descripcion = 'TIPOS DE PERSONAS';
        $tabla->sigla = 'PERSONAS';
        $tabla->save();

        // 12
        $tabla = new General();
        $tabla->descripcion = 'GRUPOS SANGUINEOS';
        $tabla->sigla = 'GRUPOS SANGUINEOS';
        $tabla->save();

        // 13
        $tabla = new General();
        $tabla->descripcion = 'UNIDAD MEDIDA';
        $tabla->sigla = 'UNIDAD MEDIDA';
        $tabla->save();
        //14
        $tabla = new General();
        $tabla->descripcion = 'MODO DE COMPRA';
        $tabla->sigla = 'MODO DE COMPRA';
        $tabla->save();

        //15
        $tabla = new General();
        $tabla->descripcion = 'TIPOS DE DOCUMENTOS TRIBUTARIOS';
        $tabla->sigla = 'TIPO DOCUMENTO TRIBUTARIO';
        $tabla->save();

        //16
        $tabla = new General();
        $tabla->descripcion = 'TIPOS DE DOCUMENTOS DE COMPRA';
        $tabla->sigla = 'TIPO DOCUMENTO COMPRA';
        $tabla->save();

        //17
        $tabla = new General();
        $tabla->descripcion = 'TIPO DE CLIENTE';
        $tabla->sigla = 'TIPO DE CLIENTE';
        $tabla->save();

        //18
        $tabla = new General();
        $tabla->descripcion = 'CONDICION DE REPARTO';
        $tabla->sigla = 'CONDICION DE REPARTO';
        $tabla->save();

        //19
        $tabla = new General();
        $tabla->descripcion = 'TIPOS DE PAGO (CAJA CHICA)';
        $tabla->sigla = 'TIPO DE PAGO CAJA CHICA';
        $tabla->save();

        //20
        $tabla = new General();
        $tabla->descripcion = 'HERRAMIENTAS DE PLANTA';
        $tabla->sigla = 'HERRAMIENTAS DE PLANTA';
        $tabla->save();
    }
}
