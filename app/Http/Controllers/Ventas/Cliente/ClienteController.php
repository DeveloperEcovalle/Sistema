<?php

namespace App\Http\Controllers\Ventas\Cliente;

use App\Http\Controllers\Controller;
use App\Ventas\Cliente\Cliente;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Session;


class ClienteController extends Controller
{
    public function index()
    {
        return view('ventas.clientes.index');
    }

    public function getTable()
    {
        $clientes = Cliente::where('estado','ACTIVO')->get();
        $coleccion = collect([]);
        foreach($clientes as $cliente) {
            $coleccion->push([
                'id' => $cliente->id,
                'documento' => $cliente->getDocumento(),
                'nombre' => $cliente->nombre,
                'telefono_movil' => $cliente->telefono_movil,
                'limite_credito' => ($cliente->limite_credito) ? $cliente->moneda_credito.' '.$cliente->limite_credito : 'No tiene'            ]);
        }
        return DataTables::of($coleccion)->toJson();
    }

    public function create()
    {
        return view('ventas.clientes.create');
    }

    public function store(Request $request)
    {
        $data = $request->all();

        $rules = [
            'tipo_documento' => 'required',
            'documento' => ['required','numeric', Rule::unique('clientes','documento')->where(function ($query) {
                $query->whereIn('estado',["ACTIVO"]);
            })],
            'nombre' => 'required',
            'departamento' => 'required',
            'provincia' => 'required',
            'distrito' => 'required',
            'direccion' => 'required',
            'telefono_movil' => 'required|numeric',
            'activo' => 'required'
        ];
        $message = [
            'tipo_documento.required' => 'El campo Tipo de documento es obligatorio.',
            'documento.required' => 'El campo Nro. Documento es obligatorio',
            'documento.unique' => 'El campo Nro. Documento debe ser único',
            'documento.numeric' => 'El campo Nro. Documento debe ser numérico',
            'departamento.required' => 'El campo Departamento es obligatorio',
            'provincia.required' => 'El campo Provincia es obligatorio',
            'distrito.required' => 'El campo Distrito es obligatorio',
            'direccion.required' => 'El campo Dirección completa es obligatorio',
            'telefono_movil.required' => 'El campo Teléfono móvil es obligatorio',
            'telefono_movil.numeric' => 'El campo Teléfono móvil debe ser numérico',
            'activo.required' => 'El campo Estado es obligatorio'
        ];

        Validator::make($data, $rules, $message)->validate();

        $cliente = new Cliente();
        $cliente->tipo_documento = $request->get('tipo_documento');
        $cliente->documento = $request->get('documento');
        $cliente->nombre = $request->get('nombre');
        $cliente->departamento_id = str_pad($request->get('departamento'), 2, "0", STR_PAD_LEFT);
        $cliente->provincia_id = str_pad($request->get('provincia'), 4, "0", STR_PAD_LEFT);
        $cliente->distrito_id = str_pad($request->get('distrito'), 6, "0", STR_PAD_LEFT);
        $cliente->direccion = $request->get('direccion');
        $cliente->correo_electronico = $request->get('correo_electronico');
        $cliente->telefono_movil = $request->get('telefono_movil');
        $cliente->telefono_fijo = $request->get('telefono_fijo');
        $cliente->moneda_credito = $request->get('moneda_credito');
        $cliente->limite_credito = $request->get('limite_credito');
        $cliente->nombre_contacto = $request->get('nombre_contacto');
        $cliente->telefono_contacto = $request->get('telefono_contacto');
        $cliente->correo_electronico_contacto = $request->get('correo_electronico_contacto');
        $cliente->activo = ($request->get('activo') == 'ACTIVO') ? 1: 0;
        $cliente->save();

        Session::flash('success','Cliente creado.');
        return redirect()->route('ventas.cliente.index')->with('guardar', 'success');
    }

    public function edit($id)
    {
        $cliente = Cliente::findOrFail($id);
        return view('ventas.clientes.edit', [
            'cliente' => $cliente
        ]);
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();

        $rules = [
            'tipo_documento' => 'required',
            'documento' => ['required','numeric', Rule::unique('clientes','documento')->where(function ($query) {
                $query->whereIn('estado',["ACTIVO"]);
            })->ignore($id)],
            'nombre' => 'required',
            'departamento' => 'required',
            'provincia' => 'required',
            'distrito' => 'required',
            'direccion' => 'required',
            'telefono_movil' => 'required|numeric',
            'activo' => 'required'
        ];
        $message = [
            'tipo_documento.required' => 'El campo Tipo de documento es obligatorio.',
            'documento.required' => 'El campo Nro. Documento es obligatorio',
            'documento.unique' => 'El campo Nro. Documento debe ser único',
            'documento.numeric' => 'El campo Nro. Documento debe ser numérico',
            'departamento.required' => 'El campo Departamento es obligatorio',
            'provincia.required' => 'El campo Provincia es obligatorio',
            'distrito.required' => 'El campo Distrito es obligatorio',
            'direccion.required' => 'El campo Dirección completa es obligatorio',
            'telefono_movil.required' => 'El campo Teléfono móvil es obligatorio',
            'telefono_movil.numeric' => 'El campo Teléfono móvil debe ser numérico',
            'activo.required' => 'El campo Estado es obligatorio'
        ];

        Validator::make($data, $rules, $message)->validate();

        $cliente = Cliente::findOrFail($id);
        $cliente->tipo_documento = $request->get('tipo_documento');
        $cliente->documento = $request->get('documento');
        $cliente->nombre = $request->get('nombre');
        $cliente->departamento_id = str_pad($request->get('departamento'), 2, "0", STR_PAD_LEFT);
        $cliente->provincia_id = str_pad($request->get('provincia'), 4, "0", STR_PAD_LEFT);
        $cliente->distrito_id = str_pad($request->get('distrito'), 6, "0", STR_PAD_LEFT);
        $cliente->direccion = $request->get('direccion');
        $cliente->correo_electronico = $request->get('correo_electronico');
        $cliente->telefono_movil = $request->get('telefono_movil');
        $cliente->telefono_fijo = $request->get('telefono_fijo');
        $cliente->moneda_credito = $request->get('moneda_credito');
        $cliente->limite_credito = $request->get('limite_credito');
        $cliente->nombre_contacto = $request->get('nombre_contacto');
        $cliente->telefono_contacto = $request->get('telefono_contacto');
        $cliente->correo_electronico_contacto = $request->get('correo_electronico_contacto');
        $cliente->activo = ($request->get('activo') == 'ACTIVO') ? 1: 0;
        $cliente->update();

        Session::flash('success','Cliente modificado.');
        return redirect()->route('ventas.cliente.index')->with('guardar', 'success');
    }

    public function show($id)
    {
        $cliente = Cliente::findOrFail($id);
        return view('ventas.clientes.show', [
            'cliente' => $cliente
        ]);
    }

    public function destroy($id)
    {
        $cliente = Cliente::findOrFail($id);
        $cliente->estado = 'ANULADO';
        $cliente->update();

        Session::flash('success','Cliente eliminado.');
        return redirect()->route('ventas.cliente.index')->with('eliminar', 'success');
    }

    public function getDocumento(Request $request)
    {
        $data = $request->all();
        $existe = false;
        $igualPersona = false;
        if (!is_null($data['tipo_documento']) && !is_null($data['documento'])) {
            if (!is_null($data['id'])) {
                $cliente = Cliente::findOrFail($data['id']);
                if ($cliente->tipo_documento == $data['tipo_documento'] && $cliente->documento == $data['documento']) {
                    $igualPersona = true;
                } else {
                    $cliente = Cliente::where([
                        ['tipo_documento', '=', $data['tipo_documento']],
                        ['documento', $data['documento']],
                        ['estado', 'ACTIVO']
                    ])->first();
                }
            } else {
                $cliente = Cliente::where([
                    ['tipo_documento', '=', $data['tipo_documento']],
                    ['documento', $data['documento']],
                    ['estado', 'ACTIVO']
                ])->first();
            }

            if (!is_null($cliente)) {
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
