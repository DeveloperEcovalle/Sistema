<?php

namespace App\Mantenimiento;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    protected $table = 'empresas';
    public $timestamps = true;
    protected $fillable =[
        'ruc', 
        'razon_social',
        'razon_social_abreviada',
        'ruta_logo',
        'nombre_logo',
        'direccion_fiscal',
        'telefono',
        'celular',
        'direccion_planta',

        'dni_representante',
        'nombre_representante',

        'num_asiento',
        'num_partida',
        'estado'
    ];
}
