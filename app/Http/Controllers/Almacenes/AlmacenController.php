<?php

namespace App\Http\Controllers\Almacenes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Almacenes\Almacen;
use DataTables;
use Carbon\Carbon;
use Session;
use Illuminate\Support\Facades\Validator;
use DB;
class AlmacenController extends Controller
{
    public function index()
    {
        return view('almacenes.almacen.index');
    }
    public function getRepository(){
        return datatables()->query(
            DB::table('almacenes')
            ->select('almacenes.*', 
            DB::raw('DATE_FORMAT(created_at, "%d/%m/%Y") as creado'),
            DB::raw('DATE_FORMAT(updated_at, "%d/%m/%Y") as actualizado')
            )->where('almacenes.estado','ACTIVO')
        )->toJson();
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
