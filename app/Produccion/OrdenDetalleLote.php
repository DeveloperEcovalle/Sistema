<?php

namespace App\Produccion;

use Illuminate\Database\Eloquent\Model;

class OrdenDetalleLote extends Model
{
    protected $table='orden_produccion_lotes'; 
    protected $fillable = [
          'orden_produccion_detalle_id',
          'lote_articulo_id',
          'cantidad',
          'tipo_cantidad'
        ];
    public $timestamps=true;

    public function loteArticulo()
	{
	    return $this->belongsTo('App\Compras\LoteArticulo', 'lote_articulo_id');
	}

}