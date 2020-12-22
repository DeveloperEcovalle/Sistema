<?php

use App\Mantenimiento\Ubigeo\Departamento;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Almacenes\Almacen;
use App\Compras\Categoria;
use App\Compras\Articulo;

class DepartamentoSeeder extends Seeder
{
    public function run()
    {


        // factory(Almacen::class)->times(10000)->create();
        // factory(Categoria::class)->times(10000)->create();
        // factory(Articulo::class)->times(1000)->create();



        DB::table('departamentos')->delete();
        $file = database_path("data/ubigeo/departamentos.json");
        $json = file_get_contents($file);
        $data = json_decode($json);
        foreach ($data as $obj) {
            Departamento::create(array(
                'id' => $obj->id,
                'nombre' => $obj->nombre
            ));
        }
    }
}
