<?php

namespace App\Http\Controllers\Compras;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Compras\Orden;
use App\Compras\Pago;
use App\Compras\Detalle;
use App\Compras\Proveedor;
use DataTables;
use Carbon\Carbon;
use DB;
use Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\RequiredIf;

class PagoController extends Controller
{
    public function index($id)
    {
        $orden = Orden::findOrFail($id);
        foreach(tipos_moneda() as $moneda){
            if ($moneda->descripcion == $orden->moneda) {
                $tipo_moneda= $moneda->simbolo;
            }
        }
        //MONTO DE LA ORDEN DE COMPRA (ORDEN - DETALLE)
        $montoTotal = calcularMonto($id);
        
        //PAGOS A LA  ORDEN
        $pagos = DB::table('pagos')
        ->select('pagos.*')
        ->where('pagos.orden_id','=',$id)
        ->where('pagos.estado','!=','ANULADO')
        ->get();

        //SUMA DE TODOS LOS PAGOS DE (ORDEN A SOLES) EN SOLES DE ESA ORDEN
        $soles = 0;
        foreach($pagos as $pago){

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
        
        //TOTAL DE LA ORDEN DE COMPRA A SOLES 
        $total_cambio = 0;
        if ($orden->moneda != "SOLES") {
            $total_cambio= $montoTotal * $orden->tipo_cambio;    
        }else{
            $total_cambio = $montoTotal;
        }

        //SALDO EN RESTANTE EN SOLES
        $saldo_restante_soles = $total_cambio - $soles;

        //CALCULAR LA SUMA DE TODOS LOS PAGOS
        $pagos = DB::table('pagos')
        ->join('ordenes','pagos.orden_id','=','ordenes.id')
        ->select('pagos.*','ordenes.moneda as moneda_orden')
        ->where('pagos.orden_id','=',$id)
        ->where('pagos.estado','!=','ANULADO')
        ->get();

        // MONTOS ACUENTA EN LA MONEDA DE LA ORDEN
        $acuenta = 0;
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
        }

        // //CALCULAR SALDO
        $saldo = $montoTotal - $acuenta;

        if ($saldo < 1) {
            $orden->estado = "PAGADA";
            $orden->update();
        }
        
        return view('compras.ordenes.pagos.index',[
            'orden' => $orden,
            'monto' => $montoTotal,
            'moneda' => $tipo_moneda,
            'saldo' => number_format($saldo, 2, '.', ''),
            'acuenta' =>  number_format($acuenta, 2, '.', ''),
            'total_soles' => number_format($total_cambio, 2, '.', ''),
            'saldo_soles' => number_format($saldo_restante_soles, 2, '.', ''),
            'acuenta_soles' => number_format($soles, 2, '.', '')
        ]);
    }
    
    public function getPay($id)
    {
        $pagos = DB::table('pagos')
        ->join('ordenes','pagos.orden_id','=','ordenes.id')
        ->join('bancos', 'pagos.id_banco_proveedor', '=', 'bancos.id')
        // ->join('orden', 'pagos.orden_id', '=', 'ordenes.id')
        ->select('pagos.*','bancos.descripcion as banco', 'ordenes.moneda as moneda_orden')
        ->where('pagos.orden_id','=',$id)
        ->where('pagos.estado','!=','ANULADO')
        ->get();
        $coleccion = collect([]);

            foreach($pagos as $pago){
                foreach(tipos_moneda() as $moneda){
                    if ($moneda->descripcion == $pago->moneda) {
                        $tipo_moneda= $moneda->simbolo;
                    }
                }

                //PAGO EN SOLES
                $soles = "";

                if ($pago->moneda_empresa != "SOLES" && $pago->moneda_proveedor != "SOLES") {
                    $soles = $pago->tipo_cambio_soles*$pago->monto;
                }else{
                    if ($pago->moneda_empresa == "SOLES") {
                        $soles = $pago->monto;
                    }else{
                        if ($pago->moneda_proveedor == "SOLES") {
                            $soles = $pago->tc_banco*$pago->monto;
                        }else{
                            $soles = $pago->tipo_cambio_soles*$pago->monto;
                        }
                    }

                }


                //MONTO ENTREGADO AL PROVEEDOR
                $monto_entregado = '';
                if ($pago->moneda_orden == $pago->moneda_empresa && $pago->moneda_orden == $pago->moneda_proveedor ) {
                    $monto_entregado = $pago->monto;
                }else{
    
                    if ($pago->moneda_empresa == $pago->moneda_proveedor ) {
                        if ($pago->moneda_orden != 'SOLES') {
                            $monto_entregado = ($pago->monto/$pago->tc_dia);
                        }else{
                            $monto_entregado = ($pago->monto*$pago->tc_dia);
                        }
                        
                    }else{
                        if ($pago->moneda_orden == $pago->moneda_empresa) {
                            $monto_entregado = $pago->monto;
                        }else{
                                if ($pago->moneda_empresa == 'SOLES') {
                                    $monto_entregado = ($pago->monto/$pago->tc_banco);
                                }else{
                                    $monto_entregado = ($pago->monto*$pago->tc_banco);
                                }
                        }
                    }
    
     
                }

                //SIMBOLO DEL PROVEEDOR
                $simbolo_proveedor = simbolo_monedas($pago->moneda_orden);
                // dd($simbolo_proveedor);

                if ($pago->estado == "ACTIVO") {
                    $coleccion->push([
                        'id' => $pago->id,
                        'fecha_pago' => Carbon::parse($pago->fecha_pago)->format( 'd/m/Y'),
                        'entidad'=> $pago->banco,
                        'cuenta_proveedor' => $pago->moneda_proveedor,
                        'cuenta_empresa' => $pago->moneda_empresa,
                        'monto' => $simbolo_proveedor.' '.number_format($monto_entregado,2,'.',''),
                        'monto_soles' => 'S/. '.number_format($soles,2,'.',''),
                        ]);
                }
    

            }
            
        
        return DataTables::of($coleccion)->toJson();   
    }

    public function create($id)
    {
        $orden = Orden::findOrFail($id);
        $fecha_hoy = Carbon::now()->toDateString();
        $monedas =  tipos_moneda();
        $bancos = bancos();
        $bancos_proveedor = collect([]);
        $bancos_empresa = collect([]);
        foreach($orden->proveedor->bancos as $moneda_bancos){
                $bancos_proveedor->push([
                    'id' => $moneda_bancos->id,
                    'descripcion'=> $moneda_bancos->descripcion,
                    'tipo_moneda' => $moneda_bancos->tipo_moneda,
                    'num_cuenta'=> $moneda_bancos->num_cuenta,
                    'cci'=> $moneda_bancos->cci,
                    'estado'=> $moneda_bancos->estado,
                ]);
        }

        foreach($orden->empresa->bancos as $moneda_bancos){
            $bancos_empresa->push([
                'id' => $moneda_bancos->id,
                'descripcion'=> $moneda_bancos->descripcion,
                'tipo_moneda' => $moneda_bancos->tipo_moneda,
                'num_cuenta'=> $moneda_bancos->num_cuenta,
                'cci'=> $moneda_bancos->cci,
                'estado'=> $moneda_bancos->estado,
            ]);
        }

        $pagos = DB::table('pagos')
        ->join('ordenes','pagos.orden_id','=','ordenes.id')
        ->select('pagos.*','ordenes.moneda as moneda_orden')
        ->where('pagos.orden_id','=',$id)
        ->where('pagos.estado','!=','ANULADO')
        ->get();
        
        // TOTAL DE PAGOS EN SU MONEDA DE LA ORDEN
        $acuenta = 0;
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
        }
     
        $monto = calcularMonto($orden->id);
        $montoRestate = $monto - $acuenta;
        
        return view('compras.ordenes.pagos.create',[
            'orden' => $orden,
            'bancos' => $bancos,
            'fecha_hoy' => $fecha_hoy,
            'monedas' => $monedas,
            'bancos_proveedor' => $bancos_proveedor,
            'bancos_empresa' => $bancos_empresa,
            'monto_restante' =>  number_format($montoRestate, 2, '.', ''),
            'monto' =>  number_format($monto, 2, '.', '')
        ]);
    }




    public function store(Request $request)
    {
       
        $data = $request->all();

        $rules = [
  
            'id_entidad' => 'required',
            'id_entidad_empresa' => 'required',
            'id_orden' => 'required',
            'archivo' => 'required|mimetypes:application/pdf,image/jpeg,image/png,image/jpg|max:40000',
            'fecha_pago' => 'required',
            'monto' => 'required|numeric',
            'moneda' => 'required',
            'observacion' => 'nullable',

        ];
        
        $message = [
            'id_entidad.required' => 'Seleccionar una entidad bancaria.',
            'archivo.mimetypes' => 'El campo Archivo no contiene el formato correcto.',
            'archivo.required' => 'El campo Archivo es obligatorio.',
            'archivo.max' => 'El tamaño máximo del Archivo para cargar es de 40 MB.',

            'fecha_pago.required'=> 'El campo Fecha de Pago es obligatorio.',

            'monto.numeric'=> 'El campo Monto debe se numérico.',
            'monto.required'=> 'El campo Monto es obligatorio.',
            
            'moneda.required'=> 'El campo Moneda es obligatorio.',

            'tipo_cambio.required' => 'El campo Tipo de Cambio es obligatorio.',
            'tipo_cambio.numeric' => 'El campo Tipo de Cambio debe ser numérico.',

            'cambio.required' => 'El campo Cambio es obligatorio.',
            'cambio.numeric' => 'El campo Cambio debe ser numérico.',
            

        ];

        Validator::make($data, $rules, $message)->validate();

        $pago = new Pago();
        $pago->orden_id = $request->get('id_orden');
        $pago->id_banco_proveedor = $request->get('id_entidad');
        $pago->id_banco_empresa = $request->get('id_entidad_empresa');
        
        $pago->moneda_empresa = $request->get('moneda_empresa_pago');
        $pago->moneda_proveedor = $request->get('moneda_proveedor_pago');

        $pago->fecha_pago = Carbon::createFromFormat('d/m/Y', $request->get('fecha_pago'))->format('Y-m-d');
        $pago->monto =  $request->get('monto');
        $pago->moneda =  $request->get('moneda');

        $pago->tipo_cambio_soles =  $request->get('tipo_cambio_soles');
        $pago->tc_dia =  $request->get('tc_dia');
        $pago->tc_banco =  $request->get('tc_empresa_proveedor');

        $pago->observacion =  $request->get('observacion');

        if($request->hasFile('archivo')){                
            $file = $request->file('archivo');
            $name = $file->getClientOriginalName();
            $pago->nombre_archivo = $name;
            $pago->ruta_archivo = $request->file('archivo')->store('public/ordenes/pagos');
        }
        $pago->save();

        Session::flash('success','Pago creado.');
        return redirect()->route('compras.pago.index',$request->get('id_orden'))->with('guardar', 'success');
        
      

    }

    public function destroy(Request $request)
    {        
        $pago = Pago::findOrFail($request->get('pago'));
        $pago->estado = 'ANULADO';
        $pago->update();

        Session::flash('success','Pago eliminado.');
        return redirect()->route('compras.pago.index', $request->get('amp;orden'))->with('eliminar', 'success');

    }

    public function show(Request $request)
    {                
        $pago = DB::table('pagos')
        ->join('bancos', 'pagos.id_banco_proveedor', '=', 'bancos.id')
        ->join('banco_empresas', 'pagos.id_banco_empresa', '=', 'banco_empresas.id')
        ->select('pagos.*','bancos.*','bancos.id as banco_proveedor_id', 'pagos.id as id_pago', 'banco_empresas.id as banco_empresa_id','banco_empresas.descripcion as descripcion_empresa','banco_empresas.tipo_moneda as moneda_empresa','banco_empresas.num_cuenta as cuenta_empresa','banco_empresas.cci as cci_empresa')
        ->where('pagos.id','=',$request->get('pago'))
        ->where('pagos.estado','=','ACTIVO')
        ->get();
        
        foreach(tipos_moneda() as $moneda){
            if ($moneda->descripcion == $pago[0]->moneda) {
                $tipo_moneda= $moneda->simbolo;
            }
        }
        $orden = Orden::findOrFail($request->get('orden') );
        return view('compras.ordenes.pagos.show',[
            'pago' => $pago,
            'orden' => $orden,
            'tipo_moneda' => $tipo_moneda
        ]);

    }
}
