<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Detalle_linea_produccion extends Model
{
    protected $table='detalle_linea_produccion';
    protected $fillable =['id','maquinaria_equipo_id','created_at','updated_at'];
    public $timestamps=true;
}
