<?php

namespace App\Compras;

use Illuminate\Database\Eloquent\Model;

class Articulo extends Model
{
    protected $table = 'articulos';
    protected $fillable =['categoria_id', 'almacen_id','presentacion','descripcion','stock','stock_min','precio_compra','estado'];
    public $timestamps = true;
    
    public function categoria()
    {
        return $this->belongsTo('App\Compras\Categoria', 'categoria_id');
    }

    public function almacen()
    {
        return $this->belongsTo('App\Almacenes\Almacen', 'almacen_id');
    }
}
