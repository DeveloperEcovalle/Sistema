<?php

namespace App\Produccion;

use Illuminate\Database\Eloquent\Model;

class Familia extends Model
{
    //
    protected $table = 'familias';
    protected $fillable = ['familia','estado'];
    public $timestamps = true;
}
