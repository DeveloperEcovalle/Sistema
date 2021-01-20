<?php

namespace App\Pos;

use Illuminate\Database\Eloquent\Model;

class Caja extends Model
{
    protected $table = 'pos_caja_chica';
    protected $fillable = ['cierre',
    'estado',
    'num_referencia',
    'saldo_inicial',
    'empleado_id',
    'moneda',
    ];
    public $timestamps = true;

    public function empleado()
    {
        return $this->belongsTo('App\Mantenimiento\Empleado\Empleado');
    }
}