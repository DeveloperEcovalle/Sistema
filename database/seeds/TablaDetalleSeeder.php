<?php
use App\Mantenimiento\Tabla\Detalle;
use Illuminate\Database\Seeder;

class TablaDetalleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Bancos

        $detalle = new Detalle();
        $detalle->descripcion = "BANCO DE LA NACION";
        $detalle->simbolo = "BN";
        $detalle->estado = 'ACTIVO';
        $detalle->tabla_id = 2;
        $detalle->save();

        $detalle = new Detalle();
        $detalle->descripcion = "INTERCONTINENTAL";
        $detalle->simbolo = "INTERCONTINENTAL";
        $detalle->estado = 'ACTIVO';
        $detalle->tabla_id = 2;
        $detalle->save();

        $detalle = new Detalle();
        $detalle->descripcion = "MI BANCO";
        $detalle->simbolo = "MI BANCO";
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

        // TIPO DE DOCUMENTO

        $detalle = new Detalle();
        $detalle->descripcion = "DOCUMENTO NACIONAL DE IDENTIDAD";
        $detalle->simbolo = 'DNI';
        $detalle->estado = 'ACTIVO';
        $detalle->tabla_id = 3;
        $detalle->save();

        $detalle = new Detalle();
        $detalle->descripcion = "CARNET DE EXTRANJERIA";
        $detalle->simbolo = 'CARNET EXT.';
        $detalle->estado = 'ACTIVO';
        $detalle->tabla_id = 3;
        $detalle->save();

        $detalle = new Detalle();
        $detalle->descripcion = "REGISTRO UNICO DE CONTRIBUYENTES";
        $detalle->simbolo = 'RUC';
        $detalle->estado = 'ACTIVO';
        $detalle->tabla_id = 3;
        $detalle->save();

        $detalle = new Detalle();
        $detalle->descripcion = "PASAPORTE";
        $detalle->simbolo = 'PASAPORTE';
        $detalle->estado = 'ACTIVO';
        $detalle->tabla_id = 3;
        $detalle->save();

        $detalle = new Detalle();
        $detalle->descripcion = "PARTIDA DE NACIMIENTO";
        $detalle->simbolo = 'P. NAC.';
        $detalle->estado = 'ACTIVO';
        $detalle->tabla_id = 3;
        $detalle->save();

        // SEXO
        $detalle = new Detalle();
        $detalle->descripcion = "HOMBRE";
        $detalle->simbolo = 'H';
        $detalle->estado = 'ACTIVO';
        $detalle->tabla_id = 4;
        $detalle->save();

        $detalle = new Detalle();
        $detalle->descripcion = "MUJER";
        $detalle->simbolo = 'M';
        $detalle->estado = 'ACTIVO';
        $detalle->tabla_id = 4;
        $detalle->save();

        // ESTADO CIVIL
        $detalle = new Detalle();
        $detalle->descripcion = "SOLTERO";
        $detalle->simbolo = 'S';
        $detalle->estado = 'ACTIVO';
        $detalle->tabla_id = 5;
        $detalle->save();

        $detalle = new Detalle();
        $detalle->descripcion = "CASADO";
        $detalle->simbolo = 'C';
        $detalle->estado = 'ACTIVO';
        $detalle->tabla_id = 5;
        $detalle->save();

        $detalle = new Detalle();
        $detalle->descripcion = "DIVORCIADO";
        $detalle->simbolo = 'D';
        $detalle->estado = 'ACTIVO';
        $detalle->tabla_id = 5;
        $detalle->save();

        $detalle = new Detalle();
        $detalle->descripcion = "VIUDO";
        $detalle->simbolo = 'V';
        $detalle->estado = 'ACTIVO';
        $detalle->tabla_id = 5;
        $detalle->save();

        // ZONAS
        $detalle = new Detalle();
        $detalle->descripcion = "NORTE";
        $detalle->simbolo = 'NORTE';
        $detalle->estado = 'ACTIVO';
        $detalle->tabla_id = 6;
        $detalle->save();

        $detalle = new Detalle();
        $detalle->descripcion = "NOROESTE";
        $detalle->simbolo = 'NOROESTE';
        $detalle->estado = 'ACTIVO';
        $detalle->tabla_id = 6;
        $detalle->save();

        $detalle = new Detalle();
        $detalle->descripcion = "CENTRO";
        $detalle->simbolo = 'CENTRO';
        $detalle->estado = 'ACTIVO';
        $detalle->tabla_id = 6;
        $detalle->save();

        $detalle = new Detalle();
        $detalle->descripcion = "SUR";
        $detalle->simbolo = 'SUR';
        $detalle->estado = 'ACTIVO';
        $detalle->tabla_id = 6;
        $detalle->save();

        $detalle = new Detalle();
        $detalle->descripcion = "SURESTE";
        $detalle->simbolo = 'SURESTE';
        $detalle->estado = 'ACTIVO';
        $detalle->tabla_id = 6;
        $detalle->save();

        // AREAS
        $detalle = new Detalle();
        $detalle->descripcion = "GERENCIA GENERAL";
        $detalle->simbolo = 'GERENCIA GENERAL';
        $detalle->estado = 'ACTIVO';
        $detalle->tabla_id = 7;
        $detalle->save();

        $detalle = new Detalle();
        $detalle->descripcion = "CONTABILIDAD";
        $detalle->simbolo = 'CONTABILIDAD';
        $detalle->estado = 'ACTIVO';
        $detalle->tabla_id = 7;
        $detalle->save();

        $detalle = new Detalle();
        $detalle->descripcion = "ALMACÉN";
        $detalle->simbolo = 'ALMACÉN';
        $detalle->estado = 'ACTIVO';
        $detalle->tabla_id = 7;
        $detalle->save();

        $detalle = new Detalle();
        $detalle->descripcion = "FÁBRICA";
        $detalle->simbolo = 'FÁBRICA';
        $detalle->estado = 'ACTIVO';
        $detalle->tabla_id = 7;
        $detalle->save();

        $detalle = new Detalle();
        $detalle->descripcion = "COMERCIAL";
        $detalle->simbolo = 'COMERCIAL';
        $detalle->estado = 'ACTIVO';
        $detalle->tabla_id = 7;
        $detalle->save();

        //CARGOS
        $detalle = new Detalle();
        $detalle->descripcion = "GERENTE GENERAL";
        $detalle->simbolo = 'GERENTE GENERAL';
        $detalle->estado = 'ACTIVO';
        $detalle->tabla_id = 8;
        $detalle->save();

        $detalle = new Detalle();
        $detalle->descripcion = "ASISTENTE DE CONTABILIDAD";
        $detalle->simbolo = 'ASISTENTE DE CONTABILIDAD';
        $detalle->estado = 'ACTIVO';
        $detalle->tabla_id = 8;
        $detalle->save();

        $detalle = new Detalle();
        $detalle->descripcion = "ASISTENTE DE ALMACÉN";
        $detalle->simbolo = 'ASISTENTE DE ALMACÉN';
        $detalle->estado = 'ACTIVO';
        $detalle->tabla_id = 8;
        $detalle->save();

        $detalle = new Detalle();
        $detalle->descripcion = "OPERARIO DE FÁBRICA";
        $detalle->simbolo = 'OPERARIO DE FÁBRICA';
        $detalle->estado = 'ACTIVO';
        $detalle->tabla_id = 8;
        $detalle->save();

        //PROFESIONES
        $detalle = new Detalle();
        $detalle->descripcion = "INGENIERO(A) INDUSTRIAL";
        $detalle->simbolo = 'ING. INDUSTRIAL';
        $detalle->estado = 'ACTIVO';
        $detalle->tabla_id = 9;
        $detalle->save();

        $detalle = new Detalle();
        $detalle->descripcion = "INGENIERO(A) DE SISTEMAS";
        $detalle->simbolo = 'ING. SISTEMAS';
        $detalle->estado = 'ACTIVO';
        $detalle->tabla_id = 9;
        $detalle->save();

        $detalle = new Detalle();
        $detalle->descripcion = "INGENIERO(A) AGROINDUSTRIAL";
        $detalle->simbolo = 'ING. AGROINDUSTRIAL';
        $detalle->estado = 'ACTIVO';
        $detalle->tabla_id = 9;
        $detalle->save();

        $detalle = new Detalle();
        $detalle->descripcion = "CONTADOR PÚBLICO";
        $detalle->simbolo = 'CONTADOR PÚBLICO';
        $detalle->estado = 'ACTIVO';
        $detalle->tabla_id = 9;
        $detalle->save();

        $detalle = new Detalle();
        $detalle->descripcion = "ADMINISTRADOR";
        $detalle->simbolo = 'ADMINISTRADOR';
        $detalle->estado = 'ACTIVO';
        $detalle->tabla_id = 9;
        $detalle->save();

        $detalle = new Detalle();
        $detalle->descripcion = "TÉCNICO DE MAQUINARIA";
        $detalle->simbolo = 'TÉCNICO DE MAQUINARIA';
        $detalle->estado = 'ACTIVO';
        $detalle->tabla_id = 9;
        $detalle->save();

        //PRESENTACIONES
        $detalle = new Detalle();
        $detalle->descripcion = "KILOGRAMO";
        $detalle->simbolo = 'KG';
        $detalle->estado = 'ACTIVO';
        $detalle->tabla_id = 10;
        $detalle->save();

        $detalle = new Detalle();
        $detalle->descripcion = "TONELADA";
        $detalle->simbolo = 'TMB';
        $detalle->estado = 'ACTIVO';
        $detalle->tabla_id = 10;
        $detalle->save();

        $detalle = new Detalle();
        $detalle->descripcion = "LITRO";
        $detalle->simbolo = 'L';
        $detalle->estado = 'ACTIVO';
        $detalle->tabla_id = 10;
        $detalle->save();

        //TIPOS DE PERSONAS
        $detalle = new Detalle();
        $detalle->descripcion = "PERSONA NATURAL";
        $detalle->simbolo = 'PERSONA NATURAL';
        $detalle->estado = 'ACTIVO';
        $detalle->tabla_id = 11;
        $detalle->save();

        $detalle = new Detalle();
        $detalle->descripcion = "PERSONA JURIDICA";
        $detalle->simbolo = 'PERSONA JURIDICA';
        $detalle->estado = 'ACTIVO';
        $detalle->tabla_id = 11;
        $detalle->save();

        // Grupos sanguíneos
        $detalle = new Detalle();
        $detalle->descripcion = "O NEGATIVO";
        $detalle->simbolo = 'O-';
        $detalle->estado = 'ACTIVO';
        $detalle->tabla_id = 12;
        $detalle->save();

        $detalle = new Detalle();
        $detalle->descripcion = "O POSITIVO";
        $detalle->simbolo = 'O+';
        $detalle->estado = 'ACTIVO';
        $detalle->tabla_id = 12;
        $detalle->save();

        $detalle = new Detalle();
        $detalle->descripcion = "A NEGATIVO";
        $detalle->simbolo = 'A-';
        $detalle->estado = 'ACTIVO';
        $detalle->tabla_id = 12;
        $detalle->save();

        $detalle = new Detalle();
        $detalle->descripcion = "A POSITIVO";
        $detalle->simbolo = 'A+';
        $detalle->estado = 'ACTIVO';
        $detalle->tabla_id = 12;
        $detalle->save();

        $detalle = new Detalle();
        $detalle->descripcion = "B NEGATIVO";
        $detalle->simbolo = 'B-';
        $detalle->estado = 'ACTIVO';
        $detalle->tabla_id = 12;
        $detalle->save();

        $detalle = new Detalle();
        $detalle->descripcion = "B POSITIVO";
        $detalle->simbolo = 'B+';
        $detalle->estado = 'ACTIVO';
        $detalle->tabla_id = 12;
        $detalle->save();



        // UNIDAD DE MEDIDA
        $detalle = new Detalle();
        $detalle->descripcion = "KILOGRAMOS";
        $detalle->simbolo = "KG";
        $detalle->estado = 'ACTIVO';
        $detalle->tabla_id = 13;
        $detalle->save();


        $detalle = new Detalle();
        $detalle->descripcion = "GALON";
        $detalle->simbolo = "GL";
        $detalle->estado = 'ACTIVO';
        $detalle->tabla_id = 13;
        $detalle->save();


        $detalle = new Detalle();
        $detalle->descripcion = "BOTEL";
        $detalle->simbolo = "BOL";
        $detalle->estado = 'ACTIVO';
        $detalle->tabla_id = 13;
        $detalle->save();

        //MODO DE COMPRA
        $detalle = new Detalle();
        $detalle->descripcion = "CONTADO 30 DÍAS";
        $detalle->simbolo = 'CONTADO';
        $detalle->estado = 'ACTIVO';
        $detalle->tabla_id = 14;
        $detalle->save();

        $detalle = new Detalle();
        $detalle->descripcion = "CONTRATO DE CRÉDITO";
        $detalle->simbolo = 'CRÉDITO';
        $detalle->estado = 'ACTIVO';
        $detalle->tabla_id = 14;
        $detalle->save();

        // TIPO DE DOCUMENTO TRIBUTARIOS
        $detalle = new Detalle();
        $detalle->descripcion = "FACTURA";
        $detalle->simbolo = 'FAC';
        $detalle->estado = 'ACTIVO';
        $detalle->tabla_id = 15;
        $detalle->save();

        $detalle = new Detalle();
        $detalle->descripcion = "BOLETA";
        $detalle->simbolo = 'BOL';
        $detalle->estado = 'ACTIVO';
        $detalle->tabla_id = 15;
        $detalle->save();

        $detalle = new Detalle();
        $detalle->descripcion = "RECIBO";
        $detalle->simbolo = 'REC';
        $detalle->estado = 'ACTIVO';
        $detalle->tabla_id = 15;
        $detalle->save();

        $detalle = new Detalle();
        $detalle->descripcion = "NOTA DE CRÉDITO";
        $detalle->simbolo = 'NC';
        $detalle->estado = 'ACTIVO';
        $detalle->tabla_id = 15;
        $detalle->save();

        $detalle = new Detalle();
        $detalle->descripcion = "NOTA DE DÉBITO";
        $detalle->simbolo = 'ND';
        $detalle->estado = 'ACTIVO';
        $detalle->tabla_id = 15;
        $detalle->save();

        $detalle = new Detalle();
        $detalle->descripcion = "GUÍA DE REMISIÓN";
        $detalle->simbolo = 'GR';
        $detalle->estado = 'ACTIVO';
        $detalle->tabla_id = 15;
        $detalle->save();
    }
}
