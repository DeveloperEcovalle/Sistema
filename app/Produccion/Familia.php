<?php

namespace App\Produccion;

use Illuminate\Database\Eloquent\Model;

class Familia extends Model
{
    //
    protected $table = 'familias';
    protected $fillable = ['familia','estado'];
    public $timestamps = true;

    public function sub_familias()
    {
        return $this->hasMany('App\Produccion\SubFamilia');
    }

    public function productos()
    {
        return $this->hasMany('App\Produccion\Producto');
    }
}
