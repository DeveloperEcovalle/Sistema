<?php

namespace App\Http\Controllers\Produccion;

use App\Http\Controllers\Controller;
use App\Produccion\Familia;
use App\Produccion\SubFamilia;
use Illuminate\Http\Request;
use DB;
use DataTables;
use Carbon\Carbon;
use Session;
use Illuminate\Support\Facades\Validator;

class SubFamiliaController extends Controller
{
 
    public function index()
    {
        $familias = Familia::where('estado','ACTIVO')->get();
        return view('produccion.subfamilias.index',[
            'familias' => $familias
        ]);
    }

    public function getSub(){

        $subfamilias = DB::table('subfamilias')
        ->join('familias','subfamilias.familia_id','=','familias.id')
        ->select('familias.familia as nombre_familia','familias.id as familia_id','subfamilias.*')
        ->where('subfamilias.estado','!=',"ANULADO")
        ->get();
        $coleccion = collect([]);
        foreach($subfamilias as $subfamilia){
            $coleccion->push([
                'id' => $subfamilia->id,
                'familia_id' => $subfamilia->familia_id,
                'familia' => $subfamilia->nombre_familia,
                'descripcion' => $subfamilia->descripcion,
                'creado' =>  Carbon::parse($subfamilia->created_at)->format( 'd/m/Y'),
                'actualizado' =>  Carbon::parse($subfamilia->updated_at)->format( 'd/m/Y'),
                'estado' => $subfamilia->estado,
            ]);
        }
        return DataTables::of($coleccion)->toJson();
    }

    public function store(Request $request){
        
        $data = $request->all();

        $rules = [
            'descripcion' => 'required',
            'familia_id' => 'required',
        ];
        
        $message = [
            'descripcion.required' => 'El campo Descripción es obligatorio.',
            'familia_id.required' => 'El campo Familia es obligatorio.',
        ];

        Validator::make($data, $rules, $message)->validate();

        $subfamilia = new SubFamilia();
        $subfamilia->descripcion = $request->get('descripcion');
        $subfamilia->familia_id = $request->get('familia_id');
        $subfamilia->save();

        Session::flash('success','Sub familia creada.');
        return redirect()->route('produccion.subfamilia.index')->with('guardar', 'success');
    }

    public function update(Request $request){
        
        $data = $request->all();

        $rules = [
            'familia_id_editar' => 'required',
            'descripcion_editar' => 'required',
        ];
        
        $message = [
            'descripcion_editar.required' => 'El campo Descripción es obligatorio.',
        ];

        Validator::make($data, $rules, $message)->validate();
        
        $subfamilia = SubFamilia::findOrFail($request->get('sub_familia_id_editar'));
        $subfamilia->descripcion = $request->get('descripcion_editar');
        $subfamilia->familia_id = $request->get('familia_id_editar');
        $subfamilia->update();

        Session::flash('success','Sub familia modificada.');
        return redirect()->route('produccion.subfamilia.index')->with('modificar', 'success');
    }

    
    public function destroy($id)
    {
        
        $subfamilia = SubFamilia::findOrFail($id);
        $subfamilia->estado = 'ANULADO';
        $subfamilia->update();

        Session::flash('success','Sub Familia eliminado.');
        return redirect()->route('produccion.subfamilia.index')->with('eliminar', 'success');

    }

    public function getFamilia(){

        $familias = DB::table('familias')   
        ->select('familias.*')
        ->where('familias.estado','!=',"ANULADO")
        ->get();

        return $familias;
    }

}
