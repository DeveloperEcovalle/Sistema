<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Provincia extends Model
{
    protected $table = 'provincias';

    public function departamentos()
    {
        return $this->belongsTo('App\Departamento');
    }

    public function distritos()
    {
        return $this->hasMany('App\Distrito');
    }
}
