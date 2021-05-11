<?php

namespace App\Http\Controllers\Almacenes;

use App\Almacenes\DetalleNotaSalidad;
use App\Almacenes\NotaSalidad;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Carbon;
use App\Mantenimiento\Tabla\General;
use App\User;
use App\Almacenes\Producto;
use Illuminate\Support\Facades\Validator;
use App\Almacenes\MovimientoNota;
use App\Almacenes\LoteProducto;
use Illuminate\Support\Facades\Session;

class NotaSalidadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('almacenes.nota_salidad.index');
    }
    public function gettable()
    {
      $data=DB::table("nota_salidad as n")
      ->select('n.*',)->where('n.estado','ACTIVO')->get();
      return Datatables::of($data)->make(true);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $fecha_hoy = Carbon::now()->toDateString();
        $fecha=Carbon::createFromFormat('Y-m-d', $fecha_hoy);
        $fecha=str_replace("-", "", $fecha);
        $fecha=str_replace(" ", "", $fecha);
        $fecha=str_replace(":", "", $fecha);
        $origenes=  General::find(28)->detalles;
        $destinos=  General::find(29)->detalles;
        $lotes=DB::table('lote_productos')->get();
        $ngenerado=$fecha.(DB::table('nota_salidad')->count()+1);
        $usuarios=User::get();
        $productos=Producto::where('estado','ACTIVO')->get();
        return view('almacenes.nota_salidad.create',["fecha_hoy"=>$fecha_hoy,
        "origenes"=>$origenes,'destinos'=>$destinos,
        'ngenerado'=>$ngenerado,'usuarios'=>$usuarios,
        'productos'=>$productos,'lotes'=>$lotes]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //return $request;
        $data = $request->all();

        $rules = [
     
            'fecha' => 'required',
            'destino' => 'required',
            'origen' => 'required',
            'notadetalle_tabla'=>'required',
        
            
        ];
        $message = [
            'fecha.required' => 'El campo fecha  es Obligatorio',
            'destino.required' => 'El campo destino  es Obligatorio',
            'origen.required' => 'El campo origen  es Obligatorio',
            'notadetalle_tabla.required'=>'No hay dispositivos',
        ];

        Validator::make($data, $rules, $message)->validate();
    
        $notasalidad=new NotaSalidad();
        $notasalidad->numero=$request->get('numero');
        $notasalidad->fecha=$request->get('fecha');
        $destino=DB::table('tabladetalles')->where('id',$request->destino)->first();
        $notasalidad->destino=$destino->descripcion;
        $notasalidad->origen=$request->origen;
        $notasalidad->usuario=Auth()->user()->usuario;
        $notasalidad->save();

        $articulosJSON = $request->get('notadetalle_tabla');
        $notatabla = json_decode($articulosJSON[0]);

        foreach ($notatabla as $fila) {
           DetalleNotaSalidad::create([
                'nota_salidad_id' => $notasalidad->id,
                'lote_id' => $fila->lote_id,
                'cantidad' => $fila->cantidad,
                'producto_id'=> $fila->producto_id,
            ]);
            $producto=DB::table('productos')->where('id',$fila->producto_id)->first();
            MovimientoNota::create([
                'cantidad'=>$fila->cantidad,
                'observacion'=>$producto->nombre,
                'movimiento'=>"SALIDA",
                'lote_id'=>$fila->lote_id,
                'usuario_id'=>Auth()->user()->id,
                'nota_id'=>$notasalidad->id,
                'producto_id'=>$fila->producto_id,
            ]);

            $lote_producto=LoteProducto::findOrFail($fila->lote_id);
            $lote_productocantidad=$lote_producto->cantidad-$fila->cantidad;
            $lote_productocantidad_logica=$lote_producto->cantidad_logica-$fila->cantidad;
            DB::update('update lote_productos set cantidad= ?,cantidad_logica = ? where id = ?', [$lote_productocantidad,$lote_productocantidad_logica,$fila->lote_id]);
            
    
        }
        $descripcion = "SE AGREGÓ LA NOTA DE SALIDAD";
        $gestion = "ALMACEN / NOTA SALIDAD";
        crearRegistro($notasalidad, $descripcion , $gestion);
        

        Session::flash('success','NOTA DE SALIDAD');
        return redirect()->route('almacenes.nota_salidad.index')->with('guardar', 'success');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
        $notasalidad=NotaSalidad::findOrFail($id);
        $data=array();
        $detallenotasalidad=DB::table('detalle_nota_salidad')->where('nota_salidad_id',$notasalidad->id)->get();
        foreach($detallenotasalidad as $fila)
        {
            $lote=DB::table('lote_productos')->where('id',$fila->lote_id)->first();
            $producto=DB::table('productos')->where('id',$fila->producto_id)->first();
            array_push($data,array(
                    'producto_id'=>$fila->producto_id,
                    'cantidad'=>$fila->cantidad,
                    'lote'=>$lote->codigo,
                    'producto'=>$producto->nombre,
                    'lote_id'=>$fila->lote_id
            ));
        }
        $origenes=  General::find(28)->detalles;
        $destinos=  General::find(29)->detalles;
        $lotes=DB::table('lote_productos')->get();
        $usuarios=User::get();
        $productos=Producto::where('estado','ACTIVO')->get();
        return view('almacenes.nota_salidad.edit',[
        "origenes"=>$origenes,'destinos'=>$destinos,
       'usuarios'=>$usuarios,
        'productos'=>$productos,'lotes'=>$lotes,'notasalidad'=>$notasalidad,'detalle'=>json_encode($data)]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
         $data = $request->all();

         $rules = [
             'fecha' => 'required',
             'destino' => 'required',
             'origen' => 'required',
             'notadetalle_tabla'=>'required',
         ];
         $message = [
             'fecha.required' => 'El campo fecha  es Obligatorio',
             'destino.required' => 'El campo destino  es Obligatorio',
             'origen.required' => 'El campo origen  es Obligatorio',
             'notadetalle_tabla.required'=>'No hay dispositivos',
         ];
 
         Validator::make($data, $rules, $message)->validate();
        
         
         //$registro_sanitario = new RegistroSanitario();
         $notasalidad=NotaSalidad::findOrFail($id);
         $notasalidad->fecha=$request->get('fecha');
         $destino=DB::table('tabladetalles')->where('id',$request->destino)->first();
         $notasalidad->destino=$destino->descripcion;
         $notasalidad->usuario=Auth()->user()->usuario;
         $notasalidad->update();
 
         $articulosJSON = $request->get('notadetalle_tabla');
         $notatabla = json_decode($articulosJSON[0]);
         if($notatabla!="")
         {
             DetalleNotaSalidad::where('nota_salidad_id',$notasalidad->id)->delete();
             foreach ($notatabla as $fila) {
                 DetalleNotaSalidad::create([
                      'nota_salidad_id' => $id,
                      'lote_id' => $fila->lote_id,
                      'cantidad' => $fila->cantidad,
                      'producto_id'=> $fila->producto_id,
                  ]);
                 
      
                  $lote_producto=LoteProducto::findOrFail($fila->lote_id);
                  $cantidadmovimiento=DB::table("movimiento_nota")->where('lote_id',$fila->lote_id)->where('producto_id',$fila->producto_id)->where('nota_id',$id)->where('movimiento','SALIDA')->first()->cantidad;
                  $lote_productocantidad=$lote_producto->cantidad+$cantidadmovimiento;
                  $lote_productocantidad_logica=$lote_producto->cantidad+$cantidadmovimiento;
                  $lote_productocantidad=$lote_productocantidad-$fila->cantidad;
                  $lote_productocantidad_logica=$lote_productocantidad_logica-$fila->cantidad;
 
                  DB::update('update lote_productos set cantidad= ?,cantidad_logica = ? where id = ?', [$lote_productocantidad,$lote_productocantidad_logica,$fila->lote_id]);
                  MovimientoNota::where('lote_id',$fila->lote_id)->where('producto_id',$fila->producto_id)->where('nota_id',$id)->where('movimiento','SALIDA')->delete();
                  $producto=DB::table('productos')->where('id',$fila->producto_id)->first();
                  MovimientoNota::create([
                      'cantidad'=>$fila->cantidad,
                      'observacion'=>$producto->nombre,
                      'movimiento'=>"SALIDA",
                      'lote_id'=>$fila->lote_id,
                      'usuario_id'=>Auth()->user()->id,
                      'producto_id'=>$fila->producto_id,
                      'nota_id'=>$id,
                  ]);
          
              }
         }
     
         
       
 
        
 
         //Registro de actividad
         $descripcion = "SE AGREGÓ LA NOTA DE SALIDAD ";
         $gestion = "ALMACEN / NOTA SALIDAD";
         crearRegistro($notasalidad, $descripcion , $gestion);
         
 
         Session::flash('success','NOTA DE SALIDAD');
         return redirect()->route('almacenes.nota_salidad.index')->with('guardar', 'success');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $notasalidad=NotaSalidad::findOrFail($id);
        $notasalidad->estado="ANULADO";
        $notasalidad->save();
        Session::flash('success','NOTA DE SALIDAD');
        return redirect()->route('almacenes.nota_salidad.index')->with('guardar', 'success');
    }
    public function getLot()
    {
        return datatables()->query(
            DB::table('lote_productos')
            ->join('productos_clientes','productos_clientes.producto_id','=','lote_productos.producto_id')
            ->join('productos','productos.id','=','lote_productos.producto_id') 
            ->join('familias','familias.id','=','productos.familia_id')
            ->join('tabladetalles','tabladetalles.id','=','productos.medida')
            ->select('lote_productos.*','productos.nombre','productos_clientes.cliente','productos_clientes.moneda','tabladetalles.simbolo as unidad_producto',
                    'productos_clientes.monto as precio_venta','familias.familia', DB::raw('DATE_FORMAT(lote_productos.fecha_vencimiento, "%d/%m/%Y") as fecha_venci'))
            ->where('lote_productos.cantidad_logica','>',0) 
            ->where('lote_productos.estado','1') 
            ->where('productos_clientes.cliente','29') //TIPO DE CLIENTE CONSUMIDOR TABLA DETALLE (29)
            ->where('productos_clientes.moneda','4') // TABLA DETALLE SOLES(4)
            ->orderBy('lote_productos.id','ASC')  
            ->where('productos_clientes.estado','ACTIVO')
        )->toJson();
    }
}
