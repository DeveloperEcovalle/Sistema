<?php

namespace App\InvDesarrollo;

use Illuminate\Database\Eloquent\Model;

class Guia extends Model
{
    protected $table='guias';
    protected $fillable =['producto_id','unidades_a_producir','area_responsable1','area_responsable2','fecha','observacion','usuario_id','estado'];
    public $timestamps=true;

    public function producto()
	{
	    return $this->belongsTo('App\Produccion\Producto');
	}
}
