<?php

namespace App\Http\Controllers\Seguridad;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use DataTables;
use Carbon\Carbon;
use App\Mantenimiento\Empleado\Empleado;
use Session;
use Illuminate\Support\Facades\Validator;
use App\User;
use Illuminate\Support\Facades\Storage;

class UsuarioController extends Controller
{
    public function index()
    {
        return view('seguridad.usuarios.index');
    }

    public function getUsers(){
        $usuarios = DB::table('users')
        ->join('empleados','users.empleado_id','=','empleados.id')
        ->join('personas','empleados.persona_id','=','personas.id')
        ->select('users.*','personas.apellido_materno','personas.apellido_paterno','personas.nombres',DB::raw('CONCAT(personas.apellido_materno,\' \',personas.apellido_paterno,\' \',personas.nombres) AS nombre_completo'))
        ->where('users.estado','!=',"ANULADO")
        ->get();
        return DataTables::of($usuarios)->toJson();
    }

    public function create(){
        return view('seguridad.usuarios.create');
    }

    public function edit($id){
        $usuario = User::findOrFail($id);
        return view('seguridad.usuarios.edit',[
            'usuario' => $usuario
        ]);
    }

    public function getEmployee(){

        $empleados = DB::table('empleados')
        ->join('personas','empleados.id','=','personas.id')
        ->select('empleados.id as empleado_id', 'personas.*')
        ->where('empleados.estado','!=',"ANULADO")
        ->get();

 
        $coleccion = collect([]);
        foreach($empleados as $empleado){
            if (DB::table('users')->where('empleado_id', $empleado->empleado_id)->where('estado','ACTIVO')->exists() == false) {
                $coleccion->push([
                    'id' => $empleado->empleado_id,
                    'apellido_materno' => $empleado->apellido_materno,
                    'apellido_paterno' => $empleado->apellido_paterno,
                    'nombres' => $empleado->nombres,
                ]);

             }
        };

        return $coleccion;
    }

    public function getEmployeeedit($id){

        $empleados = DB::table('empleados')
        ->join('personas','empleados.id','=','personas.id')
        ->select('empleados.id as empleado_id', 'personas.*')
        ->where('empleados.estado','!=',"ANULADO")
        ->get();

 
        $coleccion = collect([]);
        foreach($empleados as $empleado){
            if (DB::table('users')->where('empleado_id', $empleado->empleado_id)->where('estado','ACTIVO')->exists() == false || $empleado->id == $id) {
                $coleccion->push([
                    'id' => $empleado->empleado_id,
                    'apellido_materno' => $empleado->apellido_materno,
                    'apellido_paterno' => $empleado->apellido_paterno,
                    'nombres' => $empleado->nombres,
                ]);

             }

        };

        return $coleccion;
    }

    public function store(Request $request){
        
        $data = $request->all();
        $rules = [
            'empleado_id' => 'required',
            'usuario' => 'required',
            'correo' => 'required|unique:users,email',
            'password' => 'required|confirmed',
            'logo' => 'image|mimetypes:image/jpeg,image/png,image/jpg|max:40000',
            'file' => '',
        ];

        $message = [
            'empleado_id.required' => 'El campo Empleado es obligatorio.',

            'logo.image' => 'El campo Imagen no contiene el formato imagen.',
            'logo.max' => 'El tamaño máximo del Imagen para cargar es de 40 MB.',
            'usuario.required' => 'El campo Usuario es obligatorio.',
            'correo.required' => 'El campo Correo es obligatorio.',
            'correo.unique' => 'El campo Correo debe de ser único.',
            'password.required' => 'El campo Contraseña es obligatorio.',
            'password.confirmed' => 'Las contraseñas no coinciden.'
        ];

        Validator::make($data, $rules, $message)->validate();



        $usuario = new User();
        $usuario->usuario = $data['usuario'];
        $usuario->empleado_id = $data['empleado_id'];
        $usuario->email = $data['correo'];
        $usuario->password = bcrypt($data['password']);

        if($request->hasFile('logo')){                
            $file = $request->file('logo');
            $name = $file->getClientOriginalName();
            $usuario->nombre_imagen = $name;
            $usuario->ruta_imagen = $request->file('logo')->store('public/usuarios');
        }
        // $usuario = User::create([
        //     'usuario' => $data['usuario'],
        //     'empleado_id' => $data['empleado_id'],
        //     'email' => $data['correo'],
        //     'password' => bcrypt($data['password']),
        //     'imagen' => $nombre_img
        // ]);

        // $password_sinbcry = $data['password'];
        $usuario->save();
        Session::flash('success','Usuario creado.');
        return redirect()->route('seguridad.usuario.index')->with('guardar','success');


    }

    public function update(Request $request, $id){
        
        $data = $request->all();
        $rules = [
            'empleado_id' => 'required',
            'usuario' => 'required',
            'correo' => 'required|unique:users,email,' . $id,
            'logo' => 'image|mimetypes:image/jpeg,image/png,image/jpg|max:40000',
            'file' => '',
        ];

        $message = [
            'empleado_id.required' => 'El campo Empleado es obligatorio.',
            'logo.image' => 'El campo Imagen no contiene el formato imagen.',
            'logo.max' => 'El tamaño máximo del Imagen para cargar es de 40 MB.',
            'usuario.required' => 'El campo Usuario es obligatorio.',
            'correo.required' => 'El campo Correo es obligatorio.',
            'correo.unique' => 'El campo Correo debe de ser único.',
        ];

        Validator::make($data, $rules, $message)->validate();



        $usuario = User::findOrFail($id);
        $usuario->usuario = $data['usuario'];
        $usuario->empleado_id = $data['empleado_id'];
        $usuario->email = $data['correo'];
        // $usuario->password = bcrypt($data['password']);

        if ($request->has('cambiar_contraseña')) {
            $usuario->password = bcrypt($data['password']);
        }

        if($request->hasFile('logo')){
            //Eliminar Archivo anterior
            Storage::delete($usuario->ruta_imagen);               
            //Agregar nuevo archivo                
            $file = $request->file('logo');
            $name = $file->getClientOriginalName();
            $usuario->nombre_imagen = $name;
            $usuario->ruta_imagen = $request->file('logo')->store('public/usuarios');
        }

        // $usuario = User::create([
        //     'usuario' => $data['usuario'],
        //     'empleado_id' => $data['empleado_id'],
        //     'email' => $data['correo'],
        //     'password' => bcrypt($data['password']),
        //     'imagen' => $nombre_img
        // ]);

        // $password_sinbcry = $data['password'];
        $usuario->update();
        Session::flash('success','Usuario modificado.');
        return redirect()->route('seguridad.usuario.index')->with('modificar','success');


    }

    public function destroy($id)
    {
        
        $usuario = User::findOrFail($id);
        $usuario->estado = 'ANULADO';
        $usuario->update();

        if ( auth()->user()->estado == 'ACTIVO') {
            Session::flash('success','Usuario eliminado.');
            return redirect()->route('seguridad.usuario.index')->with('eliminar', 'success');
            
        }else{
            // Session::flash('usuario_anulado','Usuario ha sido eliminado.');
            return redirect()->route('logout')->with('usuario_eliminado','error');
        }



    }

}
