<?php

namespace App\Mantenimiento\Empleado;

use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    protected $table = 'empleados';

    public function persona()
    {
        return $this->belongsTo('App\Mantenimiento\Persona\Persona');
    }

    public function vendedor()
    {
        return $this->hasOne('App\Mantenimiento\Vendedor\Vendedor');
    }
}
