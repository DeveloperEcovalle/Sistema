<?php

namespace App\Almacenes;

use Illuminate\Database\Eloquent\Model;

class NotaIngreso extends Model
{
    protected $table = 'nota_ingreso';
    protected $fillable = [
        'id',
        'numero',
        'fecha',
        'origen',
        'destino',
        'usuario',
        'estado'
    ];
    public $timestamps = true;
}
