<?php

namespace App\Almacenes;

use Illuminate\Database\Eloquent\Model;

class NotaSalidad extends Model
{
    protected $table = 'nota_salidad';
    protected $fillable = [
        'numero',
        'fecha',
        'origen',
        'destino',
        'usuario',
        'estado'
    ];
    public $timestamps = true;
}
