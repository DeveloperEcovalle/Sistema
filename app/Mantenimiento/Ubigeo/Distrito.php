<?php

namespace App\Mantenimiento\Ubigeo;

use Illuminate\Database\Eloquent\Model;

class Distrito extends Model
{
    protected $table = 'distritos';
    public $timestamps = false;

    public function provincias()
    {
        return $this->belongsTo('App\Mantenimiento\Ubigeo\Provincia');
    }

    public function departamentos()
    {
        return $this->belongsTo('App\Mantenimiento\Ubigeo\Departamento');
    }
}
