<?php

namespace App\Produccion;

use Illuminate\Database\Eloquent\Model;

class Programacion_produccion extends Model
{
    protected $table='programacion_produccion';
    protected $fillable =[
                'id',
                'producto_id',
                // 'fecha_creacion',
                'fecha_produccion',
                'fecha_termino',
                'cantidad_programada',
                // 'cantidad_producida',
                'observacion',
                // 'usuario_id',
                'estado',
                // 'created_at',
                // 'updated_at'
            ];
    public $timestamps=true;

    public function producto()
	{
	    return $this->belongsTo('App\Almacenes\Producto','producto_id');
	}
}
