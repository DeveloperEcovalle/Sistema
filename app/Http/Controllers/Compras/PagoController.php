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
        $montoTotal = calcularMonto($id);
        
        $pagos = DB::table('pagos')
        ->select('pagos.*')
        ->where('pagos.orden_id','=',$id)
        ->where('pagos.estado','!=','ANULADO')
        ->get();

        $total = 0;
        foreach($pagos as $pago){
            $total = $total + $pago->monto;
        }

        $soles = 0;
        $cambio = 0;
        foreach($pagos as $pago){
            $cambio = $pago->monto * $pago->tipo_cambio;
            $soles = $soles+$cambio;
        }

        // CALCULAR ACUENTA EN MONEDA
        $acuenta = 0;
        foreach($pagos as $pago){
            $acuenta = $acuenta + $pago->monto;
        }

        //CALCULAR SALDO
        $saldo = $montoTotal - $total;

        
        
        
        return view('compras.ordenes.pagos.index',[
            'orden' => $orden,
            'monto' => $montoTotal,
            'moneda' => $tipo_moneda,
            'saldo' => number_format($saldo, 2, '.', ''),
            'total' => number_format($total, 2, '.', ''),
            'soles' => number_format($soles, 2, '.', '')
        ]);
    }
    
    public function getPay($id)
    {
        $pagos = DB::table('pagos')
        ->join('bancos', 'pagos.banco', '=', 'bancos.id')
        ->select('pagos.*','bancos.descripcion as banco')
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

                if ($pago->estado == "ACTIVO") {
                    $coleccion->push([
                        'id' => $pago->id,
                        'fecha_pago' => Carbon::parse($pago->fecha_pago)->format( 'd/m/Y'),
                        'entidad'=> $pago->banco,
                        'monto' => $tipo_moneda.' '.$pago->monto,
                        'monto_soles' => 'S/. '.number_format($pago->tipo_cambio * $pago->monto,2,'.',''),
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
        foreach($orden->proveedor->bancos as $moneda_bancos){
            if ($moneda_bancos->tipo_moneda == $orden->moneda) {
                $bancos_proveedor->push([
                    'id' => $moneda_bancos->id,
                    'descripcion'=> $moneda_bancos->descripcion,
                    'tipo_moneda' => $moneda_bancos->tipo_moneda,
                    'num_cuenta'=> $moneda_bancos->num_cuenta,
                    'cci'=> $moneda_bancos->cci,
                    'estado'=> $moneda_bancos->estado,
                ]);
            }

            
        }

        $pagos = DB::table('pagos')
        ->select('pagos.*')
        ->where('pagos.orden_id','=',$id)
        ->where('pagos.estado','!=','ANULADO')
        ->get();

        $total = 0;
        foreach($pagos as $pago){
            $total = $total + $pago->monto;
        }

        $monto = calcularMonto($orden->id);
        
        $montoRestate = $monto - $total;
        
        return view('compras.ordenes.pagos.create',[
            'orden' => $orden,
            'bancos' => $bancos,
            'fecha_hoy' => $fecha_hoy,
            'monedas' => $monedas,
            'bancos_proveedor' => $bancos_proveedor,
            'monto_restante' =>  number_format($montoRestate, 2, '.', ''),
            'monto' =>  number_format($monto, 2, '.', '')
        ]);
    }




    public function store(Request $request)
    {
       
        $data = $request->all();

        $rules = [
  
            'id_entidad' => 'required',
            'archivo' => 'required|mimetypes:application/pdf,image/jpeg,image/png,image/jpg|max:40000',
            
            'fecha_pago' => 'required',
            'monto' => 'required|numeric',
            'moneda' => 'required',

            'tipo_cambio' => [
                new RequiredIf($request->input('moneda') != 'SOLES'),
                'numeric',
            ],
            'cambio' => [
                new RequiredIf($request->input('moneda') != 'SOLES'),
                'numeric',
            ],
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
        $pago->banco = $request->get('id_entidad');
        $pago->orden_id = $request->get('id_orden');
        $pago->fecha_pago = Carbon::createFromFormat('d/m/Y', $request->get('fecha_pago'))->format('Y-m-d');
        $pago->monto =  $request->get('monto');
        $pago->moneda =  $request->get('moneda');
    
        if ($request->get('tipo_cambio')) {
            $pago->tipo_cambio =  $request->get('tipo_cambio');    
        }else{
            $pago->tipo_cambio =  1;
        }

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

    public function edit(Request $request)
    {
        // dd($request);
        $orden = Orden::findOrFail($request->get('orden') );
        $fecha_hoy = Carbon::now()->toDateString();
        $monedas =  tipos_moneda();
        $bancos = bancos();
        $bancos_proveedor = collect([]);
        foreach($orden->proveedor->bancos as $moneda_bancos){
            if ($moneda_bancos->tipo_moneda == $orden->moneda) {
                $bancos_proveedor->push([
                    'id' => $moneda_bancos->id,
                    'descripcion'=> $moneda_bancos->descripcion,
                    'tipo_moneda' => $moneda_bancos->tipo_moneda,
                    'num_cuenta'=> $moneda_bancos->num_cuenta,
                    'cci'=> $moneda_bancos->cci,
                    'estado'=> $moneda_bancos->estado,
                ]);
            }

            
        }
        $pagos = DB::table('pagos')
        ->select('pagos.*', 'pagos.id as id_pago')
        ->where('pagos.orden_id','=',$request->get('orden'))
        ->where('pagos.estado','!=','ANULADO')
        ->get();

        $total = 0;
        foreach($pagos as $pago){
            $total = $total + $pago->monto;
        }

        $monto = calcularMonto($orden->id);
        
        $montoRestate = $monto - $total;


        $pago = DB::table('pagos')
        ->join('bancos','pagos.banco','=','bancos.id')
        ->select('pagos.*','bancos.*','bancos.id as banco_id', 'pagos.id as id_pago')
        ->where('pagos.id','=',$request->get('pago'))
        ->where('pagos.estado','=','ACTIVO')
        ->get();

        
        return view('compras.ordenes.pagos.edit',[
            'bancos' => $bancos,
            'fecha_hoy' => $fecha_hoy,
            'monedas' => $monedas,
            'pago' => $pago,
            'orden' => $orden,
            'bancos_proveedor' => $bancos_proveedor,
            'monto_restante' =>  number_format($montoRestate, 2, '.', ''),
            'monto' =>  number_format($monto, 2, '.', '')
        ]);
    }

    public function update(Request $request,$id)
    {
        $data = $request->all();

        $rules = [
  
            'id_entidad' => 'required',
            'archivo' => 'nullable|mimetypes:application/pdf,image/jpeg,image/png,image/jpg|max:40000',
            
            'fecha_pago' => 'required',
            'monto' => 'required|numeric',
            'moneda' => 'required',
            
            'tipo_cambio' => [
                new RequiredIf($request->input('moneda') != 'SOLES'),
                'numeric',
            ],
            'cambio' => [
                new RequiredIf($request->input('moneda') != 'SOLES'),
                'numeric',
            ],
            'observacion' => 'nullable',

        ];
        
        $message = [
            'id_entidad.required' => 'El campo Entidad es obligatorio.',
            'archivo.mimetypes' => 'El campo Archivo no contiene el formato correcto.',
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

        $pago = Pago::findOrFail($id);
        $pago->banco = $request->get('id_entidad');
        $pago->fecha_pago = Carbon::createFromFormat('d/m/Y', $request->get('fecha_pago'))->format('Y-m-d');
        $pago->monto =  $request->get('monto');
        $pago->moneda =  $request->get('moneda');

        if ($request->get('tipo_cambio')) {
            $pago->tipo_cambio =  $request->get('tipo_cambio');    
        }else{
            $pago->tipo_cambio =  1;
        }

        $pago->observacion =  $request->get('observacion');

        if($request->hasFile('archivo')){      
            //Eliminar Archivo anterior
            Storage::delete($pago->ruta_archivo);
            //Agregar             
            $file = $request->file('archivo');
            $name = $file->getClientOriginalName();
            $pago->nombre_archivo = $name;
            $pago->ruta_archivo = $request->file('archivo')->store('public/ordenes/pagos');
        }
        $pago->save();

        Session::flash('success','Pago modificado.');
        return redirect()->route('compras.pago.index',$request->get('id_orden'))->with('modificar', 'success');
      

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
        ->join('bancos','pagos.banco','=','bancos.id')
        ->select('pagos.*','bancos.*','bancos.id as banco_id','pagos.id as id_pago')
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
