<?php

namespace App\Http\Controllers\Mantenimiento\Tabla;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mantenimiento\Tabla\Detalle;
use App\Mantenimiento\Tabla\General;
use DataTables;
use Carbon\Carbon;
use Session;
use Illuminate\Support\Facades\Validator;

class DetalleController extends Controller
{
    public function index($id)
    {
        $tabla = General::findOrFail($id);
        return view('mantenimiento.tablas.detalle.index', ['tabla' => $tabla]);
    }

    public function getTable($id){
        $tablas = Detalle::where('tabla_id', $id)->where('estado','!=','ANULADO')->get();
        $coleccion = collect([]);
        foreach($tablas as $tabla){
            $coleccion->push([
                'id' => $tabla->id,
                'descripcion' => $tabla->descripcion,
                'simbolo' => $tabla->simbolo,
                'fecha_creacion' =>  Carbon::parse($tabla->created_at)->format( 'd/m/Y'),
                'fecha_actualizacion' =>  Carbon::parse($tabla->updated_at)->format( 'd/m/Y'),
                'estado' => $tabla->estado,
            ]);
        }
        return DataTables::of($coleccion)->toJson();
    }

    public function destroy($id)
    {
        
        $detalle = Detalle::findOrFail($id);
        $detalle->estado = 'ANULADO';
        $detalle->update();

        Session::flash('success','Detalle eliminado.');
        return redirect()->route('mantenimiento.tabla.detalle.index',$detalle->tabla_id);

    }

    public function store(Request $request){
        
        $data = $request->all();

        $rules = [
            'tabla_id' => 'required',
            'descripcion_guardar' => 'required',
            'simbolo_guardar' => 'required'
        ];
        
        $message = [
            'descripcion_guardar.required' => 'El campo Descripción es obligatorio.',
            'simbolo_guardar.required' => 'El campo Simbolo es obligatorio.',
        ];

        Validator::make($data, $rules, $message)->validate();

        $detalle = new Detalle();
        $detalle->tabla_id = $request->get('tabla_id');
        $detalle->descripcion = $request->get('descripcion_guardar');
        $detalle->simbolo = $request->get('simbolo_guardar');
        $detalle->save();

        Session::flash('success','Detalle creado.');
        return redirect()->route('mantenimiento.tabla.detalle.index',$detalle->tabla_id);
    }

    public function update(Request $request){
        
        $data = $request->all();

        $rules = [
            'tabla_id' => 'required',
            'descripcion' => 'required',
            'simbolo' => 'required'
        ];
        
        $message = [
            'descripcion.required' => 'El campo Descripción es obligatorio.',
            'simbolo.required' => 'El campo Simbolo es obligatorio.',
        ];

        Validator::make($data, $rules, $message)->validate();
        
        $detalle = Detalle::findOrFail($request->get('tabla_id'));
        $detalle->descripcion = $request->get('descripcion');
        $detalle->simbolo = $request->get('simbolo');
        $detalle->update();

        Session::flash('success','Detalle modificado.');
        return redirect()->route('mantenimiento.tabla.detalle.index',$detalle->tabla_id);
    }

}
