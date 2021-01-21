<?php

namespace App\InvDesarrollo;

use Illuminate\Database\Eloquent\Model;

class Prototipo extends Model
{
    protected $table='prototipos';
    protected $fillable =['producto_id','fecha_registro','fecha_inicio','fecha_fin','linea_caja_texto_registrar','ruta_imagen','imagen','ruta_archivo_word','archivo_word'];
    public $timestamps=true;
}
