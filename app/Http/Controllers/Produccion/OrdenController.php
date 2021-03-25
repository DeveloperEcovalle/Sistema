<?php

namespace App\Http\Controllers\Produccion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Produccion\Programacion_produccion;
use App\Produccion\Orden;
use App\Produccion\OrdenDetalle;
use App\Compras\Articulo;
use DataTables;
use Carbon\Carbon;
use Session;
use Illuminate\Support\Facades\Validator;
use App\Almacenes\ProductoDetalle;
use App\Almacenes\Producto;
use App\Almacenes\Almacen;


class OrdenController extends Controller
{
    public function index()
    {   
        $fecha_hoy = Carbon::now()->toDateString();
        return view('produccion.ordenes.index',
            [
                'fecha_hoy' => $fecha_hoy,
            ]
        );
    }

    public function getOrdenes(){
        $ordenes = Orden::select('orden_produccion.*')
                    ->orderBy('id', 'desc')->get();
        $coleccion = collect([]);
        foreach($ordenes as $orden) {
            $coleccion->push([
                'id' => $orden->id,
                'programacion_id' => $orden->programacion_id,
                'producto' => $orden->programacion->producto->codigo.' - '.$orden->programacion->producto->nombre,
                'cantidad_programada' => $orden->programacion->cantidad_programada,
                'fecha_orden'=> Carbon::parse($orden->fecha_orden)->format( 'd/m/Y'),
                'observacion' => ($orden->observacion) ? $orden->observacion : '-',
                'conformidad' => $orden->conformidad,
                'estado' => $orden->estado,
                'editable' => $orden->editable,
            ]);
        }
        return DataTables::of($coleccion)->toJson();
    }

    public function create($id)
    {
        $fecha_hoy = Carbon::now()->toDateString();
        $programacion= Programacion_produccion::findOrFail($id);
        $almacenes = Almacen::where('estado','ACTIVO')->get();
        
        return view('produccion.ordenes.create',[
            'programacion'=> $programacion,
            'almacenes'=> $almacenes,
            'fecha_hoy' => $fecha_hoy,
           
        ]);
    }

    public function ObtenerOperacion()
    {
        $detalle = OrdenDetalle::all();
        return $detalle->last();
    }

    // public function stockArticulo($detalle)
    // {
    //     if ($detalle->cantidad_devuelta_correcta) {
    //         $correcto = $detalle->cantidad_devuelta_correcta;
    //     }else{
    //         $correcto = 0;
    //     }
    //     $nuevoStock = ($detalle->productoDetalle->articulo->stock - $detalle->cantidad_entregada) + $correcto; 
    //     $articulo = Articulo::findOrFail($detalle->productoDetalle->articulo->id);
    //     $articulo->stock =  $nuevoStock;
    //     $articulo->update();

    // }

    public function store(Request $request)
    {
       
        $data = $request->all();
        $rules = [
            'programacion_aprobada_id' => 'required',
            'fecha_produccion' => 'required',
            'cantidad_programada'=>'required',
        ];

        $message = [
            'required.programacion_aprobada_id'=>'El campo Producto es obligatorio',
            'required.fecha_produccion'=>'El campo Fecha de Producción es obligatorio',
            'required.cantidad_programada'=>'El campo Cantidad Producir es obligatorio',
        ];

        Validator::make($data, $rules, $message)->validate();

        $orden = new Orden;
        $orden->programacion_id =  $request->get('programacion_aprobada_id');
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
        //DATOS DE LA ORDEN 
        $orden->fecha_orden = Carbon::createFromFormat('d/m/Y', $request->get('fecha_produccion'))->format('Y-m-d');
        $orden->save();

        //Llenado de detalles
        $detalleJSON = $request->get('productos_detalle');       
        $detalletabla = json_decode($detalleJSON);
        foreach ($detalletabla as $detalle) {
            $operacion = self::ObtenerOperacion();
            if($operacion != null){
                $num = $operacion->operacion + 1; 
            }else{
                $num = 1000;
            }
            
            $detalleProducto = OrdenDetalle::create([
                'orden_id' => $orden->id,
                'articulo_id' => $detalle->id, //PRODUCTO DETALLE ID
                'cantidad_solicitada' => $detalle->cantidad_solicitada,
                'cantidad_entregada' => $detalle->cantidad_entregada,
                'almacen_correcto_id' => ($detalle->cantidad_devuelta_correcta_almacen) ? $detalle->cantidad_devuelta_correcta_almacen : null ,
                'cantidad_devuelta_correcta' => ($detalle->cantidad_devuelta_correcta_cantidad) ? $detalle->cantidad_devuelta_correcta_cantidad : null ,
                'almacen_incorrecto_id' => ($detalle->cantidad_devuelta_incorrecta_almacen) ? $detalle->cantidad_devuelta_incorrecta_almacen : null ,
                'cantidad_devuelta_incorrecta' => ($detalle->cantidad_devuelta_incorrecta_cantidad) ? $detalle->cantidad_devuelta_incorrecta_cantidad : null ,
                'operacion' => $num,
                'observacion_correcta' => $detalle->observacion_correcta,
                'observacion_incorrecta' => $detalle->observacion_incorrecta,
            ]);

            // self::stockArticulo($detalleProducto);
        }

        $programacion->produccion =  'ATENDIDA';
        $programacion->update();

        Session::flash('success','Orden de Produccion creada');
        return redirect()->route('produccion.orden.index')->with('guardar', 'success');
        
    }

    public function destroy(Request $request)
    {
        $orden = Orden::findOrFail($request->get('orden_id'));
        $orden->estado = 'ANULADO';
        $orden->observacion = $request->get('observacion');
        $orden->update();

        Session::flash('success','Orden de Produccion eliminado.');
        return redirect()->route('produccion.orden.index')->with('eliminar', 'success');
    }

    public function show($id)
    {
        $orden = Orden::findOrFail($id);
        $detalles = OrdenDetalle::where('orden_id',$id)->get();
       
        return view('produccion.ordenes.show',[
            'orden'=> $orden,
            'detalles'=>  $detalles,           
        ]);
    }


    public function getOrden($id)
    {
        $orden = Orden::findOrFail($id);
        return  response()->json($orden); 
    }


    public function getArticles($id)
    {
        $programacion= Programacion_produccion::findOrFail($id);
        $productodetalles = ProductoDetalle::where('producto_id',$programacion->producto_id)->where('estado','ACTIVO')->get();
     
        $coleccion = collect([]);
        foreach($productodetalles  as $detalle) {
            $coleccion->push([
                'id' => $detalle->id,
                'articulo' =>  $detalle->articulo->codigo_fabrica.' - '.$detalle->articulo->descripcion,
                'cantidad_solicitada_completa' => $programacion->cantidad_programada * $detalle->cantidad.' '.$detalle->articulo->getMedida(),
                'cantidad_solicitada' => $programacion->cantidad_programada * $detalle->cantidad,
                'cantidad_entregada' => '',
                'cantidad_devuelta_correcta_completa' => '',
                'cantidad_devuelta_incorrecta_completa' => '',
                'observacion_correcta' => '',
                'observacion_incorrecta' => '',
                'cantidad_devuelta_correcta_cantidad' => '',
                'cantidad_devuelta_correcta_almacen' => '',
                'cantidad_devuelta_incorrecta_cantidad' => '',
                'cantidad_devuelta_incorrecta_almacen' => '',
            ]);
        }
        return DataTables::of($coleccion)->toJson();
    }
}
