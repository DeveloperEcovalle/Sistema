<?php

namespace App\Imports\Articulo;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\Importable;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Validation\Rule;
use App\Compras\Articulo;
use Illuminate\Support\Facades\Log;

class ImportsArticulo implements ToCollection,WithHeadingRow,WithValidation
{
    use Importable;
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        foreach ($collection as $row){
            $categoria=DB::table('categorias')->where('descripcion',$row['categoria'])->first();
            $almacen=DB::table('almacenes')->where('descripcion',$row['almacen'])->first();
            $medida=explode('-',$row['unidad_medida']);
            $unidad_medida=DB::table('tabladetalles')->where([['tabla_id','=','13'],
                                        ['descripcion','=',$medida[1]],['simbolo','=',$medida[0]]])->first();

            $consulta=DB::table('articulos')->where([['descripcion','=',$row['descripcion']],
                                                     ['codigo_fabrica','=',$row['codigo_fabrica']],
                                                     ['presentacion','=',$row['presentacion']],
                                                     ['categoria_id','=',$categoria->id],
                                                     ['almacen_id','=',$almacen->id],
                                                     ['unidad_medida','=',$unidad_medida->id],
                                                     ['estado','<>','ANULADO']]);
            if($consulta->count()>0){
                $ID=$consulta->first()->id;
                $articulo = Articulo::findOrFail($ID);
                $articulo->descripcion = $row['descripcion'];
                $articulo->codigo_fabrica = $row['codigo_fabrica'];
                $articulo->stock_min = $row['stock_min'];
                $articulo->precio_compra =floatval( $row['precio_compra']);
                $articulo->presentacion = $row['presentacion'];
                $articulo->categoria_id = $categoria->id;
                $articulo->almacen_id = $almacen->id;
                $articulo->stock = null;
                $articulo->codigo_barra = $row['codigo_barra'];
                $articulo->unidad_medida = $unidad_medida->id;
                $articulo->update();

            }
            else{
                $articulo = new Articulo();
                $articulo->descripcion = $row['descripcion'];
                $articulo->codigo_fabrica = $row['codigo_fabrica'];
                $articulo->stock_min = $row['stock_min'];
                $articulo->precio_compra = $row['precio_compra'];
                $articulo->presentacion = $row['presentacion'];
                $articulo->categoria_id = $categoria->id;
                $articulo->almacen_id = $almacen->id;
                $articulo->stock = null;
                $articulo->codigo_barra = $row['codigo_barra'];
                $articulo->unidad_medida = $unidad_medida->id;
                $articulo->save();
            }
        }

    }
    public function rules(): array
    {
        return [

             // Can also use callback validation rules

             'categoria' => function($attribute, $value, $onFailure) {

                 $valor=DB::table('categorias')->where('descripcion',$value)->count();

                  if ($valor==0) {

                       $onFailure('No existe esta Categoria');
                  }
              },
             'almacen' => function($attribute, $value, $onFailure) {

                 $valor=DB::table('almacenes')->where('descripcion',$value)->count();

                  if ($valor==0) {

                       $onFailure('No existe este Almacen');
                  }
              },
             'unidad_medida' => function($attribute, $value, $onFailure) {

                $medida=explode('-',$value);
                $valor=DB::table('tabladetalles')->where([['tabla_id','=','13'],
                                            ['descripcion','=',$medida[1]],['simbolo','=',$medida[0]]])->count();

                  if ($valor==0) {
                       $onFailure('No existe esta medida');
                  }
              }
        ];
    }
}
