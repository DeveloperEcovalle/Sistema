<?php

use Illuminate\Database\Seeder;
use App\Almacenes\Producto;
use App\Almacenes\Familia;
use App\Almacenes\SubFamilia;
use App\Almacenes\LoteProducto;
use Carbon\Carbon;

class ProductoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $familia = new Familia();
        $familia->familia = 'FAMILIA 01';
        $familia->save();

        $subfamilia = new SubFamilia();
        $subfamilia->descripcion =  'SUB FAMILIA 01';
        $subfamilia->familia_id = 1;
        $subfamilia->save();

        $producto = new Producto();
        $producto->codigo = '20482089594';
        $producto->nombre = 'CORCUMA';
        $producto->descripcion = 'CORCUMA1';
        $producto->familia_id = 1;
        $producto->sub_familia_id = 1;
        $producto->medida= 54;
        $producto->stock = 0;
        $producto->stock_minimo = 0;
        $producto->precio_venta_minimo = 0;
        $producto->precio_venta_maximo = 0;
        $producto->precio_venta_maximo = 0;
        $producto->linea_comercial = 153;
        $producto->peso_producto = 20.56;
        $producto->igv = 1;
        $producto->save();

        $producto = new Producto();
        $producto->codigo = '20482089598';
        $producto->nombre = 'CORCUMA2';
        $producto->descripcion = 'CORCUMA2';
        $producto->familia_id = 1;
        $producto->sub_familia_id = 1;
        $producto->medida= 54;
        $producto->stock = 0;
        $producto->stock_minimo = 0;
        $producto->precio_venta_minimo = 0;
        $producto->precio_venta_maximo = 0;
        $producto->precio_venta_maximo = 0;
        $producto->linea_comercial = 153;
        $producto->peso_producto = 289.56;
        $producto->igv = 1;
        $producto->save();


        $producto = new Producto();
        $producto->codigo = '20482089591';
        $producto->nombre = 'CORCUMA3';
        $producto->descripcion = 'CORCUMA3';
        $producto->familia_id = 1;
        $producto->sub_familia_id = 1;
        $producto->medida= 54;
        $producto->stock = 0;
        $producto->stock_minimo = 0;
        $producto->precio_venta_minimo = 0;
        $producto->precio_venta_maximo = 0;
        $producto->precio_venta_maximo = 0;
        $producto->linea_comercial = 153;
        $producto->peso_producto = 289.56;
        $producto->igv = 1;
        $producto->save();

        $producto = new Producto();
        $producto->codigo = '20482089590';
        $producto->nombre = 'CORCUMA4';
        $producto->descripcion = 'CORCUMA4';
        $producto->familia_id = 1;
        $producto->sub_familia_id = 1;
        $producto->medida= 54;
        $producto->stock = 0;
        $producto->stock_minimo = 0;
        $producto->precio_venta_minimo = 0;
        $producto->precio_venta_maximo = 0;
        $producto->precio_venta_maximo = 0;
        $producto->linea_comercial = 153;
        $producto->peso_producto = 289.56;
        $producto->igv = 1;
        $producto->save();

        ///////////////////////////////////////////////////

        $lote = new LoteProducto();
        $lote->codigo =  '0000000012';
        $lote->producto_id = 1;
        $lote->cantidad = 6;
        $lote->fecha_vencimiento =  Carbon::createFromFormat('d/m/Y', '01/02/2020')->format('Y-m-d');
        $lote->save();

        // $lote = new LoteProducto();
        // $lote->codigo =  '0000000013';
        // $lote->producto_id = 1;
        // $lote->cantidad = 16;
        // $lote->fecha_vencimiento =  Carbon::createFromFormat('d/m/Y', '01/01/2020')->format('Y-m-d');
        // $lote->save();

        // $lote = new LoteProducto();
        // $lote->codigo =  '0000000014';
        // $lote->producto_id = 1;
        // $lote->cantidad = 26;
        // $lote->fecha_vencimiento =  Carbon::createFromFormat('d/m/Y', '11/02/2020')->format('Y-m-d');
        // $lote->save();

        // $lote = new LoteProducto();
        // $lote->codigo =  '0000000015';
        // $lote->producto_id = 1;
        // $lote->cantidad = 12;
        // $lote->fecha_vencimiento =  Carbon::createFromFormat('d/m/Y', '21/03/2020')->format('Y-m-d');
        // $lote->save();

        ////////////////////////////////////////
        $lote = new LoteProducto();
        $lote->codigo =  '0000000112';
        $lote->producto_id = 2;
        $lote->cantidad = 78;
        $lote->fecha_vencimiento =  Carbon::createFromFormat('d/m/Y', '08/02/2020')->format('Y-m-d');
        $lote->save();

        // $lote = new LoteProducto();
        // $lote->codigo =  '0000000113';
        // $lote->producto_id = 2;
        // $lote->cantidad = 76;
        // $lote->fecha_vencimiento =  Carbon::createFromFormat('d/m/Y', '08/01/2019')->format('Y-m-d');
        // $lote->save();

        // $lote = new LoteProducto();
        // $lote->codigo =  '0000000114';
        // $lote->producto_id = 2;
        // $lote->cantidad = 36;
        // $lote->fecha_vencimiento =  Carbon::createFromFormat('d/m/Y', '18/02/2019')->format('Y-m-d');
        // $lote->save();

        // $lote = new LoteProducto();
        // $lote->codigo =  '0000000115';
        // $lote->producto_id = 2;
        // $lote->cantidad = 22;
        // $lote->fecha_vencimiento =  Carbon::createFromFormat('d/m/Y', '28/03/2019')->format('Y-m-d');
        // $lote->save();
        /////////////////////////////////////////
        $lote = new LoteProducto();
        $lote->codigo =  '0000001112';
        $lote->producto_id = 3;
        $lote->cantidad = 78;
        $lote->fecha_vencimiento =  Carbon::createFromFormat('d/m/Y', '08/02/2020')->format('Y-m-d');
        $lote->save();

        // $lote = new LoteProducto();
        // $lote->codigo =  '0000001113';
        // $lote->producto_id = 3;
        // $lote->cantidad = 76;
        // $lote->fecha_vencimiento =  Carbon::createFromFormat('d/m/Y', '08/01/2019')->format('Y-m-d');
        // $lote->save();

        // $lote = new LoteProducto();
        // $lote->codigo =  '0000001114';
        // $lote->producto_id = 3;
        // $lote->cantidad = 36;
        // $lote->fecha_vencimiento =  Carbon::createFromFormat('d/m/Y', '18/02/2019')->format('Y-m-d');
        // $lote->save();

        // $lote = new LoteProducto();
        // $lote->codigo =  '0000001115';
        // $lote->producto_id = 3;
        // $lote->cantidad = 22;
        // $lote->fecha_vencimiento =  Carbon::createFromFormat('d/m/Y', '28/03/2019')->format('Y-m-d');
        // $lote->save();
        /////////////////////////////////////////
        $lote = new LoteProducto();
        $lote->codigo =  '0000001112';
        $lote->producto_id = 4;
        $lote->cantidad = 78;
        $lote->fecha_vencimiento =  Carbon::createFromFormat('d/m/Y', '08/02/2020')->format('Y-m-d');
        $lote->save();

        // $lote = new LoteProducto();
        // $lote->codigo =  '0000001113';
        // $lote->producto_id = 4;
        // $lote->cantidad = 76;
        // $lote->fecha_vencimiento =  Carbon::createFromFormat('d/m/Y', '08/01/2019')->format('Y-m-d');
        // $lote->save();

        // $lote = new LoteProducto();
        // $lote->codigo =  '0000001114';
        // $lote->producto_id = 4;
        // $lote->cantidad = 36;
        // $lote->fecha_vencimiento =  Carbon::createFromFormat('d/m/Y', '18/02/2019')->format('Y-m-d');
        // $lote->save();

        // $lote = new LoteProducto();
        // $lote->codigo =  '0000001115';
        // $lote->producto_id = 4;
        // $lote->cantidad = 22;
        // $lote->fecha_vencimiento =  Carbon::createFromFormat('d/m/Y', '28/03/2019')->format('Y-m-d');
        // $lote->save();







    }
}
