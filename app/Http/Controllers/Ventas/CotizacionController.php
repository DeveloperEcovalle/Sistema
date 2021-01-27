<?php

namespace App\Http\Controllers\Ventas;

use App\Http\Controllers\Controller;
use App\Mantenimiento\Empresa\Empresa;
use App\Almacenes\Producto;
use App\Ventas\Cliente;
use App\Ventas\Cotizacion;
use App\Ventas\CotizacionDetalle;
use Illuminate\Http\Request;
use DataTables;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Session;
use Illuminate\Support\Facades\Validator;
use PDF;
use Illuminate\Support\Facades\Mail;

class CotizacionController extends Controller
{
    public function index()
    {
        return view('ventas.cotizaciones.index');
    }

    public function getTable()
    {
        $cotizaciones = Cotizacion::where('estado', '<>', 'ANULADO')->get();
        $coleccion = collect([]);
        foreach($cotizaciones as $cotizacion) {
            $coleccion->push([
                'id' => $cotizacion->id,
                'empresa' => $cotizacion->empresa->razon_social,
                'cliente' => $cotizacion->cliente->nombre,
                'fecha_documento' => Carbon::parse($cotizacion->fecha_documento)->format( 'd/m/Y'),
                'total' => $cotizacion->total,
                'estado' => $cotizacion->estado
            ]);
        }
        return DataTables::of($coleccion)->toJson();
    }

    public function create()
    {
        $empresas = Empresa::where('estado', 'ACTIVO')->get();
        $clientes = Cliente::where('estado', 'ACTIVO')->get();
        $fecha_hoy = Carbon::now()->toDateString();
        $productos = Producto::where('estado', 'ACTIVO')->get();

        return view('ventas.cotizaciones.create', compact('empresas', 'clientes', 'fecha_hoy', 'productos'));
    }

    public function store(Request $request)
    {
        // dd($request);
        
        $data = $request->all();
        

        $rules = [
            'empresa' => 'required',
            'cliente' => 'required',
            'moneda' => 'required',
            'fecha_documento' => 'required|date_format:d/m/Y',
            'fecha_atencion_campo' => 'nullable|date_format:d/m/Y',
            // 'productos_tabla' => 'required|string'
        ];

        $message = [
            'empresa.required' => 'El campo Empresa es obligatorio',
            'cliente.required' => 'El campo Cliente es obligatorio',
            'moneda' => 'El campo Moneda es obligatorio',
            'fecha_documento.required' => 'El campo Fecha de Documento es obligatorio',
            'fecha_documento.date_format:d/m/Y' => 'El formato de la Fecha de Documento es incorrecto',
            'fecha_atencion_campo.date_format:d/m/Y' => 'El formato de la Fecha de Atención es incorrecto',
            // 'productos_tabla.required' => 'Debe exitir al menos un detalle de la cotización',
            // 'productos_tabla.string' => 'El formato de texto de los detalles es incorrecto',
        ];

        Validator::make($data, $rules, $message)->validate();

        // dd($request);
        $cotizacion = new Cotizacion();
        $cotizacion->empresa_id = $request->get('empresa');
        $cotizacion->cliente_id = $request->get('cliente');
        $cotizacion->moneda = $request->get('moneda');
        $cotizacion->fecha_documento = Carbon::createFromFormat('d/m/Y', $request->get('fecha_documento'))->format('Y-m-d');
        $cotizacion->fecha_atencion = Carbon::createFromFormat('d/m/Y', $request->get('fecha_atencion_campo'))->format('Y-m-d');

        $cotizacion->sub_total = (float) $request->get('monto_sub_total');
        $cotizacion->total_igv = (float) $request->get('monto_total_igv');
        $cotizacion->total = (float) $request->get('monto_total');
        
        $cotizacion->user_id = Auth::id();
        $cotizacion->igv = $request->get('igv');
        $cotizacion->igv = $request->get('igv');
        if ($request->get('igv_check') == "on") {
            $cotizacion->igv_check = "1";
        }else{
            $cotizacion->igv_check = '';
        }
        $cotizacion->save();


        //Llenado de los Productos
        $productosJSON = $request->get('productos_tabla');
        $productotabla = json_decode($productosJSON[0]);

        foreach ($productotabla as $producto) {
            CotizacionDetalle::create([
                'cotizacion_id' => $cotizacion->id,
                'producto_id' => $producto->producto_id,
                'cantidad' => $producto->cantidad,
                'precio' => $producto->precio,
                'importe' => $producto->total,
            ]);
        }
        

            // foreach (json_decode($request->get('detalles')) as $detalle) {
            //     $cotizacion_detalle = new CotizacionDetalle();
            //     $cotizacion_detalle->cotizacion_id = $cotizacion->id;
            //     $cotizacion_detalle->producto_id = $detalle->producto_id;
            //     $cotizacion_detalle->cantidad = $detalle->cantidad;
            //     $cotizacion_detalle->precio = $detalle->precio;
            //     $cotizacion_detalle->importe = $detalle->cantidad * $detalle->precio;
            //     $cotizacion_detalle->save();
            // }

  

        Session::flash('success','Cotización creada.');
        return redirect()->route('ventas.cotizacion.index')->with('guardar', 'success');
    }

    public function edit($id)
    {
        $cotizacion = Cotizacion::findOrFail($id);
        $empresas = Empresa::where('estado', 'ACTIVO')->get();
        $clientes = Cliente::where('estado', 'ACTIVO')->get();
        $fecha_hoy = Carbon::now()->toDateString();
        $productos = Producto::where('estado', 'ACTIVO')->get();

        // $detalles = [];
        // foreach ($cotizacion->detalles->where('estado', 'ACTIVO') as $detalle) {
        //     $data = [
        //         'id' => (string)$detalle->id,
        //         'producto_id' => (string)$detalle->producto_id,
        //         'producto' => $detalle->producto->codigo.'-'.$detalle->producto->nombre,
        //         'presentacion' => $detalle->producto->presentacion,
        //         'cantidad' => (int)$detalle->cantidad,
        //         'precio' => (float)$detalle->precio,
        //         'importe' => $detalle->importe
        //     ];
        //     array_push($detalles, $data);
        // }

        // $detalles =  collect([]);
        // foreach ($cotizacion->detalles->where('estado', 'ACTIVO') as $detalle) {
        //     $detalles->push([
        //         'id' => (string)$detalle->id,
        //         'producto_id' => (string)$detalle->producto_id,
        //         'producto' => $detalle->producto->codigo.'-'.$detalle->producto->nombre,
        //         'cantidad' => (int)$detalle->cantidad,
        //         'precio' => (float)$detalle->precio,
        //         'importe' => $detalle->importe
        //     ]);
        //     // array_push($detalles, $data);
        // }

        $detalles = CotizacionDetalle::where('cotizacion_id',$id)->where('estado', 'ACTIVO')->get(); 
        
        // dd($detalles);
        return view('ventas.cotizaciones.edit', [
            'cotizacion' => $cotizacion,
            'empresas' => $empresas,
            'clientes' => $clientes,
            'fecha_hoy' => $fecha_hoy,
            'productos' => $productos,
            'detalles' => $detalles
        ]);
    }

    public function update(Request $request,$id)
    {
        
        // dd($request);
        $data = $request->all();

        $rules = [
            'empresa' => 'required',
            'cliente' => 'required',
            'moneda' => 'required',
            'fecha_documento' => 'required|date_format:d/m/Y',
            'fecha_atencion' => 'nullable|date_format:d/m/Y',
            'monto_sub_total' => 'required',
            'monto_total_igv' => 'required',
            'monto_total' => 'required',
            'igv' => 'required_if:igv_check,==,on|digits_between:1,3',
        ];

        $message = [
            'empresa.required' => 'El campo Empresa es obligatorio',
            'cliente.required' => 'El campo Cliente es obligatorio',
            'moneda' => 'El campo Moneda es obligatorio',
            'fecha_documento.required' => 'El campo Fecha de Documento es obligatorio',
            'fecha_documento.date_format:d/m/Y' => 'El formato de la Fecha de Documento es incorrecto',
            'fecha_atencion.date_format:d/m/Y' => 'El formato de la Fecha de Atención es incorrecto',
            'monto_sub_total.required' => 'El campo Total Afecto es obligatorio',
            'monto_total_igv.required' => 'El campo Total IGV es obligatorio',
            'monto_total.required' => 'El campo Total es obligatorio',
        ];

        Validator::make($data, $rules, $message)->validate();

       
            $cotizacion =  Cotizacion::findOrFail($id);
            $cotizacion->empresa_id = $request->get('empresa');
            $cotizacion->cliente_id = $request->get('cliente');
            $cotizacion->moneda = $request->get('moneda');

            $cotizacion->fecha_documento = Carbon::createFromFormat('d/m/Y', $request->get('fecha_documento'))->format('Y-m-d');
            $cotizacion->fecha_atencion = Carbon::createFromFormat('d/m/Y', $request->get('fecha_atencion'))->format('Y-m-d');
    
            $cotizacion->sub_total = (float) $request->get('monto_sub_total');
            $cotizacion->total_igv = (float) $request->get('monto_total_igv');
            $cotizacion->total = (float) $request->get('monto_total');
    
            $cotizacion->user_id = Auth::id();
            $cotizacion->igv = $request->get('igv');

            if ($request->get('igv_check') == "on") {
                $cotizacion->igv_check = "1";
            }else{
                $cotizacion->igv_check = '';
            }

            $cotizacion->update();

            $productosJSON = $request->get('productos_tabla');
            $productotabla = json_decode($productosJSON[0]);

            if ($productotabla) {
                $detalles = CotizacionDetalle::where('cotizacion_id', $id)->get();

                foreach ($detalles as $detalle) {
                    $detalle->delete();
                }

                foreach ($productotabla as $cotizacion_detalle) {
                    CotizacionDetalle::create([
                        'cotizacion_id'=> $cotizacion->id,
                        'producto_id' => $cotizacion_detalle->producto_id,
                        'cantidad' => $cotizacion_detalle->cantidad,
                        'precio' => $cotizacion_detalle->precio,
                        'importe' => $cotizacion_detalle->total,
                    ]);
                }
    


                
            }

        
        Session::flash('success','Cotización modificada.');
        return redirect()->route('ventas.cotizacion.index')->with('guardar', 'success');
    }

    public function show($id)
    {
        $cotizacion = Cotizacion::findOrFail($id);
        $nombre_completo = $cotizacion->user->empleado->persona->apellido_paterno.' '.$cotizacion->user->empleado->persona->apellido_materno.' '.$cotizacion->user->empleado->persona->nombres;
        $presentaciones = presentaciones(); 
        $detalles = CotizacionDetalle::where('cotizacion_id',$id)->where('estado','ACTIVO')->get();


        
        return view('ventas.cotizaciones.show', [
            'cotizacion' => $cotizacion,
            'detalles' => $detalles,
            'presentaciones' => $presentaciones,
            'nombre_completo' => $nombre_completo
        ]);
    }

    public function destroy($id)
    {
        
        $cotizacion = Cotizacion::findOrFail($id);
        $cotizacion->estado = "ANULADO";
        $cotizacion->update();

        $cotizacion_detalle = CotizacionDetalle::where('cotizacion_id',$id)->get();
        foreach ($cotizacion_detalle as $detalle) {
            $detalle->estado = "ANULADO";
            $detalle->update();

        }
        Session::flash('success','Cotización eliminada.');
        return redirect()->route('ventas.cotizacion.index')->with('eliminar', 'success');
    }

    public function email($id)
    {
        
        $cotizacion = Cotizacion::findOrFail($id);
        $nombre_completo = $cotizacion->user->empleado->persona->apellido_paterno.' '.$cotizacion->user->empleado->persona->apellido_materno.' '.$cotizacion->user->empleado->persona->nombres;
        $igv = '';
        $tipo_moneda = '';
        $detalles = $cotizacion->detalles->where('estado', 'ACTIVO');
        // $detalles = CotizacionDetalle::where('cotizacion_id',$id)->get();

        // $detalles =  collect([]);
        // foreach ($cotizacion->detalles->where('estado', 'ACTIVO') as $detalle) {
        //     $detalles->push([
        //         'id' => (string)$detalle->id,
        //         'producto_id' => (string)$detalle->producto_id,
        //         'producto' => $detalle->producto->codigo.'-'.$detalle->producto->nombre,
        //         'cantidad' => (int)$detalle->cantidad,
        //         'precio' => (float)$detalle->precio,
        //         'importe' => $detalle->importe
        //     ]);
        //     // array_push($detalles, $data);
        // }



        // $subtotal = 0; 
        // $igv = '';
        // $tipo_moneda = '';
        // foreach($detalles as $detalle){
        //     $subtotal = ($detalle->cantidad * $detalle->precio) + $subtotal;
        // }
        // foreach(tipos_moneda() as $moneda){
        //     if ($moneda->descripcion == $orden->moneda) {
        //         $tipo_moneda= $moneda->simbolo;
        //     }
        // }


        // if (!$orden->igv) {
        //     $igv = $subtotal * 0.18;
        //     $total = $subtotal + $igv;
        //     $decimal_subtotal = number_format($subtotal, 2, '.', '');
        //     $decimal_total = number_format($total, 2, '.', '');
        //     $decimal_igv = number_format($igv, 2, '.', ''); 
        // }else{
        //     $calcularIgv = $orden->igv/100;
        //     $base = $subtotal / (1 + $calcularIgv);
        //     $nuevo_igv = $subtotal - $base;
        //     $decimal_subtotal = number_format($base, 2, '.', '');
        //     $decimal_total = number_format($subtotal, 2, '.', '');
        //     $decimal_igv = number_format($nuevo_igv, 2, '.', ''); 
        // }



        // $presentaciones = presentaciones();  
        $paper_size = array(0,0,360,360);
        $pdf = PDF::loadview('ventas.cotizaciones.reportes.detalle',[
            'cotizacion' => $cotizacion,
            'nombre_completo' => $nombre_completo,
            'detalles' => $detalles,
            // 'presentaciones' => $presentaciones,
            // 'subtotal' => $decimal_subtotal,
            // 'moneda' => $tipo_moneda,
            // 'igv' => $decimal_igv,
            // 'total' => $decimal_total,
            ])->setPaper('a4')->setWarnings(false);
        
        Mail::send('email.cotizacion',compact("cotizacion"), function ($mail) use ($pdf,$cotizacion) {
            $mail->to($cotizacion->cliente->correo_electronico);
            $mail->subject('COTIZACION OC-0'.$cotizacion->id);
            $mail->attachdata($pdf->output(), 'COTIZACION CO-0'.$cotizacion->id.'.pdf');
        });


        // //Registrar en correos enviados
        // $correo = new Enviado();
        // $correo->orden_id = $orden->id;
        // $correo->enviado = '1';
        // $correo->usuario = Auth::user()->usuario;
        // $correo->save();

        // //Añadir check
        // $orden->enviado = '1';
        // $orden->update();


        Session::flash('success','Cotización enviado al correo '.$cotizacion->cliente->correo_electronico);
        return redirect()->route('ventas.cotizacion.show', $cotizacion->id)->with('enviar', 'success');
    }

    public function report($id)
    {
        $cotizacion = Cotizacion::findOrFail($id);
        $nombre_completo = $cotizacion->user->empleado->persona->apellido_paterno.' '.$cotizacion->user->empleado->persona->apellido_materno.' '.$cotizacion->user->empleado->persona->nombres;
        // $detalles = Detalle::where('orden_id',$id)->get();
        
        $igv = '';
        $tipo_moneda = '';
        $detalles = $cotizacion->detalles->where('estado', 'ACTIVO');
        // $subtotal = 0; 
        // foreach($detalles as $detalle){
        //     $subtotal = ($detalle->cantidad * $detalle->precio) + $subtotal;
        // }
        
        // $detalles = collect([]);
        // foreach ($cotizacion->detalles->where('estado', 'ACTIVO') as $detalle) {
        //     $detalles->push([
        //         'id' => (string)$detalle->id,
        //         'producto_id' => (string)$detalle->producto_id,
        //         'producto' => $detalle->producto->codigo.'-'.$detalle->producto->nombre,
        //         'cantidad' => (int)$detalle->cantidad,
        //         'precio' => (float)$detalle->precio,
        //         'importe' => $detalle->importe
        //     ]);
           
        // }
        


        // foreach($detalles as $detalle){
        //     $subtotal = ($detalle->cantidad * $detalle->precio) + $subtotal;
        // }
        // foreach(tipos_moneda() as $moneda){
        //     if ($moneda->descripcion == $orden->moneda) {
        //         $tipo_moneda= $moneda->simbolo;
        //     }
        // }

        // if (!$orden->igv) {
        //     $igv = $subtotal * 0.18;
        //     $total = $subtotal + $igv;
        //     $decimal_subtotal = number_format($subtotal, 2, '.', '');
        //     $decimal_total = number_format($total, 2, '.', '');
        //     $decimal_igv = number_format($igv, 2, '.', ''); 
        // }else{
        //     $calcularIgv = $orden->igv/100;
        //     $base = $subtotal / (1 + $calcularIgv);
        //     $nuevo_igv = $subtotal - $base;
        //     $decimal_subtotal = number_format($base, 2, '.', '');
        //     $decimal_total = number_format($subtotal, 2, '.', '');
        //     $decimal_igv = number_format($nuevo_igv, 2, '.', ''); 
        // }



        // $presentaciones = presentaciones();  
        $paper_size = array(0,0,360,360);
        $pdf = PDF::loadview('ventas.cotizaciones.reportes.detalle',[
            'cotizacion' => $cotizacion,
            'nombre_completo' => $nombre_completo,
            'detalles' => $detalles,
            // 'presentaciones' => $presentaciones,
     
            // 'moneda' => $tipo_moneda,
            // 'igv' => $decimal_igv,
            // 'total' => $decimal_total,
            ])->setPaper('a4')->setWarnings(false);
        return $pdf->stream();
        



    }


}
