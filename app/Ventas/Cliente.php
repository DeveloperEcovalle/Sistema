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
