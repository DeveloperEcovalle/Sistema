<?php

namespace App\Http\Controllers\Produccion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Produccion\Programacion_produccion;
use App\Almacenes\Producto;
use DataTables;
use Carbon\Carbon;
use Session;
use Illuminate\Support\Facades\Validator;
use DB;

class Programacion_produccionController extends Controller
{
    public function index()
    {
        return view('produccion.programacion_produccion.index');
    }
    public function getProgramacionProduccion(){
        return datatables()->query(
            DB::table('programacion_produccion')
            ->join('productos','productos.id','=','programacion_produccion.producto_id')
            ->select('programacion_produccion.*','productos.nombre', 
            DB::raw('DATE_FORMAT(programacion_produccion.created_at, "%d/%m/%Y") as creado'),
            DB::raw('DATE_FORMAT(programacion_produccion.updated_at, "%d/%m/%Y") as actualizado')
            )
        )->toJson();
    }

    public function create()
    {
        $fecha_hoy = Carbon::now()->toDateString();
        $productos = Producto::where('estado','ACTIVO')->get();

        return view('produccion.programacion_produccion.create',[
            'productos' => $productos, 
            'fecha_hoy' => $fecha_hoy,
        ]);
    }

    public function edit($id)
    {
        $programacion_produccion = Programacion_produccion::findOrFail($id);
        $productos = Producto::where('estado','ACTIVO')->get();
        $fecha_hoy = Carbon::now()->toDateString();

        return view('produccion.programacion_produccion.edit',[
            'programacion_produccion' => $programacion_produccion,
            'productos' => $productos, 
            'fecha_hoy' => $fecha_hoy, 
        ]);
    }
    
    public function show($id)
    {
        $programacion_produccion = Programacion_produccion::findOrFail($id);
        $productos = Producto::where('estado','ACTIVO')->get();
 
        return view('produccion.programacion_produccion.show',[
            'programacion_produccion' => $programacion_produccion,
            'productos' => $productos, 
        ]);
    }
    public function store(Request $request){
        
        $data = $request->all();
        $rules = [
            'producto_id'=>'',
            'fecha_creacion'=>'',
            'fecha_produccion'=>'',
            'fecha_termino'=>'',
            'cantidad_programada'=>'',
            'cantidad_producida'=>'',
            'observacion'=>'',
            'usuario_id'=>'',
            'estado'=>'',
            
        ];
        
        $message = [
            'producto_id'=>'El campo producto_id es ...',
            'fecha_creacion'=>'El campo fecha_creacion es ...',
            'fecha_produccion'=>'El campo fecha_produccion es ...',
            'fecha_termino'=>'El campo fecha_termino es ...',
            'cantidad_programada'=>'El campo cantidad_programada es ...',
            'cantidad_producida'=>'El campo cantidad_producida es ...',
            'observacion'=>'El campo observacion es ...',
            'usuario_id'=>'El campo usuario_id es ...',
            'estado'=>'El campo estado es ...',
            
        ];

        Validator::make($data, $rules, $message)->validate();
        
        //$registro_sanitario = new RegistroSanitario();
        $programacion_produccion=new Programacion_produccion;
        $programacion_produccion->producto_id=$request->get('producto_id');
        $programacion_produccion->fecha_creacion=Carbon::createFromFormat('d/m/Y', $request->get('fecha_creacion'))->format('Y-m-d');
        $programacion_produccion->fecha_produccion=Carbon::createFromFormat('d/m/Y', $request->get('fecha_produccion'))->format('Y-m-d');
        $programacion_produccion->fecha_termino=Carbon::createFromFormat('d/m/Y', $request->get('fecha_termino'))->format('Y-m-d');
        $programacion_produccion->cantidad_programada=$request->get('cantidad_programada');
        $programacion_produccion->cantidad_producida=$request->get('cantidad_producida');
        $programacion_produccion->observacion=$request->get('observacion');
        $programacion_produccion->usuario_id = auth()->user()->id;
        //$programacion_produccion->estado=$request->get('estado');
        $programacion_produccion->save();

        //Registro de actividad
        $descripcion = "SE AGREGÓ LA PROGRAMACION DE PRODUCCION DEL PRODUCTO CON EL NOMBRE: ". $programacion_produccion->producto->nombre;
        $gestion = "PROGRAMACION DE PRODUCCION";
        crearRegistro($programacion_produccion, $descripcion , $gestion);

        Session::flash('success','Programacion de produccion creado.');
        return redirect()->route('produccion.programacion_produccion.index')->with('guardar', 'success');
    }

    public function update(Request $request, $id){
        
        $data = $request->all();
        $rules = [
            'producto_id' =>'',
            'fecha_creacion' =>'',
            'fecha_produccion' =>'',
            'fecha_termino' =>'',
            'cantidad_programada' =>'',
            'cantidad_producida' =>'',
            'observacion' =>'',
            'usuario_id' =>'',
            'estado' =>'',
            
        ];
        
        $message = [
            'producto_id' =>'El campo producto_id es ...',
            'fecha_creacion' =>'El campo fecha_creacion es ...',
            'fecha_produccion' =>'El campo fecha_produccion es ...',
            'fecha_termino' =>'El campo fecha_termino es ...',
            'cantidad_programada' =>'El campo cantidad_programada es ...',
            'cantidad_producida' =>'El campo cantidad_producida es ...',
            'observacion' =>'El campo observacion es ...',
            'usuario_id' =>'El campo usuario_id es ...',
            'estado' =>'El campo estado es ...',
            
        ];

        Validator::make($data, $rules, $message)->validate();

        $programacion_produccion = Programacion_produccion::findOrFail($id);
        $programacion_produccion->producto_id = $request->get('producto_id');
        $programacion_produccion->cantidad_programada = $request->get('cantidad_programada');
        $programacion_produccion->cantidad_producida = $request->get('cantidad_producida');
        $programacion_produccion->observacion = $request->get('observacion');
        $programacion_produccion->usuario_id = auth()->user()->id;

        $programacion_produccion->update();

        //Registro de actividad
        $descripcion = "SE MODIFICÓ LA PROGRAMACION DE PRODUCCION DEL PRODUCTO CON EL NOMBRE: ". $programacion_produccion->producto->nombre;
        $gestion = "PROGRAMACION DE PRODUCCION";
        modificarRegistro($programacion_produccion, $descripcion , $gestion);

        Session::flash('success','Programacion_produccion modificado.');
        return redirect()->route('produccion.programacion_produccion.index')->with('modificar', 'success');
    }

    
    public function destroy($id)
    {
        
        $programacion_produccion = Programacion_produccion::findOrFail($id);
        $programacion_produccion->estado = 'ANULADO';
        $programacion_produccion->update();

        //Registro de actividad
        $descripcion = "SE ELIMINÓ LA PROGRAMACION DE PRODUCCION DEL PRODUCTO CON EL NOMBRE: ". $programacion_produccion->producto->nombre;
        $gestion = "PROGRAMACION DE PRODUCCION";
        eliminarRegistro($programacion_produccion, $descripcion , $gestion);

        Session::flash('success','Programacion de Produccion eliminado.');
        return redirect()->route('produccion.programacion_produccion.index')->with('eliminar', 'success');

    }
}