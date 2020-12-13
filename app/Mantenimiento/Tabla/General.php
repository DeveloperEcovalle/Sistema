<?php

namespace App\Mantenimiento\Tabla;

use Illuminate\Database\Eloquent\Model;

class General extends Model
{
    protected $table = 'tablas';
    protected $fillable = ['descripcion','sigla'];
    public $timestamps = true;

    public function detalles()
    {
        return $this->hasMany('App\Mantenimiento\Tabla\Detalle', 'tabla_id');
    }
    
}
