<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Linea_produccion extends Model
{
    protected $table='linea_produccion';
    protected $fillable =['id','nombre_linea','cantidad_personal','ruta_imagen','nombre_imagen','ruta_archivo_word','archivo_word','created_at','updated_at'];
    public $timestamps=true;
}
