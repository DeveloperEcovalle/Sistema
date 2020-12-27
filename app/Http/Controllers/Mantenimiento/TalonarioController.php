<?php

namespace App\Http\Controllers\Mantenimiento;

use App\Http\Controllers\Controller;
use App\Mantenimiento\Empresa;
use App\Mantenimiento\Talonario;
use Illuminate\Http\Request;
use DataTables;
use Carbon\Carbon;
use Session;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class TalonarioController extends Controller
{
    public function index()
    {
        return view('mantenimiento.talonarios.index');
    }

    public function getTable()
    {
        $talonarios = Talonario::where('estado', 'ACTIVO')->get();
        $coleccion = collect([]);
        foreach ($talonarios as $talonario) {
            $coleccion->push([
                'id' => $talonario->id,
                'tipo_documento' => $talonario->tipo_documento,
                'tipo_documento_descripcion' => tipos_documentos_tributarios()->firstWhere('simbolo', $talonario->tipo_documento)->descripcion.' ('.$talonario->tipo_documento.')',
                'serie' => $talonario->serie,
                'numero_inicio' => $talonario->numero_inicio,
                'numero_final' => $talonario->numero_final,
                'numero_actual' => $talonario->numero_actual
            ]);
        }
        return DataTables::of($coleccion)->toJson();
    }

    public function create()
    {
        $empresas = Empresa::where('estado', 'ACTIVO')->get();
        return view('mantenimiento.talonarios.create', compact('empresas'));
    }

    public function store(Request $request)
    {
        $data = $request->all();

        $rules = [
            'tipo_documento_guardar' => 'required',
            'serie_guardar' => ['required', Rule::unique('talonarios','serie')->where(function ($query) {
                $query->whereIn('estado', ["ACTIVO"]);
            })->where('tipo_documento', $request->get('tipo_documento_guardar'))],
            'numero_inicio_guardar' => 'required|numeric',
            'numero_final_guardar' => 'nullable|numeric',
            'numero_actual_guardar' => 'required|numeric',
        ];

        $message = [
            'tipo_documento_guardar.required' => 'El campo Tipo de documento es obligatorio',
            'serie_guardar.required' => 'El campo Serie es obligatorio',
            'serie_guardar.unique' => 'Ya existe un talonario para el tipo de documento y serie establecida',
            'numero_inicio_guardar.required' => 'El campo Número de inicio es obligatorio',
            'numero_inicio_guardar.numeric' => 'El campo Número de inicio debe ser numérico',
            'numero_final_guardar.numeric' => 'El campo Número final debe ser numérico',
            'numero_actual_guardar.required' => 'El campo Número actual es obligatorio',
            'numero_actual_guardar.numeric' => 'El campo Número actual debe ser numérico',
        ];

        Validator::make($data, $rules, $message)->validate();

        $talonario = new Talonario();
        $talonario->tipo_documento = $request->get('tipo_documento_guardar');
        $talonario->serie = $request->get('serie_guardar');
        $talonario->numero_inicio = $request->get('numero_inicio_guardar');
        $talonario->numero_final = $request->get('numero_final_guardar');
        $talonario->numero_actual = $request->get('numero_actual_guardar');
        $talonario->save();

        Session::flash('success', 'Talonario creado.');
        return redirect()->route('mantenimiento.talonario.index')->with('guardar', 'success');
    }

    public function edit($id)
    {
        $talonario = Talonario::findOrFail($id);
        $empresas = Empresa::where('estado', 'ACTIVO')->get();

        return view ('mantenimiento.talonarios.edit', compact('talonario', 'empresas'));
    }

    public function update(Request $request)
    {
        $data = $request->all();

        $rules = [
            'id_editar' => 'required',
            'tipo_documento_editar' => 'required',
            'serie_editar' => ['required', Rule::unique('talonarios','serie')->where(function ($query) {
                $query->whereIn('estado', ["ACTIVO"]);
            })->where('tipo_documento', $request->get('tipo_documento_editar'))->ignore($request->get('id_editar'))],
            'numero_inicio_editar' => 'required|numeric',
            'numero_final_editar' => 'nullable|numeric',
            'numero_actual_editar' => 'required|numeric',
        ];

        $message = [
            'tipo_documento_editar.required' => 'El campo Tipo de documento es obligatorio',
            'serie_editar.required' => 'El campo Serie es obligatorio',
            'numero_inicio_editar.required' => 'El campo Número de inicio es obligatorio',
            'numero_inicio_editar.numeric' => 'El campo Número de inicio debe ser numérico',
            'numero_final_editar.numeric' => 'El campo Número final debe ser numérico',
            'numero_actual_editar.required' => 'El campo Número actual es obligatorio',
            'numero_actual_editar.numeric' => 'El campo Número actual debe ser numérico',
        ];

        Validator::make($data, $rules, $message)->validate();

        $talonario = Talonario::findOrFail($request->get('id_editar'));
        $talonario->tipo_documento = $request->get('tipo_documento_editar');
        $talonario->serie = $request->get('serie_editar');
        $talonario->numero_inicio = $request->get('numero_inicio_editar');
        $talonario->numero_final = $request->get('numero_final_editar');
        $talonario->numero_actual = $request->get('numero_actual_editar');
        $talonario->update();

        Session::flash('success', 'Talonario modificado.');
        return redirect()->route('mantenimiento.talonario.index')->with('modificar', 'success');
    }

    public function destroy($id)
    {
        $talonario = Talonario::findOrFail($id);
        $talonario->estado = 'ANULADO';
        $talonario->update();

        Session::flash('success','Talonario eliminado.');
        return redirect()->route('mantenimiento.talonario.index')->with('eliminar', 'success');
    }

}
