<?php

namespace App\Produccion;

use Illuminate\Database\Eloquent\Model;

class Orden extends Model
{
    protected $table='orden_produccion';
    protected $fillable = [
          'programacion_id',
          'fecha_orden',
          'observacion',
          'estado',
        ];
        
    public $timestamps=true;

    public function programacion()
	{
	    return $this->belongsTo('App\Produccion\Programacion_produccion','programacion_id');
	}
}
