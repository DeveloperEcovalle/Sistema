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
use Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class PagoController extends Controller
{
    public function index($id)
    {
        $orden = Orden::findOrFail($id);
        return view('compras.ordenes.pagos.index',[
            'orden' => $orden
        ]);
    }
    
    public function getPay($id)
    {

        $ordenes = Orden::findOrFail($id);
        $coleccion = collect([]);
       
        foreach($ordenes->proveedor->bancos as $banco){
            $tipo_moneda = '';
           

            foreach($banco->pagos as $pago){
                foreach(tipos_moneda() as $moneda){
                    if ($moneda->descripcion == $pago->moneda) {
                        $tipo_moneda= $moneda->simbolo;
                    }
                }

                if ($pago->estado == "ACTIVO") {
                    $coleccion->push([
                        'id' => $pago->id,
                        'fecha_pago' => Carbon::parse($pago->fecha_pago)->format( 'd/m/Y'),
                        'entidad'=> $pago->banco->descripcion,
                        'monto' => $tipo_moneda.' '.$pago->monto,
                    ]);
                }
    

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
        return view('compras.ordenes.pagos.create',[
            'orden' => $orden,
            'bancos' => $bancos,
            'fecha_hoy' => $fecha_hoy,
            'monedas' => $monedas,
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
            'tipo_cambio' => 'required|numeric',
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
            

        ];

        Validator::make($data, $rules, $message)->validate();

        $pago = new Pago();
        $pago->banco_id = $request->get('id_entidad');
        $pago->fecha_pago = Carbon::createFromFormat('d/m/Y', $request->get('fecha_pago'))->format('Y-m-d');
        $pago->monto =  $request->get('monto');
        $pago->moneda =  $request->get('moneda');
        $pago->tipo_cambio =  $request->get('tipo_cambio');
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
        
        $orden = Orden::findOrFail($request->get('orden') );
        $pago = Pago::findOrFail($request->get('pago'));
        $fecha_hoy = Carbon::now()->toDateString();
        $monedas =  tipos_moneda();
        $bancos = bancos();
        return view('compras.ordenes.pagos.edit',[
            'bancos' => $bancos,
            'fecha_hoy' => $fecha_hoy,
            'monedas' => $monedas,
            'pago' => $pago,
            'orden' => $orden
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
            'tipo_cambio' => 'required|numeric',
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
            

        ];

        Validator::make($data, $rules, $message)->validate();

        $pago = Pago::findOrFail($id);
        $pago->banco_id = $request->get('id_entidad');
        $pago->fecha_pago = Carbon::createFromFormat('d/m/Y', $request->get('fecha_pago'))->format('Y-m-d');
        $pago->monto =  $request->get('monto');
        $pago->moneda =  $request->get('moneda');
        $pago->tipo_cambio =  $request->get('tipo_cambio');
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
        $pago = Pago::findOrFail($request->get('pago'));
        
        foreach(tipos_moneda() as $moneda){
            if ($moneda->descripcion == $pago->moneda) {
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
