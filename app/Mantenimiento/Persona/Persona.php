<?php

namespace App\Mantenimiento\Persona;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    protected $table = 'personas';

    public function empleado()
    {
        return $this->hasOne('App\Mantenimiento\Empleado\Empleado');
    }

    public function getDocumento(): string
    {
        return $this->tipo_documento . ': ' . $this->documento;
    }

    public function getApellidosYNombres(): string
    {
        return $this->apellido_paterno . ' ' . $this->apellido_materno . ', ' . $this->nombres;
    }
}
