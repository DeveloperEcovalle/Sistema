<?php

namespace App\Http\Controllers\Ventas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Collection;
use DataTables;
use Carbon\Carbon;
use Session;
use PDF;
use DB;
use App\Mantenimiento\Empresa\Empresa;
use App\Mantenimiento\Empresa\Numeracion;
use App\Almacenes\Producto;
use App\Ventas\Cliente;
use App\Ventas\Cotizacion;
use App\Ventas\CotizacionDetalle;
use App\Ventas\Documento\Documento;
use App\ventas\Documento\Detalle;

use App\Ventas\Documento\Pago\PagoDetalle as PivotPago;
use App\Ventas\Documento\Pago\Pago as PagoOtros;
use App\Ventas\Documento\Pago\Transferencia;

use App\Events\VentaRegistrada;
use App\Events\DocumentoNumeracion;
use App\Events\ComprobanteRegistrado;
use App\Mantenimiento\Tabla\Detalle as TablaDetalle;
use App\Almacenes\LoteProducto;
use App\Almacenes\LoteDetalle;

//CONVERTIR DE NUMEROS A LETRAS
use Luecano\NumeroALetras\NumeroALetras;

class DocumentoController extends Controller
{
    public function index()
    {
        return view('ventas.documentos.index');
    }

    public function getDocument(){

        $documentos = Documento::where('estado','!=','ANULADO')->orderBy('id', 'desc')->get();
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
                'tipo_venta' => $documento->nombreTipo(),
                'tipo_pago' => $documento->tipo_pago,
                'cliente' => $documento->tipo_documento_cliente.': '.$documento->documento_cliente.' - '.$documento->cliente,
                'empresa' => $documento->empresa,
                'cotizacion_venta' =>  $documento->cotizacion_venta,
                'fecha_documento' =>  Carbon::parse($documento->fecha_documento)->format( 'd/m/Y'),
                'estado' => $documento->estado,
                'sunat' => $documento->sunat,
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
        $empresas = Empresa::where('estado', 'ACTIVO')->get();
        $clientes = Cliente::where('estado', 'ACTIVO')->get();
        $fecha_hoy = Carbon::now()->toDateString();
        $productos = Producto::where('estado', 'ACTIVO')->get();

        $cotizacion = '';
        $detalles = '';
        if($request->get('cotizacion')){
            //COLECCION DE ERRORES
            $errores = collect();
            $devolucion = false;
            $cotizacion =  Cotizacion::findOrFail( $request->get('cotizacion') );
            $detalles = CotizacionDetalle::where('cotizacion_id', $request->get('cotizacion'))->get(); 
            $lotes = self::cotizacionLote($detalles);
            //COMPROBACION DE LOTES SI LAS CANTIDADES ENVIADAS SON IGUALES A LAS SOLICITADAS
            $nuevoDetalle = collect();
            foreach ($detalles as $detalle) {
                $cantidadDetalle = $lotes->where('producto',$detalle->producto_id)->sum('cantidad');
                if($cantidadDetalle != $detalle->cantidad){
                    $devolucion = true; 
                    $devolucionLotes = $lotes->where('producto',$detalle->producto_id)->first();
                    //LLENAR ERROR CANTIDAD SOLICITADA MAYOR AL STOCK
                    $coll = new Collection();
                    $coll->producto = $devolucionLotes->descripcion_producto;
                    $coll->cantidad = $detalle->cantidad;
                    $errores->push($coll);
                    self::devolverCantidad($lotes->where('producto',$detalle->producto_id));
                }else{
                    $nuevoSindevoluciones = $lotes->where('producto',$detalle->producto_id);
                    foreach ($nuevoSindevoluciones as $devolucion) {
                        $coll = new Collection();
                        $coll->producto_id = $devolucion->producto_id;
                        $coll->cantidad = $devolucion->cantidad;
                        $coll->precio = $devolucion->precio;
                        $coll->unidad = $devolucion->unidad;
                        $coll->descripcion_producto = $devolucion->descripcion_producto;
                        $coll->precio = $devolucion->precio;
                        $coll->importe = $devolucion->importe;
                        $coll->presentacion = $devolucion->presentacion;
                        $coll->producto = $devolucion->producto;
                        $nuevoDetalle->push( $coll);
                    }

                }
            }

            return view('ventas.documentos.create',[
                'cotizacion' => $cotizacion,
                'empresas' => $empresas,
                'clientes' => $clientes,
                'productos' => $productos,  
                'lotes' => $nuevoDetalle,
                'errores' => $errores,
            ]);

        }
        
        if (empty($cotizacion)) {
            return view('ventas.documentos.create',[
                'empresas' => $empresas,
                'clientes' => $clientes,
                'productos' => $productos, 
                'fecha_hoy' => $fecha_hoy,
            ]);
        }
    }

    public function devolverCantidad($devoluciones)
    {
        foreach ($devoluciones as $devolucion) {
            $lote = LoteProducto::findOrFail($devolucion->producto_id);
            $lote->cantidad_logica = $lote->cantidad_logica + $devolucion->cantidad;
            $lote->cantidad =  $lote->cantidad_logica;
            $lote->estado = '1';
            $lote->update();
        }
    }

    public function cotizacionLote($detalles)
    {       
        $nuevoDetalle = collect();
        foreach ($detalles as $detalle) {
            $lotes = LoteProducto::where('producto_id',$detalle->producto_id)
                    ->where('estado','1')
                    ->where('cantidad_logica','>',0)
                    ->orderBy('fecha_vencimiento', 'asc')
                    ->get();
            //INICIO CON LA CANTIDAD DEL DETALLE
            $cantidadSolicitada = $detalle->cantidad;
       
            foreach ($lotes as $lote) {
                //SE OBTUVO LA CANTIDAD SOLICITADA DEL LOTE
                if ($cantidadSolicitada > 0) {
                    //CANTIDAD LOGICA DEL LOTE ES IGUAL A LA CANTIDAD SOLICITADA
                    $cantidadLogica = $lote->cantidad_logica;
                    if ($cantidadLogica == $cantidadSolicitada) {
                        //CREAMOS EL NUEVO DETALLE
                        $coll = new Collection();
                        $coll->producto_id = $lote->id;
                        $coll->cantidad = $lote->cantidad_logica;
                        $coll->precio = $detalle->precio;
                        $coll->unidad = $lote->producto->medidaCompleta();
                        $coll->descripcion_producto= $lote->producto->nombre.' - '.$lote->codigo;
                        $coll->precio = $detalle->precio;
                        $coll->importe = $detalle->importe;
                        $coll->presentacion = $lote->producto->medida;
                        $coll->producto = $lote->producto->id;
                        $nuevoDetalle->push( $coll);
                        //ACTUALIZAMOS EL LOTE
                        $lote->cantidad_logica = $lote->cantidad_logica - $cantidadSolicitada;
                        //REDUCIMOS LA CANTIDAD SOLICITADA
                        $cantidadSolicitada = 0;
                        $lote->update(); 
                    }else{
                       
                        if ($lote->cantidad_logica < $cantidadSolicitada) {
                            //CREAMOS EL NUEVO DETALLE
                            $coll = new Collection();
                            $coll->producto_id = $lote->id;
                            $coll->cantidad = $lote->cantidad_logica;
                            $coll->precio = $detalle->precio;
                            $coll->unidad = $lote->producto->medidaCompleta();
                            $coll->descripcion_producto= $lote->producto->nombre.' - '.$lote->codigo;
                            $coll->precio = $detalle->precio;
                            $coll->importe = $detalle->importe;
                            $coll->presentacion = $lote->producto->medida;
                            $coll->producto = $lote->producto->id;
                            $nuevoDetalle->push( $coll);
                            //REDUCIMOS LA CANTIDAD SOLICITADA
                            $cantidadSolicitada = $cantidadSolicitada - $lote->cantidad_logica;
                            //ACTUALIZAMOS EL LOTE
                            $lote->cantidad_logica = 0;
                            $lote->update(); 
                        }else{
                            if ($lote->cantidad_logica > $cantidadSolicitada) {
                                 //CREAMOS EL NUEVO DETALLE
                                $coll = new Collection();
                                $coll->producto_id = $lote->id;
                                $coll->cantidad = $cantidadSolicitada ;
                                $coll->precio = $detalle->precio;
                                $coll->unidad = $lote->producto->medidaCompleta();
                                $coll->descripcion_producto= $lote->producto->nombre.' - '.$lote->codigo;
                                $coll->precio = $detalle->precio;
                                $coll->importe = $detalle->importe;
                                $coll->presentacion = $lote->producto->medida;
                                $coll->producto = $lote->producto->id;
                                $nuevoDetalle->push( $coll);
                                //ACTUALIZAMOS EL LOTE
                                $lote->cantidad_logica = $lote->cantidad_logica - $cantidadSolicitada;
                                //REDUCIMOS LA CANTIDAD SOLICITADA
                                $cantidadSolicitada = 0;
                                $lote->update(); 
                            }
                            
                        }

                    }

                }

            }
        }

       
        return $nuevoDetalle;
  
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
        //EMPRESA
        $empresa = Empresa::findOrFail($request->get('empresa_id'));
        $documento->ruc_empresa =  $empresa->ruc;
        $documento->empresa =  $empresa->razon_social;
        $documento->direccion_fiscal_empresa =  $empresa->direccion_fiscal;
        $documento->empresa_id = $request->get('empresa_id'); //OBTENER NUMERACION DE LA EMPRESA 
        //CLIENTE
        $cliente = Cliente::findOrFail($request->get('cliente_id'));
       
        $documento->tipo_documento_cliente =  $cliente->tipo_documento;
        $documento->documento_cliente =  $cliente->documento;
        $documento->direccion_cliente =  $cliente->direccion;
        $documento->cliente =  $cliente->nombre;
        $documento->cliente_id = $request->get('cliente_id'); //OBTENER TIENDA DEL CLIENTE

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
    
        $documento->cotizacion_venta = $request->get('cotizacion_id');
        $documento->save();

        //Llenado de los articulos
        $productosJSON = $request->get('productos_tabla');
        $productotabla = json_decode($productosJSON[0]);

        foreach ($productotabla as $producto) {
            $lote = LoteProducto::findOrFail($producto->producto_id);
            Detalle::create([
                'documento_id' => $documento->id,
                'lote_id' => $producto->producto_id, //LOTE
                'codigo_producto' => $lote->producto->codigo, 
                'unidad' => $lote->producto->getMedida(), 
                'nombre_producto' => $lote->producto->nombre, 
                'codigo_lote' => $lote->codigo,
                'cantidad' => $producto->cantidad,
                'precio' => $producto->precio,
                'importe' => $producto->total,
            ]);

            $lote->cantidad =  $lote->cantidad - $producto->cantidad;
            $lote->update();
        }



        // event(new VentaRegistrada($documento));


        //Registro de actividad
        $descripcion = "SE AGREGÓ EL DOCUMENTO DE VENTA CON LA FECHA: ". Carbon::parse($documento->fecha_documento)->format('d/m/y');
        $gestion = "DOCUMENTO DE VENTA";
        crearRegistro($documento , $descripcion , $gestion);
        

        Session::flash('success','Documento de Venta creada.');
        return redirect()->route('ventas.documento.index')->with('guardar', 'success');
    }

    public function destroy($id)
    {
        
        $documento = Documento::findOrFail($id);
        $documento->estado = 'ANULADO';
        $documento->update();

        $detalles = Detalle::where('documento_id',$id)->get();
        foreach ($detalles as $detalle) {
            //ANULAMOS EL DETALLE
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

    public function obtenerLeyenda($documento)
    {
        $formatter = new NumeroALetras();
        $convertir = $formatter->toInvoice($documento->total, 2, 'SOLES');

        //CREAR LEYENDA DEL COMPROBANTE
        $arrayLeyenda = Array();
        $arrayLeyenda[] = array(  
            "code" => "1000",
            "value" => $convertir
        );
        return $arrayLeyenda;
    }

    public function obtenerProductos($id)
    {
        $detalles = Detalle::where('documento_id',$id)->get();
        $arrayProductos = Array();
        for($i = 0; $i < count($detalles); $i++){

            $arrayProductos[] = array(
                "codProducto" => $detalles[$i]->codigo_producto,
                "unidad" => $detalles[$i]->unidad,
                "descripcion"=> $detalles[$i]->nombre_producto.' - '.$detalles[$i]->codigo_lote,
                "cantidad" => $detalles[$i]->cantidad,
                "mtoValorUnitario" => $detalles[$i]->precio / 1.18,
                "mtoValorVenta" => ($detalles[$i]->precio / 1.18) * $detalles[$i]->cantidad,
                "mtoBaseIgv" => ($detalles[$i]->precio / 1.18) * $detalles[$i]->cantidad, 
                "porcentajeIgv" => 18,
                "igv" => ($detalles[$i]->precio - ($detalles[$i]->precio / 1.18 )) * $detalles[$i]->cantidad,
                "tipAfeIgv" => 10,
                "totalImpuestos" =>  ($detalles[$i]->precio - ($detalles[$i]->precio / 1.18 )) * $detalles[$i]->cantidad,
                "mtoPrecioUnitario" => $detalles[$i]->precio

            );
        }

        return $arrayProductos;
    }

    public function obtenerFecha($documento)
    {
        $date = strtotime($documento->fecha_documento);
        $fecha_emision = date('Y-m-d', $date); 
        $hora_emision = date('H:i:s', $date); 
        $fecha = $fecha_emision.'T'.$hora_emision.'-05:00';

        return $fecha;
    }

    public function voucher($id)
    {
      
        $documento = Documento::findOrFail($id);
        if ($documento->sunat == '0' || $documento->sunat == '2' ) {

            //ARREGLO COMPROBANTE
            $arreglo_comprobante = array(
                "tipoOperacion" => $documento->tipoOperacion(),
                "tipoDoc"=> $documento->tipoDocumento(),
                "serie" => '000',
                "correlativo" => '000',
                "fechaEmision" => self::obtenerFecha($documento),
                "observacion" => $documento->observacion,
                "tipoMoneda" => $documento->simboloMoneda(),
                "client" => array(
                    "tipoDoc" => $documento->tipoDocumentoCliente(),
                    "numDoc" => $documento->documento_cliente,
                    "rznSocial" => $documento->cliente,
                    "address" => array(
                        "direccion" => $documento->direccion_cliente,
                    )),
                "company" => array(
                    "ruc" =>  $documento->ruc_empresa,
                    "razonSocial" => $documento->empresa,
                    "address" => array(
                        "direccion" => $documento->direccion_fiscal_empresa,
                    )),
                "mtoOperGravadas" => $documento->sub_total,
                "mtoOperExoneradas" => 0,
                "mtoIGV" => $documento->total_igv,
                
                "valorVenta" => $documento->sub_total,
                "totalImpuestos" => $documento->total_igv,
                "mtoImpVenta" => $documento->total ,
                "ublVersion" => "2.1",
                "details" => self::obtenerProductos($documento->id),
                "legends" =>  self::obtenerLeyenda($documento),
            );

            $comprobante= json_encode($arreglo_comprobante);
            $data = generarComprobanteapi($comprobante, $documento->empresa_id);
            $name = $documento->id.'.pdf';
            $pathToFile = storage_path('app'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'comprobantes'.DIRECTORY_SEPARATOR.$name);
            if(!file_exists(storage_path('app'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'comprobantes'))) {
                mkdir(storage_path('app'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'comprobantes'));
            }
            file_put_contents($pathToFile, $data);
            return response()->file($pathToFile);

        }else{
          
            //OBTENER CORRELATIVO DEL COMPROBANTE ELECTRONICO
            $comprobante = event(new ComprobanteRegistrado($documento,$documento->serie));
            //ENVIAR COMPROBANTE PARA LUEGO GENERAR PDF
            $data = generarComprobanteapi($comprobante[0],$documento->empresa_id);
            $name = $documento->id.'.pdf';
            $pathToFile = storage_path('app'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'comprobantes'.DIRECTORY_SEPARATOR.$name);
            if(!file_exists(storage_path('app'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'comprobantes'))) {
                mkdir(storage_path('app'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'comprobantes'));
            }
            file_put_contents($pathToFile, $data);
            return response()->file($pathToFile);
        }



    }

    public function vouchersAvaible(Request $request)
    {
        $data = $request->all();
        $empresa_id = $data['empresa_id'];
        $tipo = $data['tipo_id'];
        $detalle = TablaDetalle::findOrFail($tipo);
        $empresa = Empresa::findOrFail($empresa_id);
        $resultado = (Numeracion::where('empresa_id',$empresa_id)->where('estado','ACTIVO')->where('tipo_comprobante',$tipo))->exists();
      
        $enviar = [
                    'existe' => ($resultado == true) ? true : false,
                    'comprobante' => $detalle->descripcion,
                    'empresa' => $empresa->razon_social,
                ];

        return  response()->json($enviar); 
        
        
    }

    public function customers(Request $request)
    {
        $data = $request->all();
        $tipo = $data['tipo_id'];
        $pun_tipo = '';
        
        if ($tipo=='131') {
            $clientes = Cliente::where('estado','!=','ANULADO')
            ->where('tipo_documento','RUC')
            ->get();
            $pun_tipo = '1';
        }else{
            $clientes = Cliente::where('estado','!=','ANULADO')
            ->where('tipo_documento','!=','RUC')
            ->get();
            $pun_tipo = '0';
        }
      
        $enviar = [
                    'clientes' => $clientes,
                    'tipo' => $pun_tipo,
                ];

        return  response()->json($enviar); 
        
        
    }

    //LOTES PARA BUSQUEDA
    public function getLot($tipo_cliente)
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

    //CAMBIAR CANTIDAD LOGICA DEL LOTE 
    public function quantity(Request $request)
    {
        $data = $request->all();
        $producto_id = $data['producto_id'];
        $cantidad = $data['cantidad'];
        $condicion = $data['condicion'];
        $mensaje = '';
        $lote = LoteProducto::findOrFail($producto_id);
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

    //DEVOLVER CANTIDAD LOGICA AL CERRAR VENTANA
    public function returnQuantity(Request $request)
    {
        $data = $request->all();
        $cantidades = $data['cantidades'];
        $productosJSON = $cantidades;
        $productotabla = json_decode($productosJSON);
        $mensaje = '';
        foreach ($productotabla as $detalle) {
            //DEVOLVEMOS CANTIDAD AL LOTE Y AL LOTE LOGICO
            $lote = LoteProducto::findOrFail($detalle->producto_id);
            $lote->cantidad_logica = $lote->cantidad_logica + $detalle->cantidad;
            $lote->cantidad =  $lote->cantidad_logica;
            $lote->estado = '1';
            $lote->update();
            $mensaje = 'Cantidad devuelta';
        };

        return $mensaje;
    
    }


}
