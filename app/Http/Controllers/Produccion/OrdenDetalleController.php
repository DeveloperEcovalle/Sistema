<?php

namespace App\Http\Controllers\Produccion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Compras\Articulo;
use App\Produccion\Programacion_produccion;
use App\Almacenes\ProductoDetalle;
use App\Compras\LoteArticulo;
use DB;
use App\Almacenes\Producto;

use App\Produccion\Orden; //ORDEN DE PRODUCCION
use App\Produccion\OrdenDetalle; // DETALLE DE LA ORDEN DE PRODUCCION
use App\Produccion\OrdenDetalleLote; //DETALLE DEL ARTICULO (LOTES PRODUCCION / EXCEDIDA)

class OrdenDetalleController extends Controller
{
    public function create(Request $request)
    {
        $articulo= Articulo::findOrFail($request->articulo_id);
        $programacion= Programacion_produccion::findOrFail($request->programacion_id);
        $productodetalles = ProductoDetalle::where('producto_id',$programacion->producto_id)
                            ->where('articulo_id',$articulo->id)
                            ->where('estado','ACTIVO')->first();
        $cantidadProduccion = $programacion->cantidad_programada * $productodetalles->cantidad;
        $cantidadProduccionCompleta = $programacion->cantidad_programada * $productodetalles->cantidad.' '.$productodetalles->articulo->getMedida();
        $lotes = LoteArticulo::where('articulo_id',$articulo->id)
                            ->where('cantidad_logica','>',0)
                            ->where('estado','1')->get();
    
    
        return view('produccion.ordenes.detallesLotes.create',[
            'articulo'=> $articulo,
            'programacion' => $programacion,
            'cantidadProduccion' => $cantidadProduccion,
            'cantidadProduccionCompleta' => $cantidadProduccionCompleta,
            'lotes' => $lotes,

        ]);
    }

    public function edit(Request $request)
    {
        $articulo= Articulo::findOrFail($request->articulo_id);
        $programacion= Programacion_produccion::findOrFail($request->programacion_id);
        $ordenDetalle = OrdenDetalle::findOrFail($request->ordenDetalle);
        $loteCantidadProduccion = OrdenDetalleLote::where('tipo_cantidad','PRODUCCION')->where('orden_produccion_detalle_id',$ordenDetalle->id)->get();
        $loteCantidadExcedida = OrdenDetalleLote::where('tipo_cantidad','EXCEDIDA')->where('orden_produccion_detalle_id',$ordenDetalle->id)->get();
        return view('produccion.ordenes.detallesLotes.edit',[
            'articulo'=> $articulo,
            'programacion' => $programacion,
            'ordenDetalle' => $ordenDetalle,
            'cantidadProducciones' => $loteCantidadProduccion ,
            'cantidadExcedidas' => $loteCantidadExcedida ,    
        ]);

    }


    public function update(Request $request)
    {
        // DETALLE DE LA ORDEN DE PRODUCCION
        $detalle = OrdenDetalle::findOrFail($request->get('ordenDetalle_id'));
        $detalle->articulo_id = $request->get('articulo_id');
        $detalle->cantidad_produccion = $request->get('cantidadProduccion');
        $detalle->cantidad_produccion_completa = $request->get('cantidadProduccionCompleta');
        $detalle->cantidad_excedida = $request->get('cantidadExcedida');
        $detalle->update();

        $cantidadProduccion = json_decode($request->get('cantidadProduccionLote'));
        $detalleLotes = OrdenDetalleLote::where('orden_produccion_detalle_id', $detalle->id)->where('tipo_cantidad','PRODUCCION')->get();
        if ($cantidadProduccion) {
            // DEVOLUCION DE LOTES ARTICULOS
            foreach ($detalleLotes as $detalleLote) {
                $loteArticulo = LoteArticulo::findOrFail($detalleLote->lote_articulo_id);
                $loteArticulo->cantidad = $loteArticulo->cantidad + $detalleLote->cantidad;
                $loteArticulo->cantidad_logica =  $loteArticulo->cantidad;
                $loteArticulo->update();
                $detalleLote->delete();
            }
            
            foreach ($cantidadProduccion as $produccion) { 
                $lote = LoteArticulo::findOrFail($produccion->lote_id);
                OrdenDetalleLote::create([
                    'orden_produccion_detalle_id' => $detalle->id,
                    'lote_articulo_id' => $produccion->lote_id,
                    'cantidad' => $produccion->cantidad,
                    'tipo_cantidad' => 'PRODUCCION',
                ]);

                $lote->cantidad =  $lote->cantidad - $produccion->cantidad;
                $lote->update();
            }
        }else{
            // DEVOLUCION DE LOTES ARTICULOS
            foreach ($detalleLotes as $detalleLote) {
                $loteArticulo = LoteArticulo::findOrFail($detalleLote->lote_articulo_id);
                $loteArticulo->cantidad = $loteArticulo->cantidad + $detalleLote->cantidad;
                $loteArticulo->cantidad_logica =  $loteArticulo->cantidad;
                $loteArticulo->update();
                $detalleLote->delete();
            }
        }


        $cantidadExcedida = json_decode($request->get('cantidadExcedidaLote'));
        $detalleLotes = OrdenDetalleLote::where('orden_produccion_detalle_id', $detalle->id)->where('tipo_cantidad','EXCEDIDA')->get();
        if ($cantidadExcedida) {
            foreach ($cantidadExcedida as $excedida) { 
                // DEVOLUCION DE LOTES ARTICULOS
                foreach ($detalleLotes as $detalleLote) {
                    $loteArticulo = LoteArticulo::findOrFail($detalleLote->lote_articulo_id);
                    $loteArticulo->cantidad = $loteArticulo->cantidad + $detalleLote->cantidad;
                    $loteArticulo->cantidad_logica =  $loteArticulo->cantidad;
                    $loteArticulo->update();
                    $detalleLote->delete();
                }

                $lote = LoteArticulo::findOrFail($excedida->lote_id);
                OrdenDetalleLote::create([
                    'orden_produccion_detalle_id' => $detalle->id,
                    'lote_articulo_id' => $excedida->lote_id,
                    'cantidad' => $excedida->cantidad,
                    'tipo_cantidad' => 'EXCEDIDA',
                ]);
                $lote->cantidad =  $lote->cantidad - $excedida->cantidad;
                $lote->update();
            }
        }else{
            // DEVOLUCION DE LOTES ARTICULOS
            foreach ($detalleLotes as $detalleLote) {
                $loteArticulo = LoteArticulo::findOrFail($detalleLote->lote_articulo_id);
                $loteArticulo->cantidad = $loteArticulo->cantidad + $detalleLote->cantidad;
                $loteArticulo->cantidad_logica =  $loteArticulo->cantidad;
                $loteArticulo->update();
                $detalleLote->delete();
            }
        }

        //CAMBIAR EL ESTADO DEL DETALLE A COMPLETADO TIENE DATOS
        $detalle->completado = '1';
        $detalle->update();
      
        $productoDetalles= ProductoDetalle::where('producto_id',$detalle->orden->producto_id)->where('estado','ACTIVO')->get();
        return redirect()->route('produccion.orden.edit', 
            [
                'orden' => $detalle->orden,
                'productoDetalles' => $productoDetalles,
            ]
        )->with('modificar', 'success');
    }


    public function store(Request $request)
    {
        // ORDEN DE PRODUCCION
        $orden = new Orden();
        $orden->programacion_id = $request->get('programacion_aprobada_id');
        //PROGRAMACION APROBADA
        $programacion= Programacion_produccion::findOrFail($request->get('programacion_aprobada_id'));
        //PRODUCTO DE LA PROGRAMACION APROBADA
        $producto= Producto::findOrFail($programacion->producto_id);
        //DATOS DEL PRODUCTO
        $orden->producto_id = $programacion->producto_id;
        $orden->codigo_producto = $producto->codigo;
        $orden->descripcion_producto = $producto->nombre;
        //DATOS DE LA PROGRAMACION
        $orden->fecha_produccion = $programacion->fecha_produccion;
        $orden->cantidad = $programacion->cantidad_programada;
        $orden->save();

        //BUSCAR TODOS LOS DETALLES DE LA ORDEN DE PRODUCCION 
        $productoDetalles = ProductoDetalle::where('producto_id',$programacion->producto_id)->where('estado','ACTIVO')->get();
        //AGREGAMOS TODOS LOS ARTICULOS DE LA ORDEN AL DETALLE
        foreach ($productoDetalles as $productoDetalle) { 
            OrdenDetalle::create([
                'orden_id' => $orden->id,
                'articulo_id' => $productoDetalle->articulo_id,
                'cantidad_produccion' => $programacion->cantidad_programada * $productoDetalle->cantidad,
                'cantidad_produccion_completa' => $programacion->cantidad_programada * $productoDetalle->cantidad.' '.$productoDetalle->articulo->getMedida(),
                'cantidad_excedida' => $programacion->cantidad_programada * $productoDetalle->cantidad,
            ]);
        }

        // MODIFICAMOS EL DETALLE DE LA ORDEN DE PRODUCCION
        $detalle = OrdenDetalle::where('articulo_id', $request->get('articulo_id'))
                                ->where('orden_id', $orden->id)
                                ->first();
        $detalle->orden_id = $orden->id;
        $detalle->articulo_id = $request->get('articulo_id');
        $detalle->cantidad_produccion = $request->get('cantidadProduccion');
        $detalle->cantidad_produccion_completa = $request->get('cantidadProduccionCompleta');
        $detalle->cantidad_excedida = $request->get('cantidadExcedida');
        $detalle->completado = '1';
        $detalle->update();

        //AGREGAMOS LAS CANTIDADES AL DETALLE LOTE
        $cantidadProduccion = json_decode($request->get('cantidadProduccionLote'));

        if ($cantidadProduccion) {
            foreach ($cantidadProduccion as $produccion) { 
                $lote = LoteArticulo::findOrFail($produccion->lote_id);
                OrdenDetalleLote::create([
                    'orden_produccion_detalle_id' => $detalle->id,
                    'lote_articulo_id' => $produccion->lote_id,
                    'cantidad' => $produccion->cantidad,
                    'tipo_cantidad' => 'PRODUCCION',
                ]);

                $lote->cantidad =  $lote->cantidad - $produccion->cantidad;
                $lote->update();
            }
        }

        $cantidadExcedida = json_decode($request->get('cantidadExcedidaLote'));

        if ($cantidadExcedida) {
            foreach ($cantidadExcedida as $excedida) { 
                $lote = LoteArticulo::findOrFail($excedida->lote_id);
                OrdenDetalleLote::create([
                    'orden_produccion_detalle_id' => $detalle->id,
                    'lote_articulo_id' => $excedida->lote_id,
                    'cantidad' => $excedida->cantidad,
                    'tipo_cantidad' => 'EXCEDIDA',
                ]);
                $lote->cantidad =  $lote->cantidad - $excedida->cantidad;
                $lote->update();
            }
        }

        //CAMBIAR EL ESTADO DE LA PROGRAMACION DE PRODUCCION (NO PUEDA AGREGAR ORDEN DE PRODUCCION)
        $programacion->produccion = '1';
        $programacion->update();
        
        //RETORNAMOS A LA VISTA EDITAR PARA QUE SE SIGA INGRESANDO DETALLE A LA ORDEN

        $productoDetalles= ProductoDetalle::where('producto_id',$programacion->producto_id)->where('estado','ACTIVO')->get();
        return redirect()->route('produccion.orden.edit', 
            [
                'orden' => $orden,
                'productoDetalles' => $productoDetalles,
                'ordenDetalle' => $detalle,
            ]
        )->with('guardar', 'success');
    }

    //LOTES - ARTICULO PARA BUSQUEDA
    public function getLotArticle($articulo_id)
    {
        return datatables()->query(
            DB::table('lote_articulos')
            ->join('articulos','lote_articulos.articulo_id','=','articulos.id')
            ->select('lote_articulos.*',DB::raw('DATE_FORMAT(lote_articulos.fecha_vencimiento, "%d/%m/%Y") as fecha_venci'))
            ->where('lote_articulos.cantidad_logica','>',0) 
            ->where('lote_articulos.estado','1') 
            ->where('lote_articulos.articulo_id',$articulo_id) 
            ->orderBy('lote_articulos.fecha_vencimiento','ASC')  
            ->where('articulos.estado','ACTIVO')
        )->toJson();
    }

    //CAMBIAR CANTIDAD LOGICA DEL LOTE ARTIculo
    public function quantity(Request $request)
    {
      
        $data = $request->all();
        $lote_id = $data['lote_id'];
        $cantidad = $data['cantidad'];
        $condicion = $data['condicion'];
        $mensaje = '';
        $lote = LoteArticulo::findOrFail($lote_id);
        //DISMINUIR
        if ($lote->cantidad_logica >= $cantidad && $condicion == '1' ) {
            $nuevaCantidad = $lote->cantidad_logica - $cantidad;
            $lote->cantidad_logica = $nuevaCantidad;
            $lote->update();
            $mensaje = 'Cantidad aceptada';
        }
        //AUMENTAR
        if ($condicion == '0' ) {
            $nuevaCantidad = $lote->cantidad_logica + $cantidad;
            $lote->cantidad_logica = $nuevaCantidad;
            $lote->update();
            $mensaje = 'Cantidad regresada';
        }

        return $mensaje;
        }
    
}
