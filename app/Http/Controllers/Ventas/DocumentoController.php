<?php

namespace App\Http\Controllers\Ventas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use DataTables;
use Carbon\Carbon;
use Session;
use PDF;
use DB;
use App\Mantenimiento\Empresa\Empresa;
use App\Almacenes\Producto;
use App\Ventas\Cliente;

use App\Ventas\Cotizacion;
use App\Ventas\CotizacionDetalle;

use App\ventas\Documento\Documento;
use App\ventas\Documento\Detalle;

use App\Ventas\Documento\Pago\PagoDetalle as PivotPago;
use App\Ventas\Documento\Pago\Pago as PagoOtros;
use App\Ventas\Documento\Pago\Transferencia;

//CONVERTIR DE NUMEROS A LETRAS
use Luecano\NumeroALetras\NumeroALetras;

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

            $saldo = 0;
            $acuenta = 0;

            //TIPO DE PAGO (OTROS) 
            $otros = calcularMontosAcuentaVentas($documento->id);
            $acuenta = calcularMontosAcuentaVentasTransferencia($documento->id);

            
            if ($documento->tipo_pago == '1') {
                $saldo = $documento->total - $acuenta;
            }else{
                if ($documento->tipo_pago == '0') {
                    $saldo = $documento->total - $otros;
                }   
            }

            $coleccion->push([
                'id' => $documento->id,
                'tipo_venta' => $documento->tipo_venta,
                'tipo_pago' => $documento->tipo_pago,
                'cliente' => $documento->cliente->nombre,
                'cotizacion_venta' =>  $documento->cotizacion_venta,
                'fecha_documento' =>  Carbon::parse($documento->fecha_documento)->format( 'd/m/Y'),
                'estado' => $documento->estado,
                
                'otros' => 'S/. '.number_format($otros, 2, '.', ''),
                'saldo' => 'S/. '.number_format($saldo, 2, '.', ''),
                'transferencia' => 'S/. '.number_format($acuenta, 2, '.', ''),
                'total' => 'S/. '.number_format($documento->total, 2, '.', ''),
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
            $detalles = CotizacionDetalle::where('cotizacion_id', $request->get('cotizacion'))->get(); 
        }

        $empresas = Empresa::where('estado', 'ACTIVO')->get();
        $clientes = Cliente::where('estado', 'ACTIVO')->get();
        $fecha_hoy = Carbon::now()->toDateString();
        $productos = Producto::where('estado', 'ACTIVO')->get();
        
        if (empty($cotizacion)) {
            
            return view('ventas.documentos.create',[
                'empresas' => $empresas,
                'clientes' => $clientes,
                'productos' => $productos, 

                'fecha_hoy' => $fecha_hoy,
            ]);

        }else{
            
            
            return view('ventas.documentos.create',[
                'cotizacion' => $cotizacion,
                'empresas' => $empresas,
                'clientes' => $clientes,
                'productos' => $productos,  
                'detalles' => $detalles
            ]);
        }
        



    }

    public function store(Request $request){
     
        $data = $request->all();
        $rules = [
            'fecha_documento'=> 'required',
            'fecha_atencion_campo'=> 'required',
            'tipo_venta'=> 'required',
            'empresa_id'=> 'required',
            'cliente_id'=> 'required',
            'observacion' => 'nullable',
            'igv' => 'required_if:igv_check,==,on|numeric|digits_between:1,3',
            
        ];
        $message = [
            'fecha_documento.required' => 'El campo Fecha de Emisión es obligatorio.',
            'tipo_venta.required' => 'El campo Tipo es obligatorio.',
            'fecha_atencion_campo.required' => 'El campo Fecha de Entrega es obligatorio.',
            'empresa_id.required' => 'El campo Empresa es obligatorio.',
            'cliente_id.required' => 'El campo Cliente es obligatorio.',
            'igv.required_if' => 'El campo Igv es obligatorio.',
            'igv.digits' => 'El campo Igv puede contener hasta 3 dígitos.',
            'igv.numeric' => 'El campo Igv debe se numérico.',


        ];
        Validator::make($data, $rules, $message)->validate();

        $documento = new Documento();        
        $documento->fecha_documento = Carbon::createFromFormat('d/m/Y', $request->get('fecha_documento'))->format('Y-m-d');
        $documento->fecha_atencion = Carbon::createFromFormat('d/m/Y', $request->get('fecha_atencion_campo'))->format('Y-m-d');
        $documento->empresa_id = $request->get('empresa_id');
        $documento->cliente_id = $request->get('cliente_id');
        $documento->tipo_venta = $request->get('tipo_venta');
        $documento->observacion = $request->get('observacion');
        $documento->user_id = auth()->user()->id;
        $documento->sub_total = $request->get('monto_sub_total');
        $documento->total_igv = $request->get('monto_total_igv');
        $documento->total = $request->get('monto_total');
        $documento->igv = $request->get('igv');
        $documento->moneda = 4;

        if ($request->get('igv_check') == "on") {
            $documento->igv_check = "1";
        };
        


 

        // $arrayLeyenda = Array();
        // $arrayLeyenda[] = array(  
        //     "code" => "1000",
        //     "value" => "SON TRESCIENTOS TREINTA Y SEIS CON OO/100 SOLES"
        // );
    
        // //ARREGLO COMPROBANTE
        // $arreglo_comprobante = array(
        //     "tipoOperacion" => "10",
        //     "tipoDoc"=> "01",
        //     "serie" => "F001",
        //     "correlativo" => "123",
        //     "fechaEmision" => "2019-10-27T00:00:00-05:00",
        //     "tipoMoneda" => "PEN",
        //     "client" => array(
        //         "tipoDoc" => "6",
        //         "numDoc" => $documento->cliente->documento,
        //         "rznSocial" => $documento->cliente->nombre,
        //         "address" => array(
        //             "direccion" => $documento->cliente->direccion,
        //         )),
        //     "company" => array(
        //         "ruc" =>  $documento->empresa->ruc,
        //         "razonSocial" => $documento->empresa->razon_social,
        //         "address" => array(
        //             "direccion" => $documento->empresa->direccion_fiscal,
        //         )),
        //     "mtoOperGravadas" => 200,
        //     "mtoOperExoneradas" => 100,
        //     "mtoIGV" => 36,
        //     "valorVenta" => 300,
        //     "totalImpuestos" => 36,
        //     "mtoImpVenta" => 336,
        //     "ublVersion" => "2.1",
        //     "details" => $arrayDetalle ,
        //     "legends" =>  $arrayLeyenda,
        // );
        
        // dd(json_encode($arreglo_comprobante));
        // agregarComprobanteapi($empresa);


        $documento->cotizacion_venta = $request->get('cotizacion_id');
        $documento->save();

        //Llenado de los articulos
        $productosJSON = $request->get('productos_tabla');
        $productotabla = json_decode($productosJSON[0]);

        foreach ($productotabla as $producto) {
            Detalle::create([
                'documento_id' => $documento->id,
                'producto_id' => $producto->producto_id,
                'cantidad' => $producto->cantidad,
                'precio' => $producto->precio,
                'importe' => $producto->total,
            ]);
        }


        //Registro de actividad
        $descripcion = "SE AGREGÓ EL DOCUMENTO DE VENTA CON LA FECHA: ". Carbon::parse($documento->fecha_documento)->format('d/m/y');
        $gestion = "DOCUMENTO DE VENTA";
        crearRegistro($documento , $descripcion , $gestion);
        

        Session::flash('success','Documento de Venta creada.');
        return redirect()->route('ventas.documento.index')->with('guardar', 'success');
    }

    public function edit($id)
    {

        $empresas = Empresa::where('estado','ACTIVO')->get();
        $documento =  Documento::findOrFail($id);
        $detalles = Detalle::where('documento_id', $id)->get();   
        $clientes = Cliente::where('estado', 'ACTIVO')->get();
        $productos = Producto::where('estado', 'ACTIVO')->get();
        
        return view('ventas.documentos.edit',[

            'documento' => $documento,
            'detalles' => $detalles,
            'empresas' => $empresas,
            'clientes' => $clientes,
            'productos' => $productos,

        ]);
    }

    public function update(Request $request, $id){

        $data = $request->all();
        $rules = [
            'fecha_documento_campo'=> 'required',
            'fecha_atencion_campo'=> 'required',
            'tipo_venta'=> 'required',
            'empresa_id'=> 'required',
            'cliente_id'=> 'required',
            'observacion' => 'nullable',
            'igv' => 'required_if:igv_check,==,on|numeric|digits_between:1,3',
        ];
        $message = [
            'fecha_documento_campo.required' => 'El campo Fecha de Emisión es obligatorio.',
            'tipo_venta.required' => 'El campo Tipo es obligatorio.',
            'fecha_atencion_campo.required' => 'El campo Fecha de Entrega es obligatorio.',
            'empresa_id.required' => 'El campo Empresa es obligatorio.',
            'cliente_id.required' => 'El campo Cliente es obligatorio.',

            'igv.required_if' => 'El campo Igv es obligatorio.',
            'igv.digits' => 'El campo Igv puede contener hasta 3 dígitos.',
            'igv.numeric' => 'El campo Igv debe se numérico.',
            'tipo_cambio.numeric' => 'El campo Tipo de Cambio debe se numérico.',

        ];
        Validator::make($data, $rules, $message)->validate();

        $documento = Documento::findOrFail($id);              
        $documento->fecha_documento = Carbon::createFromFormat('d/m/Y', $request->get('fecha_documento_campo'))->format('Y-m-d');
        $documento->fecha_atencion = Carbon::createFromFormat('d/m/Y', $request->get('fecha_atencion_campo'))->format('Y-m-d');
        $documento->empresa_id = $request->get('empresa_id');
        $documento->cliente_id = $request->get('cliente_id');
        $documento->tipo_venta = $request->get('tipo_venta');
        $documento->observacion = $request->get('observacion');
        $documento->user_id = auth()->user()->id;
        $documento->sub_total = $request->get('monto_sub_total');
        $documento->total_igv = $request->get('monto_total_igv');
        $documento->total = $request->get('monto_total');
        $documento->moneda = 4;

        if ($request->get('igv_check') == "on") {
            $documento->igv_check = "1";
            $documento->igv = $request->get('igv');
        }else{
            $documento->igv_check = '';
            $documento->igv = '';
            
        }

        $documento->cotizacion_venta = $request->get('cotizacion_id');
        $documento->update();

        //Llenado de los articulos
        $productosJSON = $request->get('productos_tabla');
        $productotabla = json_decode($productosJSON[0]);

        if ($productotabla) {
            $detalles = Detalle::where('documento_id', $id)->get();
            foreach ($detalles as $detalle) {
                $detalle->delete();
            }

            foreach ($productotabla as $producto) {
                Detalle::create([
                    'documento_id' => $documento->id,
                    'producto_id' => $producto->producto_id,
                    'cantidad' => $producto->cantidad,
                    'precio' => $producto->precio,
                    'importe' => $producto->total,
                ]);
            }
        }


        //Registro de actividad
        $descripcion = "SE MODIFICÓ EL DOCUMENTO DE VENTA CON LA FECHA: ". Carbon::parse($documento->fecha_documento)->format('d/m/y');
        $gestion = "DOCUMENTO DE VENTA";
        modificarRegistro($documento , $descripcion , $gestion);
        
        Session::flash('success','Documento de Venta modificada.');
        return redirect()->route('ventas.documento.index')->with('modificar', 'success');
    }

    public function destroy($id)
    {
        
        $documento = Documento::findOrFail($id);
        $documento->estado = 'ANULADO';
        $documento->update();

        $detalles = Detalle::where('documento_id',$id)->get();

        foreach ($detalles as $detalle) {
            $detalle->estado = "ANULADO";
            $detalle->update();
        }

        //Registro de actividad
        $descripcion = "SE ELIMINÓ EL DOCUMENTO DE VENTA CON LA FECHA: ". Carbon::parse($documento->fecha_documento)->format('d/m/y');
        $gestion = "DOCUMENTO DE VENTA";
        eliminarRegistro($documento, $descripcion , $gestion);

        Session::flash('success','Documento de Venta eliminada.');
        return redirect()->route('ventas.documento.index')->with('eliminar', 'success');

    }

    public function show($id)
    {
        $documento = Documento::findOrFail($id);
        $nombre_completo = $documento->user->empleado->persona->apellido_paterno.' '.$documento->user->empleado->persona->apellido_materno.' '.$documento->user->empleado->persona->nombres;
        $detalles = Detalle::where('documento_id',$id)->get(); 
        //TOTAL EN LETRAS
        $formatter = new NumeroALetras();
        $convertir = $formatter->toInvoice($documento->total, 2, 'SOLES');
        
        
        return view('ventas.documentos.show', [
            'documento' => $documento,
            'detalles' => $detalles,
            'nombre_completo' => $nombre_completo,
            'cadena_valor' => $convertir
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

        DB::table('cotizacion_documento_pago_detalle_cajas')
            ->join('cotizacion_documento_pagos','cotizacion_documento_pagos.id','=','cotizacion_documento_pago_detalle_cajas.pago_id')
            ->join('cotizacion_documento_pago_cajas','cotizacion_documento_pago_cajas.id','=','cotizacion_documento_pago_detalle_cajas.caja_id')
            ->select('cotizacion_documento_pago_cajas.*','cotizacion_documento_pagos.*')
            ->where('cotizacion_documento_pagos.documento_id','=',$id)
            // //ANULAR
            ->where('cotizacion_documento_pagos.estado','!=','ANULADO')
            ->update(['cotizacion_documento_pago_cajas.estado' => 'ANULADO']);



        //ANULAR TIPO TRANSFERENCIAS
        $transferencias = Transferencia::where('documento_id',$id)->get();
        foreach ($transferencias as $transferencia) {
            $transferencia->estado = 'ANULADO';
            $transferencia->update();
        }


        
        //TIPO DE DOCUMENTO
        $documento = Documento::findOrFail($id);
        $documento->tipo_pago = null;
        $documento->estado = 'PENDIENTE';
        $documento->update();

        Session::flash('success','Tipo de pagos anulados, puede crear nuevo pago.');
        return redirect()->route('ventas.documento.index')->with('exitosa', 'success');
    }

    public function voucher($id)
    {
        $documento = Documento::findOrFail($id);
        $nombre_completo = $documento->user->empleado->persona->apellido_paterno.' '.$documento->user->empleado->persona->apellido_materno.' '.$documento->user->empleado->persona->nombres;
        $detalles = Detalle::where('documento_id',$id)->get();
        //TOTAL EN LETRAS
        $formatter = new NumeroALetras();
        $convertir = $formatter->toInvoice($documento->total, 2, 'SOLES');

        $arrayDetalle = Array();
        for($i = 0; $i < count($detalles); $i++){
            $arrayDetalle[] = array(
                "codProducto" => $detalles[$i]->producto->codigo,
                "unidad" => $detalles[$i]->producto->getMedida(),
                "descripcion"=> $detalles[$i]->producto->nombre,
                "cantidad" => $detalles[$i]->cantidad,
                "mtoValorUnitario" => $detalles[$i]->precio,
                "mtoValorVenta" => $detalles[$i]->importe,
                "mtoBaseIgv" => $detalles[$i]->importe,
                "porcentajeIgv" => 18,
                "igv" => 18,
                "tipAfeIgv" => 10,
                "totalImpuestos" => 18,
                "mtoPrecioUnitario" => $detalles[$i]->precio + 18
            );
        }

        //Leyenda
        $arrayLeyenda = Array();
        $arrayLeyenda[] = array(  
            "code" => "1000",
            "value" => $convertir
        );
        
        $date = strtotime($documento->fecha_documento);
        $fecha_emision = date('Y-m-d', $date); 
        $hora_emision = date('H:i:s', $date); 
        $fecha = $fecha_emision.'T'.$hora_emision.'-05:00';

        //ARREGLO COMPROBANTE
        $arreglo_comprobante = array(
            "tipoOperacion" => $documento->tipoOperacion(),
            "tipoDoc"=> $documento->tipoDocumento(),
            "serie" => $documento->serie()."00".$documento->id,
            "correlativo" => "123",
            "fechaEmision" => $fecha,
            "tipoMoneda" => $documento->simboloMoneda(),
            "client" => array(
                "tipoDoc" => "6",
                "numDoc" => $documento->cliente->documento,
                "rznSocial" => $documento->cliente->nombre,
                "address" => array(
                    "direccion" => $documento->cliente->direccion,
                )),
            "company" => array(
                "ruc" =>  $documento->empresa->ruc,
                "razonSocial" => $documento->empresa->razon_social,
                "address" => array(
                    "direccion" => $documento->empresa->direccion_fiscal,
                )),
            "mtoOperGravadas" => $documento->sub_total,
            "mtoOperExoneradas" => 0, //(PREGUNTAR)
            "mtoIGV" => $documento->total_igv,
            "valorVenta" => $documento->sub_total,
            "totalImpuestos" =>  $documento->total_igv,
            "mtoImpVenta" => $documento->total,
            "ublVersion" => "2.1",
            "details" => $arrayDetalle ,
            "legends" =>  $arrayLeyenda,
        );

        // dd(json_encode($arreglo_comprobante));
        $data = generarComprobanteapi(json_encode($arreglo_comprobante));
        $name = $documento->id.'.pdf';
        $pathToFile = storage_path('app'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'comprobantes'.DIRECTORY_SEPARATOR.$name);
        
        if(!file_exists(storage_path('app'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'comprobantes'))) {
            mkdir(storage_path('app'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'comprobantes'));
        }
        file_put_contents($pathToFile, $data);
        return response()->file($pathToFile);
    }

    public function sunat($id)
    {
 
        $documento = Documento::findOrFail($id);
        // dd($documento);
        if ($documento->sunat != '1') {

            $nombre_completo = $documento->user->empleado->persona->apellido_paterno.' '.$documento->user->empleado->persona->apellido_materno.' '.$documento->user->empleado->persona->nombres;
            $detalles = Detalle::where('documento_id',$id)->get();
           
            //TOTAL EN LETRAS
            $formatter = new NumeroALetras();
            $convertir = $formatter->toInvoice($documento->total, 2, 'SOLES');

            $arrayDetalle = Array();
            for($i = 0; $i < count($detalles); $i++){
    
                $arrayDetalle[] = array(
                    "codProducto" => $detalles[$i]->producto->codigo,
                    "unidad" => $detalles[$i]->producto->getMedida(),
                    "descripcion"=> $detalles[$i]->producto->nombre,
                    "cantidad" => $detalles[$i]->cantidad,
                    "mtoValorUnitario" => $detalles[$i]->precio / 1.18,
                    "mtoValorVenta" => ($detalles[$i]->precio / 1.18) * $detalles[$i]->cantidad,
                    "mtoBaseIgv" => ($detalles[$i]->precio / 1.18) * $detalles[$i]->cantidad,
                    // "mtoBaseIgv" => ((($detalles[$i]->precio / 1.18) * $detalles[$i]->cantidad) * 1.18) - (($detalles[$i]->precio / 1.18) * $detalles[$i]->cantidad), 
                    "porcentajeIgv" => 18,
                    "igv" => ($detalles[$i]->precio - ($detalles[$i]->precio / 1.18 )) * $detalles[$i]->cantidad,
                    "tipAfeIgv" => 10,
                    "totalImpuestos" =>  ($detalles[$i]->precio - ($detalles[$i]->precio / 1.18 )) * $detalles[$i]->cantidad,
                    "mtoPrecioUnitario" => $detalles[$i]->precio

                );
            }

            //Leyenda
            $arrayLeyenda = Array();
            $arrayLeyenda[] = array(  
                "code" => "1000",
                "value" => $convertir
            );
            
            $date = strtotime($documento->fecha_documento);
            $fecha_emision = date('Y-m-d', $date); 
            $hora_emision = date('H:i:s', $date); 
            $fecha = $fecha_emision.'T'.$hora_emision.'-05:00';

            //ARREGLO COMPROBANTE
            $arreglo_comprobante = array(
                "tipoOperacion" => $documento->tipoOperacion(),
                "tipoDoc"=> $documento->tipoDocumento(),
                "serie" => $documento->serie()."00".$documento->id,
                "correlativo" => "123",
                "fechaEmision" => $fecha,
                "observacion" => $documento->observacion,
                "tipoMoneda" => $documento->simboloMoneda(),
                "client" => array(
                    "tipoDoc" => "6",
                    "numDoc" => $documento->cliente->documento,
                    "rznSocial" => $documento->cliente->nombre,
                    "address" => array(
                        "direccion" => $documento->cliente->direccion,
                    )),
                "company" => array(
                    "ruc" =>  $documento->empresa->ruc,
                    "razonSocial" => $documento->empresa->razon_social,
                    "address" => array(
                        "direccion" => $documento->empresa->direccion_fiscal,
                    )),
                "mtoOperGravadas" => $documento->sub_total,
                "mtoOperExoneradas" => 0,
                "mtoIGV" => $documento->total_igv,
                
                "valorVenta" => $documento->sub_total,
                "totalImpuestos" => $documento->total_igv,
                "mtoImpVenta" => $documento->total ,
                "ublVersion" => "2.1",
                "details" => $arrayDetalle ,
                "legends" =>  $arrayLeyenda,
            );

            // dd(json_encode($arreglo_comprobante));
            $data = enviarComprobanteapi(json_encode($arreglo_comprobante));
            $json_sunat = json_decode($data);

            // dd($json_sunat);
            
            if ($json_sunat->sunatResponse->success == true) {

                $documento->sunat = '1';

                $data = generarComprobanteapi(json_encode($arreglo_comprobante));
                $name = $documento->serie()."00".$documento->id.'.pdf';
                // $pathToFile = storage_path('app'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'sunat'.DIRECTORY_SEPARATOR.$name);
                $pathToFile = storage_path('app'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'sunat'.DIRECTORY_SEPARATOR.$name);

                if(!file_exists(storage_path('app'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'sunat'))) {
                    mkdir(storage_path('app'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'sunat'));
                }

                // $pathToFile = 'public/sunat/'.$name;

                file_put_contents($pathToFile, $data);
                $documento->nombre_comprobante_archivo = $name;
                $documento->ruta_comprobante_archivo = 'public/sunat/'.$name;
                $documento->update(); 
                // dd($json_sunat->sunatResponse->cdrResponse->id);
                Session::flash('success','Documento de Venta enviada a Sunat con exito.');
                return view('ventas.documentos.index',[
                    
                    'id_sunat' => $json_sunat->sunatResponse->cdrResponse->id,
                    'descripcion_sunat' => $json_sunat->sunatResponse->cdrResponse->description,
                    'notas_sunat' => $json_sunat->sunatResponse->cdrResponse->notes,
                    'sunat_exito' => true

                ])->with('sunat_exito', 'success');

            }else{
                Session::flash('error','Documento de Venta sin exito en el envio a sunat.');
                return view('ventas.documentos.index',[
                    'id_sunat' => $json_sunat->sunatResponse->cdrResponse->id,
                    'descripcion_sunat' => $json_sunat->sunatResponse->cdrResponse->description,
                    'notas_sunat' => $json_sunat->sunatResponse->cdrResponse->notes,
                    'sunat_error' => true

                ])->with('sunat_error', 'error');
            }
        }else{

            Session::flash('error','Documento de venta fue enviado a Sunat.');
            return redirect()->route('ventas.documento.index')->with('sunat_existe', 'error');
        }


    }

    public function indexVouchers()
    {
        return view('ventas.comprobantes.index');
    }

    public function getVouchers(){

        $documentos = Documento::where('estado','!=','ANULADO')->where('sunat','1')->orderBy('id','DESC')->get();
        $coleccion = collect([]);
        foreach($documentos as $documento){

            $coleccion->push([
                'id' => $documento->id,
                'tipo_venta' => $documento->tipo_venta,
                'tipo_pago' => $documento->tipo_pago,
                'cliente' => $documento->cliente->nombre,
                'cotizacion_venta' =>  $documento->cotizacion_venta,
                'fecha_documento' =>  Carbon::parse($documento->fecha_documento)->format( 'd/m/Y'),
                'total' => 'S/. '.number_format($documento->total, 2, '.', ''),
                'ruta_comprobante_archivo' => $documento->ruta_comprobante_archivo,
                'nombre_comprobante_archivo' => $documento->nombre_comprobante_archivo,
            ]);
        }
  
        return DataTables::of($coleccion)->toJson();
    }



}
