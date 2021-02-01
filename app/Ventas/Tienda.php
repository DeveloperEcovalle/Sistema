<?php

namespace App\Ventas;

use Illuminate\Database\Eloquent\Model;

class Tienda extends Model
{
    protected $table = 'cliente_tiendas';
    protected $fillable = [
        'cliente_id',
        'nombre',
        'tipo_tienda',
        'tipo_negocio',
        'direccion',

        'facebook',
        'web',
        'instagram',
        
        'correo',
        'telefono',
        'celular',

        'hora_inicio',
        'hora_fin',

        'contacto_admin_nombre',
        'contacto_admin_cargo',
        'contacto_admin_fecha_nacimiento',
        'contacto_admin_correo',
        'contacto_admin_celular',
        'contacto_admin_telefono',

        'contacto_credito_nombre',
        'contacto_credito_cargo',
        'contacto_credito_fecha_nacimiento',
        'contacto_credito_correo',
        'contacto_credito_celular',
        'contacto_credito_telefono',

        'contacto_vendedor_nombre',
        'contacto_vendedor_cargo',
        'contacto_vendedor_fecha_nacimiento',
        'contacto_vendedor_correo',
        'contacto_vendedor_celular',
        'contacto_vendedor_telefono',
        'estado',

        'condicion_reparto',

        'nombre_transporte_oficina',
        'direccion_transporte_oficina',
        'responsable_pago_flete',
        'responsable_pago',

        'dni_responsable_recoger',
        'estado_responsable_recoger',
        'nombre_responsable_recoger',
        'telefono_responsable_recoger',
        'observacion_envio',

        'nombre_transporte_domicilio',
        'direccion_domicilio',
        'nombre_contacto_recoger',
        'telefono_contacto_recoger',
        'observacion_domicilio'

















    ];

    public function cliente()
    {
        return $this->belongsTo('App\Ventas\Cliente');
    }

    public function producto()
    {
        return $this->belongsTo('App\Almacenes\Producto');
    }

}
