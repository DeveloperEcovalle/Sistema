<?php

namespace App\Http\Controllers\Mantenimiento\Empleado;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mantenimiento\Empleado\Empleado;
use App\Mantenimiento\Persona\Persona;
use Carbon\Carbon;
use DataTables;
use Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class EmpleadoController extends Controller
{
    public function index()
    {
        return view('mantenimiento.empleados.index');
    }

    public function getTable()
    {
        $empleados = Empleado::where('estado','ACTIVO')->get();
        $coleccion = collect([]);
        foreach($empleados as $empleado) {
            if ($empleado->vendedor)
                continue;
            $coleccion->push([
                'id' => $empleado->id,
                'documento' => $empleado->persona->getDocumento(),
                'apellidos_nombres' => $empleado->persona->getApellidosYNombres(),
                'telefono_movil' => $empleado->persona->telefono_movil,
                'area' => $empleado->area,
                'cargo' => $empleado->cargo
            ]);
        }
        return DataTables::of($coleccion)->toJson();
    }

    public function create()
    {
        return view('mantenimiento.empleados.create');
    }

    public function store(Request $request)
    {
        $data = $request->all();
        DB::transaction(function () use ($request) {

            $persona = new Persona();
            $persona->tipo_documento = $request->get('tipo_documento');
            $persona->documento = $request->get('documento');
            $persona->codigo_verificacion = $request->get('codigo_verificacion');
            $persona->nombres = $request->get('nombres');
            $persona->apellido_paterno = $request->get('apellido_paterno');
            $persona->apellido_materno = $request->get('apellido_materno');
            $persona->fecha_nacimiento = Carbon::createFromFormat('d/m/Y', $request->get('fecha_nacimiento'))->format('Y-m-d') ;
            $persona->sexo = $request->get('sexo');
            $persona->estado_civil = $request->get('estado_civil');
            $persona->departamento_id = str_pad($request->get('departamento'), 2, "0", STR_PAD_LEFT);
            $persona->provincia_id = str_pad($request->get('provincia'), 4, "0", STR_PAD_LEFT);
            $persona->distrito_id = str_pad($request->get('distrito'), 6, "0", STR_PAD_LEFT);
            $persona->direccion = $request->get('direccion');
            $persona->correo_electronico = $request->get('correo_electronico');
            $persona->telefono_movil = $request->get('telefono_movil');
            $persona->telefono_fijo = $request->get('telefono_fijo');
            $persona->estado_documento = $request->get('estado_documento');
            $persona->save();

            $empleado = new Empleado();
            $empleado->persona_id = $persona->id;
            $empleado->area = $request->get('area');
            $empleado->profesion = $request->get('profesion');
            $empleado->cargo = $request->get('cargo');
            $empleado->telefono_referencia = $request->get('telefono_referencia');
            $empleado->contacto_referencia = $request->get('contacto_referencia');
            $empleado->grupo_sanguineo = $request->get('grupo_sanguineo');
            $empleado->alergias = $request->get('alergias');
            $empleado->numero_hijos = $request->get('numero_hijos');
            $empleado->sueldo = $request->get('sueldo');
            $empleado->sueldo_bruto = $request->get('sueldo_bruto');
            $empleado->sueldo_neto = $request->get('sueldo_neto');
            $empleado->moneda_sueldo = $request->get('moneda_sueldo');
            $empleado->tipo_banco = $request->get('tipo_banco');
            $empleado->numero_cuenta = $request->get('numero_cuenta');

            if($request->hasFile('imagen')){
                $file = $request->file('imagen');
                $name = $file->getClientOriginalName();
                $empleado->nombre_imagen = $name;
                $empleado->ruta_imagen = $request->file('imagen')->store('public/empleados/imagenes');
            }

            $empleado->fecha_inicio_actividad = Carbon::createFromFormat('d/m/Y', $request->get('fecha_inicio_actividad'))->format('Y-m-d') ;
            if (!is_null($request->get('fecha_fin_actividad'))) {
                $empleado->fecha_fin_actividad = Carbon::createFromFormat('d/m/Y', $request->get('fecha_fin_actividad'))->format('Y-m-d') ;
            }
            if (!is_null($request->get('fecha_inicio_planilla'))) {
                $empleado->fecha_inicio_planilla = Carbon::createFromFormat('d/m/Y', $request->get('fecha_inicio_planilla'))->format('Y-m-d') ;
            }
            if (!is_null($request->get('fecha_fin_planilla'))) {
                $empleado->fecha_fin_planilla = Carbon::createFromFormat('d/m/Y', $request->get('fecha_fin_planilla'))->format('Y-m-d') ;
            }
            $empleado->save();
        });

        Session::flash('success','Empleado creado.');
        return redirect()->route('mantenimiento.empleado.index')->with('guardar', 'success');
    }

    public function edit($id)
    {
        $empleado = Empleado::findOrFail($id);
        return view('mantenimiento.empleados.edit', [
            'empleado' => $empleado
        ]);
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();
        //dd($data);
        $empleado = Empleado::findOrFail($id);

        DB::transaction(function () use ($request, $empleado) {

            $persona =  $empleado->persona;
            $persona->tipo_documento = $request->get('tipo_documento');
            $persona->documento = $request->get('documento');
            $persona->codigo_verificacion = $request->get('codigo_verificacion');
            $persona->nombres = $request->get('nombres');
            $persona->apellido_paterno = $request->get('apellido_paterno');
            $persona->apellido_materno = $request->get('apellido_materno');
            $persona->fecha_nacimiento = Carbon::createFromFormat('d/m/Y', $request->get('fecha_nacimiento'))->format('Y-m-d') ;
            $persona->sexo = $request->get('sexo');
            $persona->estado_civil = $request->get('estado_civil');
            $persona->departamento_id = str_pad($request->get('departamento'), 2, "0", STR_PAD_LEFT);
            $persona->provincia_id = str_pad($request->get('provincia'), 4, "0", STR_PAD_LEFT);
            $persona->distrito_id = str_pad($request->get('distrito'), 6, "0", STR_PAD_LEFT);
            $persona->direccion = $request->get('direccion');
            $persona->correo_electronico = $request->get('correo_electronico');
            $persona->telefono_movil = $request->get('telefono_movil');
            $persona->telefono_fijo = $request->get('telefono_fijo');
            $persona->estado_documento = $request->get('estado_documento');
            $persona->update();

            $empleado->persona_id = $persona->id;
            $empleado->area = $request->get('area');
            $empleado->profesion = $request->get('profesion');
            $empleado->cargo = $request->get('cargo');
            $empleado->telefono_referencia = $request->get('telefono_referencia');
            $empleado->contacto_referencia = $request->get('contacto_referencia');
            $empleado->grupo_sanguineo = $request->get('grupo_sanguineo');
            $empleado->alergias = $request->get('alergias');
            $empleado->numero_hijos = $request->get('numero_hijos');
            $empleado->sueldo = $request->get('sueldo');
            $empleado->sueldo_bruto = $request->get('sueldo_bruto');
            $empleado->sueldo_neto = $request->get('sueldo_neto');
            $empleado->moneda_sueldo = $request->get('moneda_sueldo');
            $empleado->tipo_banco = $request->get('tipo_banco');
            $empleado->numero_cuenta = $request->get('numero_cuenta');

            if($request->hasFile('imagen')){
                //Eliminar Archivo anterior
                Storage::delete($empleado->ruta_imagen);
                //Agregar nuevo archivo
                $file = $request->file('imagen');
                $name = $file->getClientOriginalName();
                $empleado->nombre_imagen = $name;
                $empleado->ruta_imagen = $request->file('imagen')->store('public/empleados/imagenes');
            }else{
                if ($empleado->ruta_imagen) {
                    //Eliminar Archivo anterior
                    Storage::delete($empleado->ruta_imagen);
                    $empleado->nombre_imagen = '';
                    $empleado->ruta_imagen = '';
                }
            }

            $empleado->fecha_inicio_actividad = Carbon::createFromFormat('d/m/Y', $request->get('fecha_inicio_actividad'))->format('Y-m-d') ;
            if (!is_null($request->get('fecha_fin_actividad'))) {
                $empleado->fecha_fin_actividad = Carbon::createFromFormat('d/m/Y', $request->get('fecha_fin_actividad'))->format('Y-m-d') ;
            }
            if (!is_null($request->get('fecha_inicio_planilla'))) {
                $empleado->fecha_inicio_planilla = Carbon::createFromFormat('d/m/Y', $request->get('fecha_inicio_planilla'))->format('Y-m-d') ;
            }
            if (!is_null($request->get('fecha_fin_planilla'))) {
                $empleado->fecha_fin_planilla = Carbon::createFromFormat('d/m/Y', $request->get('fecha_fin_planilla'))->format('Y-m-d') ;
            }
            $empleado->update();
        });

        Session::flash('success','Empleado modificado.');
        return redirect()->route('mantenimiento.empleado.index')->with('modificar', 'success');
    }

    public function show($id)
    {
        $empleado = Empleado::findOrFail($id);
        return view('mantenimiento.empleados.show', [
            'empleado' => $empleado
        ]);
    }

    public function destroy($id)
    {
        DB::transaction(function() use ($id) {

            $empleado = Empleado::findOrFail($id);
            $empleado->estado = 'ANULADO';
            $empleado->update();

            $persona = $empleado->persona;
            $persona->estado = 'ANULADO';
            $persona->update();

        });

        Session::flash('success','Empleado eliminado.');
        return redirect()->route('mantenimiento.empleado.index')->with('eliminar', 'success');
    }

    public function getDni(Request $request)
    {
        $data = $request->all();
        $existe = false;
        $igualPersona = false;
        if (!is_null($data['tipo_documento']) && !is_null($data['documento'])) {
            if (!is_null($data['id'])) {
                $persona = Persona::findOrFail($data['id']);
                if ($persona->tipo_documento == $data['tipo_documento'] && $persona->documento == $data['documento']) {
                    $igualPersona = true;
                } else {
                    $persona = Persona::where([
                        ['tipo_documento', '=', $data['tipo_documento']],
                        ['documento', $data['documento']],
                        ['estado', 'ACTIVO']
                    ])->first();
                }
            } else {
                $persona = Persona::where([
                    ['tipo_documento', '=', $data['tipo_documento']],
                    ['documento', $data['documento']],
                    ['estado', 'ACTIVO']
                ])->first();
            }

            if (!is_null($persona) && !is_null($persona->empleado)) {
                $existe = true;
            }
        }

        $result = [
            'existe' => $existe,
            'igual_persona' => $igualPersona
        ];

        return response()->json($result);
    }
}
