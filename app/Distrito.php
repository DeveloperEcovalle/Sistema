<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Distrito extends Model
{
    protected $table = 'distritos';

    public function provincias()
    {
        return $this->belongsTo('App\Provincia');
    }

    public function departamentos()
    {
        return $this->belongsTo('App\Departamento');
    }
}
