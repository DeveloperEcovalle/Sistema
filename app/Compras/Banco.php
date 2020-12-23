<?php

namespace App\Compras;

use Illuminate\Database\Eloquent\Model;

class Banco extends Model
{
    protected $table = 'bancos';
    public $timestamps = true;
    protected $fillable = [
            'proveedor_id',
            'descripcion',
            'tipo_moneda',
            'num_cuenta',
            'cci'
        ];

    public function proveedor()
    {
        return $this->belongsTo('App\Compras\Proveedor');
    }
}
