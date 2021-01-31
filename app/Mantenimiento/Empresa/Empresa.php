<?php

namespace App\Mantenimiento\Empresa;

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
        'correo',
        'direccion_planta',

        'dni_representante',
        'nombre_representante',

        'num_asiento',
        'num_partida',
        'estado_ruc',
        'estado_dni_representante',
        'estado',
    ];

    public function cotizaciones()
    {
        return $this->hasMany('App\Ventas\Cotizacion');
    }

    public function talonarios()
    {
        return $this->hasMany('App\Mantenimiento\Talonario');
    }

    public function bancos()
    {
        return $this->hasMany('App\Mantenimiento\Empresa\Banco');
    }
}
