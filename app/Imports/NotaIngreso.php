<?php

namespace App\Imports;

use App\Almacenes\LoteProducto;
use App\Almacenes\Producto;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\Importable;
use Throwable;

class NotaIngreso implements ToCollection,WithHeadingRow,WithValidation
{
    use Importable;
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function collection(Collection $collection)
    {
        foreach($collection as $row)
        {

            $loteproducto=DB::table('lote_productos')->where('codigo',$row['codigo_lote'])->where('codigo_producto',$row['codigo'])->first();
            if(DB::table('lote_productos')->where('codigo',$row['codigo_lote'])->where('codigo_producto',$row['codigo'])->count()!=0)
            {
                DB::update('update lote_productos set cantidad=?,cantidad_logica=?,observacion=? where id = ?', [$row['cantidad'],$row['cantidad'],"nota por excel",$loteproducto->id]);
            }
            else
            {
                if(DB::table('productos')->where('codigo',$row['codigo'])->count()==0)
                {
                    $familia=DB::table('familias')->where('familia',$row['familia'])->first();
                $subfamilia=DB::table('subfamilias')->where('descripcion',$row['subfamilia'])->first();
                $medida=DB::table('tabladetalles')->where('descripcion',$row['medida'])->first();
                $linea_comercial=DB::table('tabladetalles')->where('descripcion',$row['linea_comercial'])->first();
                    $producto=new Producto();
                    $producto->codigo=$row['codigo'];
                    $producto->nombre=$row['nombre'];
                    $producto->descripcion=$row['descripcion'];
                    $producto->familia_id=$familia->id;
                    $producto->sub_familia_id=$subfamilia->id;
                    $producto->medida=$medida->id;
                    $producto->linea_comercial=$linea_comercial->id;
                    $producto->codigo_barra=$row['codigo_barra'];
                    $producto->stock=$row['stock'];
                    $producto->stock_minimo=$row['stock_minimo'];
                    $producto->precio_venta_minimo=$row['precio_venta_minimo'];
                    $producto->precio_venta_maximo=$row['precio_venta_maximo'];
                    $producto->peso_producto=floatval($row['peso_producto']);
                    $producto->igv=($row['igv']=="Si")? "1": "0";
                    $producto->save();

                    DB::insert('insert into lote_productos(codigo,orden_id,producto_id,
                    codigo_producto,descripcion_producto,cantidad,cantidad_logica,fecha_vencimiento,
                    fecha_entrega,observacion,confor_almacen,confor_produccion,estado) values (?,?,?,?,?,?,?,?,?,?,?,?,?)', [$row['codigo_lote'],0,$producto->id,
                    $producto->codigo,$producto->nombre,$row['cantidad'],$row['cantidad'],$row['fecha_vencimiento'],$row['fecha_entrega'],
                    "nota por excel",1,1,"1"]);
                }
                else
                {
                    $producto=DB::table('productos')->where('codigo',$row['codigo'])->first();
                    DB::insert('insert into lote_productos(codigo,orden_id,producto_id,
                    codigo_producto,descripcion_producto,cantidad,cantidad_logica,fecha_vencimiento,
                    fecha_entrega,observacion,confor_almacen,confor_produccion,estado) values (?,?,?,?,?,?,?,?,?,?,?,?,?)', [$row['codigo_lote'],0,$producto->id,
                    $producto->codigo,$producto->nombre,$row['cantidad'],$row['cantidad'],$row['fecha_vencimiento'],$row['fecha_entrega'],
                    "nota por excel",1,1,"1"]);
                }
            }


        }

    }
    public function rules(): array
    {
        return [

             // Can also use callback validation rules
             'familia' => function($attribute, $value, $onFailure) {

                 $valor=DB::table('familias')->where('familia',$value)->count();

                  if ($valor===0) {

                       $onFailure('No existe esta familia');
                  }
              },
             'subfamilia' => function($attribute, $value, $onFailure) {

                 $valor=DB::table('subfamilias')->where('descripcion',$value)->count();

                  if ($valor===0) {

                       $onFailure('No existe esta subfamilia');
                  }
              },
             'medida' => function($attribute, $value, $onFailure) {

                 $valor=DB::table('tabladetalles')->where('descripcion',$value)->count();

                  if ($valor===0) {

                       $onFailure('No existe esta medida');
                  }
              },
             'linea_comercial' => function($attribute, $value, $onFailure) {

                 $valor=DB::table('tabladetalles')->where('descripcion',$value)->count();

                  if ($valor===0) {

                       $onFailure('No existe esta linea comercial');
                  }
              }
        ];
    }





}
