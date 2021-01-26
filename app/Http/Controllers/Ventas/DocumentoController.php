<?php

namespace App\Http\Controllers\Ventas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Compras\Proveedor;
use App\Compras\Articulo;
use Illuminate\Support\Facades\Validator;
use DataTables;
use Carbon\Carbon;
use Session;
use App\Compras\Orden;
use App\Compras\Documento\Documento;
use App\Compras\Documento\Detalle;
use PDF;
use DB;

use App\Mantenimiento\Empresa\Empresa;
use App\Almacenes\Producto;
use App\Ventas\Cliente;

use App\Ventas\Cotizacion;
use App\Ventas\CotizacionDetalle;

// use App\Compras\Documento\Pago\Pago;
// use App\Compras\Documento\Pago\Transferencia;

// use App\Compras\Detalle as Detalle_Orden;


class DocumentoController extends Controller
{
    public function index()
    {
        return view('ventas.documentos.index');
    }

    public function getDocument(){

        $documentos = Documento::where('estado','!=','ANULADO')->get();
        $coleccion = collect([]);
        foreach($documentos as $documento){

            
            $detalles = Detalle::where('documento_id',$documento->id)->get();
            $documento = Documento::findOrFail($documento->id); 
            $subtotal = 0; 
            $igv = '';
            $tipo_moneda = '';

            foreach($detalles as $detalle){
                $subtotal = ($detalle->cantidad * $detalle->precio) + $subtotal;
            }

            foreach(tipos_moneda() as $moneda){
                if ($moneda->descripcion == $documento->moneda) {
                    $tipo_moneda= $moneda->simbolo;
                }
            }

            if (!$documento->igv) {
                $igv = $subtotal * 0.18;
                $total = $subtotal + $igv;
                $decimal_total = number_format($total, 2, '.', ''); 
            }else{
                $calcularIgv = $documento->igv/100;
                $base = $subtotal / (1 + $calcularIgv);
                $nuevo_igv = $subtotal - $base;
                $decimal_total = number_format($subtotal, 2, '.', '');
            }

            //TIPO DE PAGO (OTROS) 
            $otros =$suma_detalle_pago = calcularMontosAcuentaDocumentos($documento->id);

            //Pagos a cuenta
            $pagos = DB::table('compra_documento_transferencia')
            ->join('compra_documentos','compra_documento_transferencia.documento_id','=','compra_documentos.id')
            ->select('compra_documento_transferencia.*','compra_documentos.moneda as moneda_orden')
            ->where('compra_documento_transferencia.documento_id','=',$documento->id)
            ->where('compra_documento_transferencia.estado','!=','ANULADO')
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


            $saldo = 0 ;
            if ($documento->tipo_pago == '1') {
                $saldo = $decimal_total - $acuenta;
            }else{
                if ($documento->tipo_pago == '0') {
                    $saldo = $decimal_total - $otros;
                }   
            }
            
            // //CALCULAR SALDO
            // $saldo = $decimal_total - $ac;

            $coleccion->push([
                'id' => $documento->id,
                'tipo' => $documento->tipo_compra,
                'tipo_pago' => $documento->tipo_pago,
                'proveedor' => $documento->proveedor->descripcion,
                'empresa' => $documento->empresa->razon_social,
                'fecha_emision' =>  Carbon::parse($documento->fecha_emision)->format( 'd/m/Y'),
                'igv' =>  $documento->igv,
                'orden_compra' =>  $documento->orden_compra,
                'subtotal' => $tipo_moneda.' '.number_format($subtotal, 2, '.', ''),
                // 'fecha_entrega' =>  Carbon::parse($documento->fecha_entrega)->format( 'd/m/Y'), 
                'estado' => $documento->estado,
                'otros' => $tipo_moneda.' '.number_format($otros, 2, '.', ''),
                'saldo' => $tipo_moneda.' '.number_format($saldo, 2, '.', ''),
                'transferencia' => $tipo_moneda.' '.number_format($acuenta, 2, '.', ''),
                'total' => $tipo_moneda.' '.number_format($decimal_total, 2, '.', ''),
            ]);
        }
  
        return DataTables::of($coleccion)->toJson();
    }

    public function create(Request $request)
    {
        
        $cotizacion = '';
        $detalles = '';
        if($request->get('cotizacion')){
            $cotizacion =  Cotizacion::findOrFail( $request->get('cotizacion') );
            $detalles = CotizacionDetalles::where('cotizacion_id', $request->get('cotizacion'))->get(); 
        }

        $empresas = Empresa::where('estado', 'ACTIVO')->get();
        $clientes = Cliente::where('estado', 'ACTIVO')->get();
        $fecha_hoy = Carbon::now()->toDateString();
        $productos = Producto::where('estado', 'ACTIVO')->get();
        
        if (empty($cotizacion)) {
            
            return view('compras.documentos.create',[
                'empresas' => $empresas,
                'clientes' => $clientes,
                'articulos' => $articulos, 
                'modos' => $modos,
                'monedas' => $monedas,
                'fecha_hoy' => $fecha_hoy,
            ]);

        }else{

            return view('compras.documentos.create',[
                'orden' => $orden,
                'empresas' => $empresas,
                'proveedores' => $proveedores,
                'articulos' => $articulos, 
                'modos' => $modos,
                'monedas' => $monedas,
                'fecha_hoy' => $fecha_hoy,
                'detalles' => $detalles
            ]);
        }
        



    }

    public function store(Request $request){
 
        $data = $request->all();
        $rules = [
            'fecha_emision'=> 'required',
            'fecha_entrega'=> 'required',
            'tipo_compra'=> 'required',
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
            'tipo_compra.required' => 'El campo Tipo es obligatorio.',
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

        $documento = new Documento();        
        $documento->fecha_emision = Carbon::createFromFormat('d/m/Y', $request->get('fecha_emision'))->format('Y-m-d');
        $documento->fecha_entrega = Carbon::createFromFormat('d/m/Y', $request->get('fecha_entrega'))->format('Y-m-d');
        $documento->empresa_id = $request->get('empresa_id');
        $documento->proveedor_id = $request->get('proveedor_id');
        $documento->modo_compra = $request->get('modo_compra');
        $documento->observacion = $request->get('observacion');
        $documento->moneda = $request->get('moneda');
        $documento->tipo_cambio = $request->get('tipo_cambio');
        $documento->usuario_id = auth()->user()->id;
        $documento->igv = $request->get('igv');
        if ($request->get('igv_check') == "on") {
            $documento->igv_check = "1";
        };

        $documento->tipo_compra = $request->get('tipo_compra');
        $documento->orden_compra = $request->get('orden_id');

        $documento->save();

        //Llenado de los articulos
        $articulosJSON = $request->get('articulos_tabla');
        $articulotabla = json_decode($articulosJSON[0]);

        foreach ($articulotabla as $articulo) {
            Detalle::create([
                'documento_id' => $documento->id,
                'articulo_id' => $articulo->articulo_id,
                'cantidad' => $articulo->cantidad,
                'precio' => $articulo->precio,
                'costo_flete' => $articulo->costo_flete,
            ]);
        }

        Session::flash('success','Documento de Compra creada.');
        return redirect()->route('compras.documento.index')->with('guardar', 'success');
    }

    public function edit($id)
    {
        $empresas = Empresa::where('estado','ACTIVO')->get();
        $detalles = Detalle::where('documento_id',$id)->get();        
        $proveedores = Proveedor::where('estado','ACTIVO')->get();
        $documento = Documento::findOrFail($id);
        $articulos = Articulo::where('estado','ACTIVO')->get();
        $presentaciones =  presentaciones();
        $fecha_hoy = Carbon::now()->toDateString();

        return view('compras.documentos.edit',[
            'empresas' => $empresas,
            'proveedores' => $proveedores,
            'documento' => $documento,
            'articulos' => $articulos, 
            'presentaciones' => $presentaciones,
            'fecha_hoy' => $fecha_hoy, 
            'detalles' => $detalles,
        ]);
    }

    public function update(Request $request, $id){
        
        // dd($request);
        $data = $request->all();
        $rules = [
            'fecha_emision'=> 'required',
            'fecha_entrega'=> 'required',
            'tipo_compra'=> 'required',
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
            'tipo_compra.required' => 'El campo Tipo es obligatorio.',
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

        $documento = Documento::findOrFail($id);        
        $documento->fecha_emision = Carbon::createFromFormat('d/m/Y', $request->get('fecha_emision'))->format('Y-m-d');
        $documento->fecha_entrega = Carbon::createFromFormat('d/m/Y', $request->get('fecha_entrega'))->format('Y-m-d');
        $documento->empresa_id = $request->get('empresa_id');
        $documento->proveedor_id = $request->get('proveedor_id');
        $documento->modo_compra = $request->get('modo_compra');
        $documento->observacion = $request->get('observacion');
        $documento->moneda = $request->get('moneda');
        $documento->tipo_cambio = $request->get('tipo_cambio');
        $documento->usuario_id = auth()->user()->id;
        
        if ($request->get('igv_check') == "on") {
            $documento->igv_check = "1";
            $documento->igv = $request->get('igv');
        }else{
            $documento->igv_check = '';
            $documento->igv = '';
            
        }
        $documento->tipo_compra = $request->get('tipo_compra');
        $documento->update();

        $articulosJSON = $request->get('articulos_tabla');
        $articulotabla = json_decode($articulosJSON[0]);

        
        if ($articulotabla) {
            $detalles = Detalle::where('documento_id', $documento->id)->get();
            foreach ($detalles as $detalle) {
                $detalle->delete();
            }
            foreach ($articulotabla as $articulo) {
                
                Detalle::create([
                    'documento_id' => $documento->id,
                    'articulo_id' => $articulo->articulo_id,
                    'cantidad' => $articulo->cantidad,
                    'precio' => $articulo->precio,
                    'costo_flete' => $articulo->costo_flete,
                ]);
            }
        }
        
        Session::flash('success','Documento de Compra modificada.');
        return redirect()->route('compras.documento.index')->with('modificar', 'success');
    }


    public function destroy($id)
    {
        
        $documento = Documento::findOrFail($id);
        $documento->estado = 'ANULADO';
        $documento->update();

        Session::flash('success','Documento de Compra eliminada.');
        return redirect()->route('compras.documento.index')->with('eliminar', 'success');

    }

    public function show($id)
    {
        $documento = Documento::findOrFail($id);
        $nombre_completo = $documento->usuario->empleado->persona->apellido_paterno.' '.$documento->usuario->empleado->persona->apellido_materno.' '.$documento->usuario->empleado->persona->nombres;
        $detalles = Detalle::where('documento_id',$id)->get(); 
        $presentaciones = presentaciones(); 
        $subtotal = 0; 
        $igv = '';
        $tipo_moneda = '';
   
        
        foreach($detalles as $detalle){
            $subtotal = ($detalle->cantidad * $detalle->precio) + $subtotal;
        }


        foreach(tipos_moneda() as $moneda){
            if ($moneda->descripcion == $documento->moneda) {
                $tipo_moneda= $moneda->simbolo;
            }
        }


        if (!$documento->igv) {
               $igv = $subtotal * 0.18;
               $total = $subtotal + $igv;
               $decimal_subtotal = number_format($subtotal, 2, '.', '');
               $decimal_total = number_format($total, 2, '.', '');
               $decimal_igv = number_format($igv, 2, '.', ''); 
        }else{
            $calcularIgv = $documento->igv/100;
            $base = $subtotal / (1 + $calcularIgv);
            $nuevo_igv = $subtotal - $base;
            $decimal_subtotal = number_format($base, 2, '.', '');
            $decimal_total = number_format($subtotal, 2, '.', '');
            $decimal_igv = number_format($nuevo_igv, 2, '.', ''); 
        }

 
        
        return view('compras.documentos.show', [
            'documento' => $documento,
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
        $documento = Documento::findOrFail($id);
        $nombre_completo = $documento->usuario->empleado->persona->apellido_paterno.' '.$documento->usuario->empleado->persona->apellido_materno.' '.$documento->usuario->empleado->persona->nombres;
        $detalles = Detalle::where('documento_id',$id)->get();
        $subtotal = 0; 
        $igv = '';
        $tipo_moneda = '';
        foreach($detalles as $detalle){
            $subtotal = ($detalle->cantidad * $detalle->precio) + $subtotal;
        }
        foreach(tipos_moneda() as $moneda){
            if ($moneda->descripcion == $documento->moneda) {
                $tipo_moneda= $moneda->simbolo;
            }
        }


        if (!$documento->igv) {
            $igv = $subtotal * 0.18;
            $total = $subtotal + $igv;
            $decimal_subtotal = number_format($subtotal, 2, '.', '');
            $decimal_total = number_format($total, 2, '.', '');
            $decimal_igv = number_format($igv, 2, '.', ''); 
        }else{
            $calcularIgv = $documento->igv/100;
            $base = $subtotal / (1 + $calcularIgv);
            $nuevo_igv = $subtotal - $base;
            $decimal_subtotal = number_format($base, 2, '.', '');
            $decimal_total = number_format($subtotal, 2, '.', '');
            $decimal_igv = number_format($nuevo_igv, 2, '.', ''); 
        }



        $presentaciones = presentaciones();  
        $paper_size = array(0,0,360,360);
        $pdf = PDF::loadview('compras.documentos.reportes.detalle',[
            'documento' => $documento,
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

    public function TypePay($id)
    {
        //ANULAR TIPO DE PAGO OTROS
        $pagos = Pago::where('documento_id',$id)->get();

        foreach ($pagos as $pago) {
            $pago->estado = 'ANULADO';
            $pago->update();
        }


        //ANULAR TIPO TRANSFERENCIAS
        $transferencias = Transferencia::where('documento_id',$id)->get();
        foreach ($transferencias as $transferencia) {
            $transferencia->estado = 'ANULADO';
            $transferencia->update();
        }


        
        //TIPO DE DOCUMENTO
        $documento = Documento::findOrFail($id);
        $documento->tipo_pago = '';
        $documento->estado = 'ACTIVO';
        $documento->update();

        Session::flash('success','Tipo de pagos anulados, puede crear nuevo pago.');
        return redirect()->route('compras.documento.index')->with('exitosa', 'success');
    }



}