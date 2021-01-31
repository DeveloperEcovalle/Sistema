<?php

namespace App\Http\Controllers\Mantenimiento\Empresa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mantenimiento\Empresa\Empresa;
use App\Mantenimiento\Ubigeo\Departamento;
use App\Mantenimiento\Ubigeo\Provincia;
use App\Mantenimiento\Ubigeo\Distrito;
use DataTables;
use Carbon\Carbon;
use Session;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use DB;
use App\Mantenimiento\Empresa\Banco;

class EmpresaController extends Controller
{
    public function index()
    {
        return view('mantenimiento.empresas.index');
    }

    public function getBusiness(){
        return datatables()->query(
            DB::table('empresas')
            ->select('empresas.*')->where('empresas.estado','ACTIVO')->orderBy('empresas.id', 'desc')
        )->toJson();
    }

    public function create()
    {
        $departamentos = Departamento::all();
        $provincias = Provincia::all();
        $distritos = Distrito::all();
        $bancos = bancos();
        $monedas = tipos_moneda();
        return view('mantenimiento.empresas.create',[
            'departamentos' => $departamentos,
            'provincias' => $provincias,
            'distritos' => $distritos,
            'bancos' => $bancos,
            'monedas' => $monedas,
        ]);
    }

    public function store(Request $request){

        $data = $request->all();

        $rules = [
            'ruc' => ['required','numeric','min:11', Rule::unique('empresas','ruc')->where(function ($query) {
                        $query->whereIn('estado',["ACTIVO"]);
                    })],
            'estado' => 'required',
            'razon_social' => 'required',
            'direccion_fiscal' => 'required',
            'direccion_llegada' => 'required',
            'logo' => 'image|mimetypes:image/jpeg,image/png,image/jpg|max:40000',
            'dni_representante' => 'required|min:8|numeric',
            'nombre_representante' => 'required',
            'num_asiento' => 'required',
            'num_partida' => 'required',
            'telefono' => 'nullable|numeric',
            'celular' => 'nullable|numeric',
            'correo' => 'nullable',

        ];
        
        $message = [
            'ruc.required' => 'El campo Ruc es obligatorio.',
            'ruc.unique'=> 'El campo Ruc debe de ser campo único.',
            'ruc.numeric'=> 'El campo Ruc debe se numérico.',
            'ruc.min'=>'El campo Ruc debe tener 11 dígitos.',
            'razon_social.required' => 'El campo Razón Social es obligatorio.',
            'direccion_fiscal.required' => 'El campo Direccion Fiscal es obligatorio.',
            'direccion_llegada.required' => 'El campo Direccion Planta es obligatorio.',
            'logo.image' => 'El campo Logo no contiene el formato imagen.',
            'logo.max' => 'El tamaño máximo del Logo para cargar es de 40 MB.',
            'dni_representante.required' => 'El campo Dni es obligatorio.',
            'dni_representante.min' => 'El campo Dni debe tener 8 dígitos.',
            'dni_representante.numeric' => 'El campo Dni debe ser numérico.',
            'nombre_representante.required' => 'El campo Nombre es obligatorio.',
            'num_asiento.required' => 'El campo N° Asiento es obligatorio.',
            'num_partida.required' => 'El campo N° Partida es obligatorio.',
            'telefono.numeric' => 'El campo Teléfono es obligatorio.',
            'estado.required' => 'El campo Estado es obligatorio.',

            

        ];

        Validator::make($data, $rules, $message)->validate();

        $empresa = new Empresa();
        $empresa->ruc = $request->get('ruc');
        $empresa->razon_social = $request->get('razon_social');
        $empresa->razon_social_abreviada = $request->get('razon_social_abreviada');
        $empresa->direccion_fiscal = $request->get('direccion_fiscal');
        $empresa->direccion_llegada = $request->get('direccion_llegada');
        $empresa->telefono = $request->get('telefono');
        $empresa->celular = $request->get('celular');

        if($request->hasFile('logo')){                
            $file = $request->file('logo');
            $name = $file->getClientOriginalName();
            $empresa->nombre_logo = $name;
            $empresa->ruta_logo = $request->file('logo')->store('public/empresas/logos');
        }
        
        $empresa->dni_representante = $request->get('dni_representante');
        $empresa->nombre_representante = $request->get('nombre_representante');
        $empresa->estado_dni_representante = $request->get('estado_dni_representante');

        $empresa->num_partida = $request->get('num_partida');
        $empresa->num_asiento = $request->get('num_asiento');
        $empresa->estado_ruc = $request->get('estado');
        $empresa->save();

        //Llenado de Bancos
        $entidadesJSON = $request->get('entidades_tabla');
        $entidadtabla = json_decode($entidadesJSON[0]);

        if ($entidadtabla) {
            foreach ($entidadtabla as $entidad) {
                Banco::create([
                    'empresa_id' => $empresa->id,
                    'descripcion' => $entidad->entidad,
                    'tipo_moneda' => $entidad->moneda,
                    'num_cuenta' => $entidad->cuenta,
                    'cci' => $entidad->cci,
                    'itf' => $entidad->itf,
                ]);
            }
        }
        

        Session::flash('success','Empresa creada.');
        return redirect()->route('mantenimiento.empresas.index')->with('guardar', 'success');
    }

    public function destroy($id)
    {
        
        $empresa = Empresa::findOrFail($id);
        $empresa->estado = 'ANULADO';
        $empresa->update();

        Session::flash('success','Empresa eliminado.');
        return redirect()->route('mantenimiento.empresas.index')->with('eliminar', 'success');

    }

    public function show($id)
    {
        $empresa = Empresa::findOrFail($id);
        $banco = Banco::where('empresa_id',$id)
        ->where('estado','ACTIVO')
        ->get();
        return view('mantenimiento.empresas.show', [
            'empresa' => $empresa ,
            'banco' => $banco
        ]);

    }

    public function edit($id)
    {
        $empresa = Empresa::findOrFail($id);
        $banco = Banco::where('empresa_id',$id)
        ->where('estado','ACTIVO')
        ->get();
        $bancos = bancos();
        $monedas = tipos_moneda();
        return view('mantenimiento.empresas.edit', [
            'empresa' => $empresa ,
            'bancos' => $bancos, 
            'monedas' => $monedas,
            'banco' => $banco,
        ]);

    }

    public function update(Request $request, $id){
        
        $data = $request->all();
        $rules = [
            'ruc' => ['required','numeric','min:11', Rule::unique('empresas','ruc')->where(function ($query) {
                        $query->whereIn('estado',["ACTIVO"]);
                    })->ignore($id)],
            'razon_social' => 'required',
            'estado' => 'required',
            'direccion_fiscal' => 'required',
            'direccion_llegada' => 'required',
            'logo' => 'image|mimetypes:image/jpeg,image/png,image/jpg|max:40000',
            'dni_representante' => 'required|min:8|numeric',
            'nombre_representante' => 'required',
            'num_asiento' => 'required',
            'num_partida' => 'required',
            'telefono' => 'nullable|numeric',
            'celular' => 'nullable|numeric',

        ];
        
        $message = [
            'ruc.required' => 'El campo Ruc es obligatorio.',
            'ruc.unique'=> 'El campo Ruc debe de ser campo único.',
            'ruc.min'=>'El campo Ruc debe tener 11 dígitos.',
            'ruc.numeric'=> 'El campo Ruc debe se numérico.',
            'razon_social.required' => 'El campo Razón Social es obligatorio.',
            'direccion_fiscal.required' => 'El campo Direccion Fiscal es obligatorio.',
            'direccion_llegada.required' => 'El campo Direccion Planta es obligatorio.',
            'logo.image' => 'El campo Logo no contiene el formato imagen.',
            'logo.max' => 'El tamaño máximo del Logo para cargar es de 40 MB.',
            'dni_representante.required' => 'El campo Dni es obligatorio.',
            'dni_representante.min' => 'El campo Dni debe tener 8 dígitos.',
            'dni_representante.numeric' => 'El campo Dni debe ser numérico.',
            'nombre_representante.required' => 'El campo Nombre es obligatorio.',
            'num_asiento.required' => 'El campo N° Asiento es obligatorio.',
            'num_partida.required' => 'El campo N° Partida es obligatorio.',
            'telefono.numeric' => 'El campo Teléfono es obligatorio.',
            'celular.numeric' => 'El campo Celular es obligatorio.',
            'estado.required' => 'El campo Estado es obligatorio.',

        ];

        Validator::make($data, $rules, $message)->validate();

        $empresa = Empresa::findOrFail($id);
        $empresa->ruc = $request->get('ruc');
        $empresa->razon_social = $request->get('razon_social');
        $empresa->razon_social_abreviada = $request->get('razon_social_abreviada');
        $empresa->direccion_fiscal = $request->get('direccion_fiscal');
        $empresa->direccion_llegada = $request->get('direccion_llegada');
        $empresa->telefono = $request->get('telefono');
        $empresa->celular = $request->get('celular');

        if($request->hasFile('logo')){
            //Eliminar Archivo anterior
            Storage::delete($empresa->ruta_logo);               
            //Agregar nuevo archivo
            $file = $request->file('logo');
            $name = $file->getClientOriginalName();
            $empresa->nombre_logo = $name;
            $empresa->ruta_logo = $request->file('logo')->store('public/empresas/logos');
        }

        $empresa->dni_representante = $request->get('dni_representante');
        $empresa->nombre_representante = $request->get('nombre_representante');
        $empresa->estado_dni_representante = $request->get('estado_dni_representante');

        $empresa->num_partida = $request->get('num_partida');
        $empresa->num_asiento = $request->get('num_asiento');
        $empresa->estado_ruc = $request->get('estado');

        $empresa->update();

        $entidadesJSON = $request->get('entidades_tabla');
        $entidadtabla = json_decode($entidadesJSON[0]);

        if ($entidadtabla) {

            $bancos = Banco::where('empresa_id', $empresa->id)->get();
            foreach ($bancos as $banco) {
                $banco->estado= "ANULADO";
                $banco->update();
            }
            foreach ($entidadtabla as $entidad) {
                Banco::create([
                    'empresa_id' => $empresa->id,
                    'descripcion' => $entidad->entidad,
                    'tipo_moneda' => $entidad->moneda,
                    'num_cuenta' => $entidad->cuenta,
                    'cci' => $entidad->cci,
                    'itf' => $entidad->itf,
                ]);
            }

        }else{
            $bancos = Banco::where('empresa_id', $empresa->id)->get();
            foreach ($bancos as $banco) {
                $banco->estado= "ANULADO";
                $banco->update();
            }
        }

        Session::flash('success','Empresa modificada.');
        return redirect()->route('mantenimiento.empresas.index')->with('modificar', 'success');

    }

}
