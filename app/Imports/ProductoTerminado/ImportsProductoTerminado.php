<?php

namespace App\Imports\ProductoTerminado;

use App\Almacenes\ProductoDetalle;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\Importable;

class ImportsProductoTerminado implements ToCollection, WithHeadingRow, WithValidation
{
    use Importable;

    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        foreach ($collection as $row) {
            Log::info($row);
            $c_producto = DB::table('productos')->where('nombre', $row['producto'])->first();
            $c_articulo = DB::table('articulos')->where('descripcion', $row['articulo'])->first();
            Log::info($c_producto->id);
            Log::info($c_articulo->id);
            $c_productodetalles = DB::table('producto_detalles')->where([['producto_id', '=', $c_producto->id], ['articulo_id', '=', $c_articulo->id]]);
           Log::info($c_productodetalles->count() );
            if ($c_productodetalles->count() > 0) {
                $productodetalle = ProductoDetalle::findOrFail($c_productodetalles->first()->id);
                $productodetalle->producto_id=$c_producto->id;
                $productodetalle->articulo_id=$c_articulo->id;
                $productodetalle->cantidad=floatval($row['cantidad']);
                $productodetalle->peso=floatval($row['peso']);
                $productodetalle->observacion=$row['observacion'];
                $productodetalle->estado='ACTIVO';
                $productodetalle->save();
            } else {

                $productodetalle = new ProductoDetalle();
                $productodetalle->producto_id=$c_producto->id;
                $productodetalle->articulo_id=$c_articulo->id;
                $productodetalle->cantidad=floatval($row['cantidad']);
                $productodetalle->peso=floatval($row['peso']);
                $productodetalle->observacion=$row['observacion'];
                $productodetalle->estado='ACTIVO';
                $productodetalle->save();



            }
        }
    }
    public function get_data()
    {
        return $this->data;
    }
    public function rules(): array
    {

        return [

             // Can also use callback validation rules

             'producto' => function($attribute, $value, $onFailure) {

                 $valor=DB::table('productos')->where('nombre', $value)->count();

                  if ($valor===0) {

                       $onFailure('No existe este producto');
                  }
              },
             'articulo' => function($attribute, $value, $onFailure) {

                 $valor=DB::table('articulos')->where('descripcion', $value)->count();

                  if ($valor===0) {

                       $onFailure('No existe esta articulo');
                  }
              }
        ];
    }
}
