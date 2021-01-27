<?php

namespace App\Http\Controllers\Almacenes;

use App\Almacenes\Familia;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use DataTables;
use Carbon\Carbon;
use Session;
use Illuminate\Support\Facades\Validator;

class FamiliaController extends Controller
{

    public function index()
    {
        
        return view('almacenes.familias.index');
    }
        public function getfamilia(){
            $familias = Familia::where('estado','ACTIVO')->get();
            $coleccion = collect([]);
            foreach($familias as $familia){
                $coleccion->push([
                    'id' => $familia->id,
                    'familia' => $familia->familia,
                    'fecha_creacion' =>  Carbon::parse($familia->created_at)->format( 'd/m/Y'),
                    'fecha_actualizacion' =>  Carbon::parse($familia->updated_at)->format( 'd/m/Y'),
                    'estado' => $familia->estado,
                ]);
            }
            return DataTables::of($coleccion)->toJson();
    }

    public function store(Request $request){
        
        $data = $request->all();

        $rules = [
            'familia_guardar' => 'required',
            
        ];
        
        $message = [
            'familia_guardar.required' => 'El campo Familia es obligatorio.',
            
        ];

        Validator::make($data, $rules, $message)->validate();

        $familia = new Familia();
        $familia->familia = $request->get('familia_guardar');
        $familia->save();

        Session::flash('success','Familia creado.');
        return redirect()->route('almacenes.familias.index')->with('guardar', 'success');
    }

    public function update(Request $request){
       
        $data = $request->all();

        $rules = [
            'tabla_id' => 'required',
            'familia' => 'required',
            
        ];
        
        $message = [
            'familia.required' => 'El campo Familia es obligatorio.',
            
        ];

        Validator::make($data, $rules, $message)->validate();
        
        $familia = Familia::findOrFail( $request->get('tabla_id'));
        $familia->familia = $request->get('familia');
        $familia->update();

        Session::flash('success','Familia modificado.');
        return redirect()->route('almacenes.familias.index')->with('modificar', 'success');
    }

    
    public function destroy($id)
    {
        
        $familia = Familia::findOrFail($id);
        $familia->estado = 'ANULADO';
        $familia->update();

        Session::flash('success','Familia eliminado.');
        return redirect()->route('almacenes.familias.index')->with('eliminar', 'success');

    }
}


