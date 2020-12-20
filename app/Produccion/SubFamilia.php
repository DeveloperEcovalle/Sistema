<?php

namespace App\Produccion;

use Illuminate\Database\Eloquent\Model;

class SubFamilia extends Model
{
    protected $table = 'subfamilias';
    protected $fillable = [
        'descripcion',
        'familia_id',
        'estado'
    ];

    public function familia()
    {
        return $this->belongsTo('App\Produccion\Familia');
    }

    public function productos()
    {
        return $this->hasMany('App\Produccion\Producto');
    }
}
