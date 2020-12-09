<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Departamento extends Model
{
    protected $table = 'departamentos';

    public function provincias()
    {
        return $this->hasMany('App\Provincia');
    }

    public function distritos()
    {
        return $this->hasMany('App\Distrito');
    }
}
