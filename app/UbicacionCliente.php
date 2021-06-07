<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UbicacionCliente extends Model
{
    protected $table = 'ubicacion_cliente';
    public $timestamps = true;
    protected $fillable = [
            'ver',
            'cliente_id'
        ];
}
