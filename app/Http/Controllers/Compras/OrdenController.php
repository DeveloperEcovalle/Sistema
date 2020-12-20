<?php

namespace App\Http\Controllers\Compras;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Compras\Orden;
use App\Compras\Detalle;
use App\Compras\Proveedor;
use App\Mantenimiento\Empresa;
use App\Compras\Articulo;
use DataTables;
use Carbon\Carbon;
use Session;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use PDF;

class OrdenController extends Controller
{
    public function index()
    {
        return view('compras.ordenes.index');
    }

    public function getOrder(){

        //Cambiar a estado pendiente si la fecha de la de hoy
        $fecha_hoy = Carbon::now();
        $ordenes = Orden::where('estado','!=','CONCRETADA')->where('estado','!=','ANULADO')->get();
        foreach($ordenes as $orden){
            if ($orden->fecha_entrega == $fecha_hoy->toDateString()) {
                $orden->estado = 'PENDIENTE';
                $orden->update();
            }else{
                $orden->estado = 'VIGENTE';
                $orden->update();
            }
        }

     

        $coleccion = collect([]);
        foreach($ordenes as $orden){
            $coleccion->push([
                'id' => $orden->id,
                'empresa' => $orden->empresa->razon_social,
                'proveedor' => $orden->proveedor->descripcion,
                'fecha_documento' =>  Carbon::parse($orden->fecha_documento)->format( 'd/m/Y'),
                'fecha_entrega' =>  Carbon::parse($orden->fecha_entrega)->format( 'd/m/Y'), 
                'estado' => $orden->estado,
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
            'fecha_documento'=> 'required',
            'fecha_entrega'=> 'required',
            'empresa_id'=> 'required',
            'proveedor_id'=> 'required',
            'modo_compra'=> 'required',
            'observacion' => 'nullable',
            'moneda' => 'nullable',
            'igv' => 'required_if:igv_check,==,on|numeric|digits_between:1,3',
            
        ];
        $message = [
            'fecha_documento.required' => 'El campo Fecha de Documento es obligatorio.',
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

        $orden = new Orden();        
        $orden->fecha_documento = Carbon::createFromFormat('d/m/Y', $request->get('fecha_documento'))->format('Y-m-d');
        $orden->fecha_entrega = Carbon::createFromFormat('d/m/Y', $request->get('fecha_entrega'))->format('Y-m-d');
        $orden->empresa_id = $request->get('empresa_id');
        $orden->proveedor_id = $request->get('proveedor_id');
        $orden->modo_compra = $request->get('modo_compra');
        $orden->observacion = $request->get('observacion');
        $orden->moneda = $request->get('moneda');
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
        // dd($request);
       
        $data = $request->all();
        $rules = [
            'fecha_documento'=> 'required',
            'fecha_entrega'=> 'required',
            'empresa_id'=> 'required',
            'proveedor_id'=> 'required',
            'modo_compra'=> 'required',
            'observacion' => 'nullable',
            'moneda' => 'nullable',
            'igv' => 'required_if:igv_check,==,on|digits_between:1,3',
        ];
        $message = [
            'fecha_documento.required' => 'El campo Fecha de Documento es obligatorio.',
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
        $orden->fecha_documento = Carbon::createFromFormat('d/m/Y', $request->get('fecha_documento'))->format('Y-m-d');
        $orden->fecha_entrega = Carbon::createFromFormat('d/m/Y', $request->get('fecha_entrega'))->format('Y-m-d');
        $orden->empresa_id = $request->get('empresa_id');
        $orden->proveedor_id = $request->get('proveedor_id');
        $orden->modo_compra = $request->get('modo_compra');
        $orden->moneda = $request->get('moneda');
        $orden->observacion = $request->get('observacion');
        $orden->igv = $request->get('igv');
        
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

        $decimal_subtotal = number_format($subtotal, 2, '.', ''); 
        
        $igv_monto = $orden->igv /100;
        $igv = $decimal_subtotal * $igv_monto;
        $total = $decimal_subtotal + $igv;
        
        $decimal_total = number_format($total, 2, '.', '');
        $decimal_igv = number_format($igv, 2, '.', ''); 
        return view('compras.ordenes.show', [
            'orden' => $orden,
            'detalles' => $detalles,
            'presentaciones' => $presentaciones,
            'subtotal' => $decimal_subtotal,
            'moneda' => $tipo_moneda,
            'igv' => $decimal_igv,
            'total' => $decimal_total,
        ]);

    }

    public function report($id)
    {
        $orden = Orden::findOrFail($id);
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

        $decimal_subtotal = number_format($subtotal, 2, '.', '');

        $igv_monto = $orden->igv /100;
        $igv = $decimal_subtotal * $igv_monto;
        $total = $decimal_subtotal + $igv;
        
        $decimal_total = number_format($total, 2, '.', '');
        $decimal_igv = number_format($igv, 2, '.', ''); 


        $presentaciones = presentaciones();  
        $paper_size = array(0,0,360,360);
        $pdf = PDF::loadview('compras.ordenes.reportes.detalle',[
            'orden' => $orden,
            'detalles' => $detalles,
            'presentaciones' => $presentaciones,
            'subtotal' => $decimal_subtotal,
            'moneda' => $tipo_moneda,
            'igv' => $decimal_igv,
            'total' => $decimal_total,
            ])->setPaper('a4')->setWarnings(false);

        return $pdf->stream();
        



    }



}