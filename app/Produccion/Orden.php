<?php

namespace App\Produccion;

use Illuminate\Database\Eloquent\Model;

class Orden extends Model
{
    protected $table='orden_produccion';
    protected $fillable = [
          'programacion_id',
          
          'producto_id',
          'codigo_producto',
          'descripcion_producto',
          'fecha_produccion',
          'cantidad',

          'fecha_orden',
          'observacion', //POR BORRAR O INGRESARLO
          'conformidad', //[0=>PENDIENTE , 1 => APROBADO , 2 => NULO]
          'editable',// [0=>NUEVO , 1 => EDITAR , 2 => NO NECESITA (COMPLETADO)]
          'estado',
        ];
        
    public $timestamps=true;

    public function programacion()
	{
	    return $this->belongsTo('App\Produccion\Programacion_produccion','programacion_id');
	}
}
