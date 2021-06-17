<?php

namespace App\Imports\NotaIngresoArticulo;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\Importable;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Validation\Rule;
use App\Compras\Articulo;
use Illuminate\Support\Facades\Log;

class ImportsNotaArticulo implements ToCollection,WithHeadingRow,WithValidation
{
    use Importable;
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        foreach ($collection as $row){
            Log::info($row);
            $articulo=DB::table('articulos')->where('descripcion',$row['articulo'])->first();
            $consulta=DB::table('lote_articulos')->where('lote',$row['lote'])->where('articulo_id',$articulo->id);
            Log::info($consulta->count());
            if($consulta->count()!=0)
            {

                DB::update('update lote_articulos set cantidad=?,cantidad_logica=?,descripcion_articulo=? where id = ?', [$row['cantidad'],$row['cantidad'],"nota por excel",$consulta->first()->id]);
            }
            else
            {

                    DB::insert('insert into lote_articulos(detalle_id,lote,articulo_id,
                    codigo_articulo,descripcion_articulo,cantidad,cantidad_logica,fecha_vencimiento,estado) values (?,?,?,?,?,?,?,?,?)', ["0",$row['lote'],$articulo->id,
                    "-","nota por excel",$row['cantidad'],$row['cantidad'],$row['fecha_vencimiento'],"1"]);
            }

        }

    }
    public function rules(): array
    {
        return [
        ];
    }
}
