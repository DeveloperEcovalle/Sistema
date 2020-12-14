<?php

namespace App\Http\Controllers\Mantenimiento;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mantenimiento\Empresa;
use DataTables;
use Carbon\Carbon;
use Session;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class EmpresaController extends Controller
{
    public function index()
    {
        return view('mantenimiento.empresas.index');
    }

    public function getBusiness(){
        $empresas = Empresa::where('estado','ACTIVO')->get();
        return DataTables::of($empresas)->toJson();
    }

    public function create()
    {
        return view('mantenimiento.empresas.create');
    }

    public function store(Request $request){

        $data = $request->all();

        $rules = [
            'ruc' => ['required','numeric','min:11', Rule::unique('empresas','ruc')->where(function ($query) {
                        $query->whereIn('estado',["ACTIVO"]);
                    })],
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
            'celular.numeric' => 'El campo Celular es obligatorio.',
            

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

        $empresa->num_partida = $request->get('num_partida');
        $empresa->num_asiento = $request->get('num_asiento');
        $empresa->save();

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
        return view('mantenimiento.empresas.show', [
            'empresa' => $empresa 
        ]);

    }

    public function edit($id)
    {
        $empresa = Empresa::findOrFail($id);
        return view('mantenimiento.empresas.edit', [
            'empresa' => $empresa 
        ]);

    }

    public function update(Request $request, $id){
        $data = $request->all();

        $rules = [
            'ruc' => ['required','numeric','min:11', Rule::unique('empresas','ruc')->where(function ($query) {
                        $query->whereIn('estado',["ACTIVO"]);
                    })->ignore($id)],
            'razon_social' => 'required',
            'direccion_fiscal' => 'required',
            'direccion_llegada' => 'required',
            'logo' => 'image|mimetypes:image/jpeg,image/png,image/jpg|max:40000',
            'dni_representante' => 'required|min:8|numeric',
            'nombre_representante' => 'required',
            'num_asiento' => 'required',
            'num_partida' => 'required',
            'telefono' => 'numeric',
            'celular' => 'numeric',

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
        }else{
            if ($empresa->ruta_logo) {
                //Eliminar Archivo anterior
                Storage::delete($empresa->ruta_logo);
                $empresa->nombre_logo = '';
                $empresa->ruta_logo = '';
            }
        }
        
        $empresa->dni_representante = $request->get('dni_representante');
        $empresa->nombre_representante = $request->get('nombre_representante');

        $empresa->num_partida = $request->get('num_partida');
        $empresa->num_asiento = $request->get('num_asiento');
        $empresa->update();

        Session::flash('success','Empresa modificada.');
        return redirect()->route('mantenimiento.empresas.index')->with('modificar', 'success');

    }

}
