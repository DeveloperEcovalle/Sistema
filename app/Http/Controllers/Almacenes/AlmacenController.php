<?php

namespace App\Http\Controllers\Almacenes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Almacenes\Almacen;
use DataTables;
use Carbon\Carbon;
use Session;
use Illuminate\Support\Facades\Validator;

class AlmacenController extends Controller
{
    public function index()
    {
        return view('almacenes.almacen.index');
    }
    public function getRepository(){
        $almacenes = Almacen::where('estado','ACTIVO')->get();
        $coleccion = collect([]);
        foreach($almacenes as $almacen){
            $coleccion->push([
                'id' => $almacen->id,
                'descripcion' => $almacen->descripcion,
                'ubicacion' => $almacen->ubicacion,
                'fecha_creacion' =>  Carbon::parse($almacen->created_at)->format( 'd/m/Y'),
                'fecha_actualizacion' =>  Carbon::parse($almacen->updated_at)->format( 'd/m/Y'),
                'estado' => $almacen->estado,
            ]);
        }
        return DataTables::of($coleccion)->toJson();
    }
    public function store(Request $request){
        
        $data = $request->all();

        $rules = [
            'descripcion_guardar' => 'required',
            'ubicacion_guardar' => 'required',
        ];
        
        $message = [
            'descripcion_guardar.required' => 'El campo Descripción es obligatorio.',
            'ubicacion_guardar.required' => 'El campo Ubicación es obligatorio.',
        ];

        Validator::make($data, $rules, $message)->validate();

        $almacen = new Almacen();
        $almacen->descripcion = $request->get('descripcion_guardar');
        $almacen->ubicacion = $request->get('ubicacion_guardar');
        $almacen->save();

        Session::flash('success','Almacen creado.');
        return redirect()->route('almacenes.almacen.index')->with('guardar', 'success');
    }

    public function update(Request $request){
        
        $data = $request->all();

        $rules = [
            'tabla_id' => 'required',
            'descripcion' => 'required',
            'ubicacion' => 'required',
        ];
        
        $message = [
            'descripcion.required' => 'El campo Descripción es obligatorio.',
            'ubicacion.required' => 'El campo Ubicación es obligatorio.',
        ];

        Validator::make($data, $rules, $message)->validate();
        
        $almacen = Almacen::findOrFail($request->get('tabla_id'));
        $almacen->descripcion = $request->get('descripcion');
        $almacen->ubicacion = $request->get('ubicacion');
        $almacen->update();

        Session::flash('success','Almacen modificado.');
        return redirect()->route('almacenes.almacen.index')->with('modificar', 'success');
    }

    
    public function destroy($id)
    {
        
        $almacen = Almacen::findOrFail($id);
        $almacen->estado = 'ANULADO';
        $almacen->update();

        Session::flash('success','Almacen eliminado.');
        return redirect()->route('almacenes.almacen.index')->with('eliminar', 'success');

    }
}
