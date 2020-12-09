<?php

namespace App\Mantenimiento\Tabla;

use Illuminate\Database\Eloquent\Model;

class General extends Model
{
    protected $table = 'tablas';
    protected $fillable = ['descripcion','sigla'];
    public $timestamps = true;
    
}
