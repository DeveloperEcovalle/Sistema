<?php

namespace App\Compras;

use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    protected $table = 'proveedores';
    public $timestamps = true;
    protected $fillable =[
        'ruc',
        'dni', 
        'descripcion',
        'tipo_documento',
        'tipo_persona',
        'direccion',
        'correo',
        'telefono',
        'web',
        'zona',
        'contacto',
        'celular_contacto',
        'telefono_contacto',
        'transporte',
        'direccion_transporte',
        'direccion_almacen',
        'calidad',
        'celular_calidad',
        'telefono_calidad',
        'correo_calidad',






    ];
}
