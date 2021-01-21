<?php

namespace App\Http\Controllers\InvDesarrollo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\InvDesarrollo\Prototipo;
use App\Produccion\Producto;
use DataTables;
use Carbon\Carbon;
use Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Input; 
use DB;

class PrototipoController extends Controller
{
       public function index()
    {
        $productos = Producto::where('estado','ACTIVO')->get();
        return view('invdesarrollo.prototipos.index',["productos"=>$productos]);
    }
    public function getPrototipo(){
        return datatables()->query(
            DB::table('prototipos')
            ->join('productos','productos.id','=','prototipos.producto_id')
            ->select('prototipos.*', 
                DB::raw('DATE_FORMAT(prototipos.created_at, "%d/%m/%Y") as creado'),
                DB::raw('DATE_FORMAT(prototipos.updated_at, "%d/%m/%Y") as actualizado'),'productos.nombre'
            )->where('prototipos.estado','ACTIVO')
        )->toJson();
    }
    
    public function store(Request $request){
        
        $data = $request->all();
        $rules = [
            'producto_id'=>'',
            'fecha_registro'=>'',
            'fecha_inicio'=>'',
            'fecha_fin'=>'',
            'linea_caja_texto_registrar'=>'',
            'imagen'=>'',
            'archivo_word'=>'',
          
        ];
        
        $message = [
            'producto_id'=>'',
            'fecha_registro'=>'',
            'fecha_inicio'=>'',
            'fecha_fin'=>'',
            'linea_caja_texto_registrar'=>'',
            'imagen'=>'',
            'archivo_word'=>'',
        ];

        Validator::make($data, $rules, $message)->validate();
        
        $prototipos=new Prototipo;
        $prototipos->producto_id = $request->get('producto_id');
        $prototipos->fecha_registro = $request->get('fecha_registro');
        $prototipos->fecha_inicio = $request->get('fecha_inicio');
        $prototipos->fecha_fin = $request->get('fecha_fin');
        $prototipos->linea_caja_texto_registrar = $request->get('linea_caja_texto_registrar');
        //$prototipos->imagen = $request->get('imagen');
        //$prototipos->archivo_word = $request->get('archivo_word');

        if($request->hasFile('imagen')){                
            $file = $request->file('imagen');
            $name = $file->getClientOriginalName();
            $prototipos->imagen = $name;
            $prototipos->ruta_imagen = $request->file('imagen')->store('public/prototipos');
        }
        if($request->hasFile('archivo_word')){                
            $file = $request->file('archivo_word');
            $name = $file->getClientOriginalName();
            $prototipos->archivo_word = $name;
            $prototipos->ruta_archivo_word = $request->file('archivo_word')->store('public/prototipos');
        }
        $prototipos->save();

        Session::flash('success','Prototipos creado.');
        return redirect()->route('invdesarrollo.prototipo.index')->with('guardar', 'success');
    }

    public function update(Request $request){
        
        $data = $request->all();
        $rules = [
            'producto_id'=>'',
            'fecha_registro'=>'',
            'fecha_inicio'=>'',
            'fecha_fin'=>'',
            'linea_caja_texto_registrar'=>'',
            'imagen'=>'image|mimetypes:image/jpeg,image/png,image/jpg|max:40000',
            'archivo_word'=>'',
            
        ];
        
        $message = [
            'producto_id'=>'',
            'fecha_registro'=>'',
            'fecha_inicio'=>'',
            'fecha_fin'=>'',
            'linea_caja_texto_registrar'=>'',
            'imagen'=>'',
            'archivo_word'=>'',
            
        ];
        Validator::make($data, $rules, $message)->validate();

        $prototipos = Prototipo::findOrFail($request->get('id'));
        $prototipos->producto_id = $request->get('producto_id');
        $prototipos->fecha_registro = $request->get('fecha_registro');
        $prototipos->fecha_inicio = $request->get('fecha_inicio');
        $prototipos->fecha_fin = $request->get('fecha_fin');
        $prototipos->linea_caja_texto_registrar = $request->get('linea_caja_texto_registrar');
        //$prototipos->imagen = $request->get('imagen');
        //$prototipos->archivo_word = $request->get('archivo_word');
        
        if($request->hasFile('imagen')){   
            //Eliminar Archivo anterior
            Storage::delete($prototipos->ruta_imagen);               
            //Agregar nuevo archivo
            $file = $request->file('imagen');
            $name = $file->getClientOriginalName();
            $prototipos->imagen = $name;
            $prototipos->ruta_imagen = $request->file('imagen')->store('public/prototipos');
        }
        if($request->hasFile('archivo_word')){ 
            //Eliminar Archivo anterior
            Storage::delete($prototipos->ruta_archivo_word);               
            //Agregar nuevo archivo               
            $file = $request->file('archivo_word');
            $name = $file->getClientOriginalName();
            $prototipos->archivo_word = $name;
            $prototipos->ruta_archivo_word = $request->file('archivo_word')->store('public/prototipos');
        }
        $prototipos->update();

        Session::flash('success','Prototipos modificado.');
        return redirect()->route('invdesarrollo.prototipo.index')->with('modificar', 'success');
    }

    
    public function destroy($id)
    {
        
        $prototipos = Prototipo::findOrFail($id);
        $prototipos->estado = 'ANULADO';
        $prototipos->update();

        Session::flash('success','Prototipos eliminado.');
        return redirect()->route('invdesarrollo.prototipo.index')->with('eliminar', 'success');

    }
}
