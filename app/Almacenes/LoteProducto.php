<?php

namespace App\Almacenes;

use App\Almacenes\Producto;
use Illuminate\Database\Eloquent\Model;

class LoteProducto extends Model
{
    protected $table = 'lote_productos';
    protected $fillable = [
        'codigo',
        'producto_id',
        'cantidad',
        'fecha_vencimiento',
        'estado'
    ];
    public $timestamps = true;

    public function producto()
    {
        return $this->belongsTo('App\Almacenes\Producto','producto_id');
    }

    //EVENTO AL CREAR Y AL MODIFICAR

    protected static function booted()
    {
        static::saved(function(LoteProducto $loteProducto){
            //RECORRER LOTE - PRODUCTO Y LA SUMA
            $cantidadProductos = LoteProducto::where('estado','1')->where('producto_id',$loteProducto->producto_id)->sum('cantidad');
            //ACTUALIZAR EL STOCK DEL PRODUCTO
            $producto = Producto::findOrFail($loteProducto->producto_id);
            $producto->stock = $cantidadProductos;
            $producto->update();
        });
    }
}
