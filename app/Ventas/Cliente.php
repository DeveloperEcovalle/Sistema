<?php

namespace App\Ventas;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = 'clientes';
    protected $fillable = [
        'tipo_documento',
        'documento',
        'nombre',
        'departamento_id',
        'provincia_id',
        'distrito_id',
        'direccion',
        'correo_electronico',
        'telefono_movil',
        'telefono_fijo',
        'moneda_credito',
        'limite_credito',
        'nombre_contacto',
        'telefono_contacto',

        'direccion_negocio',
        'fecha_aniversario',
        'observaciones',
        'nombre1',
        'fecha_nacimiento1',
        'correo_electronico1',
        'celular1',
        'nombre2',
        'fecha_nacimiento2',
        'correo_electronico2',
        'celular2',
        'nombre3',
        'fecha_nacimiento3',
        'correo_electronico3',
        'celular3',
        'condicion_reparto',
        'direccion_entrega',
        'empresa_envio',
        'pago_flete_envio',
        'persona_recoge',
        'dni_persona_recoge',
        'telefono_dato_envio',
        'dato_envio_observacion',
        'activo',
        'estado'
    ];

    public function cotizaciones()
    {
        return $this->hasMany('App\Ventas\Cotizacion');
    }

    public function getDocumento(): string
    {
        return $this->tipo_documento . ': ' . $this->documento;
    }

    public function getDepartamento(): string
    {
        return departamentos()->where('id', $this->departamento_id)->first()->nombre;
    }

    public function getProvincia(): string
    {
        return provincias($this->provincia_id)->first()->nombre;
    }

    public function getDistrito(): string
    {
        return distritos($this->distrito_id)->first()->nombre;
    }
}
