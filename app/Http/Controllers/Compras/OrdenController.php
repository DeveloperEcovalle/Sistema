<?php

namespace App\Http\Controllers\Compras;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Compras\Orden;
use App\Compras\Detalle;
use App\Compras\Proveedor;
use App\Compras\Enviado;
use App\Mantenimiento\Empresa\Empresa;
use App\Compras\Articulo;
use DataTables;
use Carbon\Carbon;
use Session;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use PDF;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrdenCompra;
use Illuminate\Support\Facades\Auth;
use DB;


class OrdenController extends Controller
{
    public function index()
    {
        return view('compras.ordenes.index');
    }

    public function getOrder(){

        //Cambiar a estado pendiente si la fecha de la de hoy
        DB::table('ordenes')->where('estado','!=','ANULADO')
        ->where('estado','!=','CONCRETADA')
        ->chunkById(100,function ($ordenes) {
            $fecha_hoy = Carbon::now();

            foreach ($ordenes as $orden) {

                if ($orden->fecha_entrega <= $fecha_hoy->toDateString()) {
                    DB::table('ordenes')
                    ->where('id', $orden->id)
                    ->update(['estado' => 'PENDIENTE']);
                }else{
                    DB::table('ordenes')
                    ->where('id', $orden->id)
                    ->update(['estado' => 'VIGENTE']);
                }

            }
        });
        $ordenes = Orden::where('estado','!=','CONCRETADA')->where('estado','!=','ANULADO')->get();

        $coleccion = collect([]);
        foreach($ordenes as $orden){

            
            $detalles = Detalle::where('orden_id',$orden->id)->get(); 
            $subtotal = 0; 
            $igv = '';
            $tipo_moneda = '';

            foreach($detalles as $detalle){
                $subtotal = ($detalle->cantidad * $detalle->precio) + $subtotal;
            }

            foreach(tipos_moneda() as $moneda){
                if ($moneda->descripcion == $orden->moneda) {
                    $tipo_moneda= $moneda->simbolo;
                }
            }

            if (!$orden->igv) {
                $igv = $subtotal * 0.18;
                $total = $subtotal + $igv;
                $decimal_total = number_format($total, 2, '.', ''); 
            }else{
                $calcularIgv = $orden->igv/100;
                $base = $subtotal / (1 + $calcularIgv);
                $nuevo_igv = $subtotal - $base;
                $decimal_total = number_format($subtotal, 2, '.', '');
            }

            //Pagos a cuenta
            $pagos = DB::table('pagos')
            ->join('ordenes','pagos.orden_id','=','ordenes.id')
            ->select('pagos.*','ordenes.moneda as moneda_orden')
            ->where('pagos.orden_id','=',$orden->id)
            ->where('pagos.estado','!=','ANULADO')
            ->get();


            // CALCULAR ACUENTA EN MONEDA
            $acuenta = 0;
            $soles = 0;
            foreach($pagos as $pago){

                if ($pago->moneda_orden == $pago->moneda_empresa && $pago->moneda_orden == $pago->moneda_proveedor ) {
                    $acuenta = $acuenta + $pago->monto;
                }else{
    
                    if ($pago->moneda_empresa == $pago->moneda_proveedor ) {
                        if ($pago->moneda_orden != 'SOLES') {
                            $acuenta = $acuenta + ($pago->monto/$pago->tc_dia);
                        }else{
                            $acuenta = $acuenta + ($pago->monto*$pago->tc_dia);
                        }
                        
                    }else{
                        if ($pago->moneda_orden == $pago->moneda_empresa) {
                            $acuenta = $acuenta + $pago->monto;
                        }else{
                                if ($pago->moneda_empresa == 'SOLES') {
                                    $acuenta = $acuenta + ($pago->monto/$pago->tc_banco);
                                }else{
                                    $acuenta = $acuenta + ($pago->monto*$pago->tc_banco);
                                }
                        }
                    }
    
     
                }

                //CALCULAR A CUENTA EN SOLES
                if($pago->moneda_empresa != "SOLES" && $pago->moneda_proveedor != "SOLES" && $pago->moneda_orden != "SOLES"  ){
                    $soles = $soles + ($pago->monto*$pago->tipo_cambio_soles);
                }else{
                    if ($pago->moneda_empresa == "SOLES") {
                        $soles = $soles + $pago->monto;
                    }else{
                        if ($pago->moneda_proveedor == "SOLES") {
                            $soles = $soles + ($pago->tc_banco*$pago->monto);
                        }else{
                            $soles = $soles + ($pago->tipo_cambio_soles*$pago->monto);
                        }
                    }
                }

            }

            //CALCULAR SALDO
            $saldo = $decimal_total - $acuenta;

            //CAMBIAR ESTADO DE LA ORDEN A PAGADA
        
            if ($saldo < 1) {
                $orden->estado = "PAGADA";
                $orden->update();
            }

            

            $coleccion->push([
                'id' => $orden->id,
                'proveedor' => $orden->proveedor->descripcion,
                'fecha_emision' =>  Carbon::parse($orden->fecha_emision)->format( 'd/m/Y'),
                'fecha_entrega' =>  Carbon::parse($orden->fecha_entrega)->format( 'd/m/Y'), 
                'estado' => $orden->estado,
                'total' => $tipo_moneda.' '.number_format($decimal_total, 2, '.', ''),
                'acuenta' => $tipo_moneda.' '.number_format($acuenta, 2, '.', ''),
                'acuenta_soles' => 'S/. '.number_format($soles, 2, '.', ''),
                'saldo' => $tipo_moneda.' '.number_format($saldo, 2, '.', ''),
            ]);
        }
  
        return DataTables::of($coleccion)->toJson();
    }

    public function create()
    {
        $empresas = Empresa::where('estado','ACTIVO')->get();
        $proveedores = Proveedor::where('estado','ACTIVO')->get();
        $articulos = Articulo::where('estado','ACTIVO')->get();
        $presentaciones =  presentaciones();
        $modos =  modo_compra();
        $fecha_hoy = Carbon::now()->toDateString();
        $monedas =  tipos_moneda();
        
        return view('compras.ordenes.create',[
            'empresas' => $empresas,
            'proveedores' => $proveedores,
            'articulos' => $articulos, 
            'presentaciones' => $presentaciones,
            'fecha_hoy' => $fecha_hoy,
            'modos' => $modos,
            'monedas' => $monedas,
        ]);
    }

    
    public function edit($id)
    {
        $empresas = Empresa::where('estado','ACTIVO')->get();
        $detalles = Detalle::where('orden_id',$id)->get();        
        $proveedores = Proveedor::where('estado','ACTIVO')->get();
        $orden = Orden::findOrFail($id);

        $articulos = Articulo::where('estado','ACTIVO')->get();
        $presentaciones =  presentaciones();
        $modos =  modo_compra();
        $monedas =  tipos_moneda();
        $fecha_hoy = Carbon::now()->toDateString();

        return view('compras.ordenes.edit',[
            'empresas' => $empresas,
            'proveedores' => $proveedores,
            'orden' => $orden,
            'articulos' => $articulos, 
            'presentaciones' => $presentaciones,
            'fecha_hoy' => $fecha_hoy, 
            'detalles' => $detalles,
            'modos' => $modos,
            'monedas' => $monedas,
        ]);
    }

    public function store(Request $request){
        $data = $request->all();
        $rules = [
            'fecha_emision'=> 'required',
            'fecha_entrega'=> 'required',
            'empresa_id'=> 'required',
            'proveedor_id'=> 'required',
            'modo_compra'=> 'required',
            'observacion' => 'nullable',
            'moneda' => 'nullable',
            'tipo_cambio' => 'nullable|numeric',
            'igv' => 'required_if:igv_check,==,on|numeric|digits_between:1,3',
            
        ];
        $message = [
            'fecha_emision.required' => 'El campo Fecha de Emisión es obligatorio.',
            'fecha_entrega.required' => 'El campo Fecha de Entrega es obligatorio.',
            'empresa_id.required' => 'El campo Empresa es obligatorio.',
            'proveedor_id.required' => 'El campo Proveedor es obligatorio.',
            'modo_compra.required' => 'El campo Modo de Compra es obligatorio.',
            'moneda.required' => 'El campo Moneda es obligatorio.',
            'igv.required_if' => 'El campo Igv es obligatorio.',
            'igv.digits' => 'El campo Igv puede contener hasta 3 dígitos.',
            'igv.numeric' => 'El campo Igv debe se numérico.',
            'tipo_cambio.numeric' => 'El campo Tipo de Cambio debe se numérico.',


        ];
        Validator::make($data, $rules, $message)->validate();

        $orden = new Orden();        
        $orden->fecha_emision = Carbon::createFromFormat('d/m/Y', $request->get('fecha_emision'))->format('Y-m-d');
        $orden->fecha_entrega = Carbon::createFromFormat('d/m/Y', $request->get('fecha_entrega'))->format('Y-m-d');
        $orden->empresa_id = $request->get('empresa_id');
        $orden->proveedor_id = $request->get('proveedor_id');
        $orden->modo_compra = $request->get('modo_compra');
        $orden->observacion = $request->get('observacion');
        $orden->moneda = $request->get('moneda');
        $orden->tipo_cambio = $request->get('tipo_cambio');
        $orden->usuario_id = auth()->user()->id;
        $orden->igv = $request->get('igv');
        if ($request->get('igv_check') == "on") {
            $orden->igv_check = "1";
        };
        $orden->save();

        //Llenado de los articulos
        $articulosJSON = $request->get('articulos_tabla');
        $articulotabla = json_decode($articulosJSON[0]);

        foreach ($articulotabla as $articulo) {
            Detalle::create([
                'orden_id' => $orden->id,
                'articulo_id' => $articulo->articulo_id,
                'cantidad' => $articulo->cantidad,
                'precio' => $articulo->precio,
            ]);
        }

        Session::flash('success','Orden de Compra creada.');
        return redirect()->route('compras.orden.index')->with('guardar', 'success');
    }

    public function update(Request $request, $id){
        $data = $request->all();
        $rules = [
            'fecha_emision'=> 'required',
            'fecha_entrega'=> 'required',
            'empresa_id'=> 'required',
            'proveedor_id'=> 'required',
            'modo_compra'=> 'required',
            'observacion' => 'nullable',
            'moneda' => 'nullable',
            'igv' => 'required_if:igv_check,==,on|digits_between:1,3',
        ];
        $message = [
            'fecha_emision.required' => 'El campo Fecha de Emisión es obligatorio.',
            'fecha_entrega.required' => 'El campo Fecha de Entrega es obligatorio.',
            'empresa_id.required' => 'El campo Empresa es obligatorio.',
            'proveedor_id.required' => 'El campo Proveedor es obligatorio.',
            'modo_compra.required' => 'El campo Modo de Compra es obligatorio.',
            'moneda.required' => 'El campo Moneda es obligatorio.',
            'igv.required_if' => 'El campo Igv es obligatorio.',
            'igv.digits' => 'El campo Igv puede contener hasta 3 dígitos.',
            'igv.numeric' => 'El campo Igv debe se numérico.',

        ];
        Validator::make($data, $rules, $message)->validate();

        $orden = Orden::findOrFail($id);        
        $orden->fecha_emision = Carbon::createFromFormat('d/m/Y', $request->get('fecha_emision'))->format('Y-m-d');
        $orden->fecha_entrega = Carbon::createFromFormat('d/m/Y', $request->get('fecha_entrega'))->format('Y-m-d');
        $orden->empresa_id = $request->get('empresa_id');
        $orden->proveedor_id = $request->get('proveedor_id');
        $orden->modo_compra = $request->get('modo_compra');
        $orden->moneda = $request->get('moneda');
        $orden->observacion = $request->get('observacion');
        $orden->usuario_id = auth()->user()->id;
        $orden->igv = $request->get('igv');
        $orden->tipo_cambio = $request->get('tipo_cambio');
        
        if ($request->get('igv_check') == "on") {
            $orden->igv_check = "1";
        }else{
            $orden->igv_check = '';
        }
        $orden->save();

        $articulosJSON = $request->get('articulos_tabla');
        $articulotabla = json_decode($articulosJSON[0]);

        
        if ($articulotabla) {
            $detalles = Detalle::where('orden_id', $orden->id)->get();
            foreach ($detalles as $detalle) {
                $detalle->delete();
            }
            foreach ($articulotabla as $articulo) {
                Detalle::create([
                    'orden_id' => $orden->id,
                    'articulo_id' => $articulo->articulo_id,
                    'cantidad' => $articulo->cantidad,
                    'precio' => $articulo->precio,
                ]);
            }
        }
        
        Session::flash('success','Orden de Compra modificada.');
        return redirect()->route('compras.orden.index')->with('modificar', 'success');
    }

    public function destroy($id)
    {
        
        $orden = Orden::findOrFail($id);
        $orden->estado = 'ANULADO';
        $orden->update();

        Session::flash('success','Orden de Compra eliminada.');
        return redirect()->route('compras.orden.index')->with('eliminar', 'success');

    }

    public function show($id)
    {
        $orden = Orden::findOrFail($id);
        $nombre_completo = $orden->usuario->empleado->persona->apellido_paterno.' '.$orden->usuario->empleado->persona->apellido_materno.' '.$orden->usuario->empleado->persona->nombres;
        $detalles = Detalle::where('orden_id',$id)->get(); 
        $presentaciones = presentaciones(); 
        $subtotal = 0; 
        $igv = '';
        $tipo_moneda = '';
   
        
        foreach($detalles as $detalle){
            $subtotal = ($detalle->cantidad * $detalle->precio) + $subtotal;
        }


        foreach(tipos_moneda() as $moneda){
            if ($moneda->descripcion == $orden->moneda) {
                $tipo_moneda= $moneda->simbolo;
            }
        }


        if (!$orden->igv) {
               $igv = $subtotal * 0.18;
               $total = $subtotal + $igv;
               $decimal_subtotal = number_format($subtotal, 2, '.', '');
               $decimal_total = number_format($total, 2, '.', '');
               $decimal_igv = number_format($igv, 2, '.', ''); 
        }else{
            $calcularIgv = $orden->igv/100;
            $base = $subtotal / (1 + $calcularIgv);
            $nuevo_igv = $subtotal - $base;
            $decimal_subtotal = number_format($base, 2, '.', '');
            $decimal_total = number_format($subtotal, 2, '.', '');
            $decimal_igv = number_format($nuevo_igv, 2, '.', ''); 
        }

 
        
        return view('compras.ordenes.show', [
            'orden' => $orden,
            'detalles' => $detalles,
            'presentaciones' => $presentaciones,
            'subtotal' => $decimal_subtotal,
            'moneda' => $tipo_moneda,
            'igv' => $decimal_igv,
            'total' => $decimal_total,
            'nombre_completo' => $nombre_completo
        ]);

    }

    public function report($id)
    {
        $orden = Orden::findOrFail($id);
        $nombre_completo = $orden->usuario->empleado->persona->apellido_paterno.' '.$orden->usuario->empleado->persona->apellido_materno.' '.$orden->usuario->empleado->persona->nombres;
        $detalles = Detalle::where('orden_id',$id)->get();
        $subtotal = 0; 
        $igv = '';
        $tipo_moneda = '';
        foreach($detalles as $detalle){
            $subtotal = ($detalle->cantidad * $detalle->precio) + $subtotal;
        }
        foreach(tipos_moneda() as $moneda){
            if ($moneda->descripcion == $orden->moneda) {
                $tipo_moneda= $moneda->simbolo;
            }
        }


        if (!$orden->igv) {
            $igv = $subtotal * 0.18;
            $total = $subtotal + $igv;
            $decimal_subtotal = number_format($subtotal, 2, '.', '');
            $decimal_total = number_format($total, 2, '.', '');
            $decimal_igv = number_format($igv, 2, '.', ''); 
        }else{
            $calcularIgv = $orden->igv/100;
            $base = $subtotal / (1 + $calcularIgv);
            $nuevo_igv = $subtotal - $base;
            $decimal_subtotal = number_format($base, 2, '.', '');
            $decimal_total = number_format($subtotal, 2, '.', '');
            $decimal_igv = number_format($nuevo_igv, 2, '.', ''); 
        }



        $presentaciones = presentaciones();  
        $paper_size = array(0,0,360,360);
        $pdf = PDF::loadview('compras.ordenes.reportes.detalle',[
            'orden' => $orden,
            'nombre_completo' => $nombre_completo,
            'detalles' => $detalles,
            'presentaciones' => $presentaciones,
            'subtotal' => $decimal_subtotal,
            'moneda' => $tipo_moneda,
            'igv' => $decimal_igv,
            'total' => $decimal_total,
            ])->setPaper('a4')->setWarnings(false);
        return $pdf->stream();
        



    }

    public function email($id)
    {
        $orden = Orden::findOrFail($id);
        $nombre_completo = $orden->usuario->empleado->persona->apellido_paterno.' '.$orden->usuario->empleado->persona->apellido_materno.' '.$orden->usuario->empleado->persona->nombres;
        $detalles = Detalle::where('orden_id',$id)->get();
        $subtotal = 0; 
        $igv = '';
        $tipo_moneda = '';
        foreach($detalles as $detalle){
            $subtotal = ($detalle->cantidad * $detalle->precio) + $subtotal;
        }
        foreach(tipos_moneda() as $moneda){
            if ($moneda->descripcion == $orden->moneda) {
                $tipo_moneda= $moneda->simbolo;
            }
        }


        if (!$orden->igv) {
            $igv = $subtotal * 0.18;
            $total = $subtotal + $igv;
            $decimal_subtotal = number_format($subtotal, 2, '.', '');
            $decimal_total = number_format($total, 2, '.', '');
            $decimal_igv = number_format($igv, 2, '.', ''); 
        }else{
            $calcularIgv = $orden->igv/100;
            $base = $subtotal / (1 + $calcularIgv);
            $nuevo_igv = $subtotal - $base;
            $decimal_subtotal = number_format($base, 2, '.', '');
            $decimal_total = number_format($subtotal, 2, '.', '');
            $decimal_igv = number_format($nuevo_igv, 2, '.', ''); 
        }



        $presentaciones = presentaciones();  
        $paper_size = array(0,0,360,360);
        $pdf = PDF::loadview('compras.ordenes.reportes.detalle',[
            'orden' => $orden,
            'nombre_completo' => $nombre_completo,
            'detalles' => $detalles,
            'presentaciones' => $presentaciones,
            'subtotal' => $decimal_subtotal,
            'moneda' => $tipo_moneda,
            'igv' => $decimal_igv,
            'total' => $decimal_total,
            ])->setPaper('a4')->setWarnings(false);
        
        Mail::send('email.ordencompra',compact("orden"), function ($mail) use ($pdf,$orden) {
            $mail->to($orden->proveedor->correo);
            $mail->subject('ORDEN DE COMPRA OC-00'.$orden->id);
            $mail->attachdata($pdf->output(), 'ORDEN DE COMPRA OC-00'.$orden->id.'.pdf');
        });


        //Registrar en correos enviados
        $correo = new Enviado();
        $correo->orden_id = $orden->id;
        $correo->enviado = '1';
        $correo->usuario = Auth::user()->usuario;
        $correo->save();

        //Añadir check
        $orden->enviado = '1';
        $orden->update();


        Session::flash('success','Orden de Compra enviado al correo '.$orden->proveedor->correo);
        return redirect()->route('compras.orden.show',$orden->id)->with('enviar', 'success');
        
    }

    public function concretized($id)
    {
        
        $orden = Orden::findOrFail($id);
        $orden->estado = 'CONCRETADA';
        $orden->update();

        Session::flash('success','Orden de Compra concretada.');
        return redirect()->route('compras.orden.index')->with('concretar', 'success');

    }


    public function send($id)
    {
        $enviado = Enviado::where('orden_id',$id)->orderby('created_at','DESC')->take(1)->get();;
        $coleccion = collect([]);
        foreach($enviado as $envio){
            $coleccion->push([
                'id' => $envio->id,
                'fecha' => Carbon::parse($envio->created)->format( 'd/m/Y'),
                'hora' => Carbon::parse($envio->created)->format( 'H:i:s'),
                'correo' => $envio->orden->proveedor->correo,
                'usuario' => $envio->usuario,
            ]);
        }

        return $coleccion;
    }


    



}