<?php

namespace App\Http\Controllers\Ventas;

use App\Http\Controllers\Controller;
use App\Ventas\Cliente;
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
        $clientes = Cliente::where('estado','ACTIVO')->orderBy('clientes.id', 'desc')->get();
        $coleccion = collect([]);
        foreach($clientes as $cliente) {
            $coleccion->push([
                'id' => $cliente->id,
                'documento' => $cliente->getDocumento(),
                'nombre' => ($cliente->tipo_documento == 'RUC') ? '-' : $cliente->nombre ,
                'razon_social' => ($cliente->tipo_documento == 'RUC') ? $cliente->nombre : '-',
                'telefono_movil' => $cliente->telefono_movil,
                'departamento' => $cliente->getDepartamento(),
                'provincia' => $cliente->getProvincia(),
                'distrito' => $cliente->getDistrito(),
                'zona' => $cliente->getDepartamentoZona(),         
                ]);
        }
        return DataTables::of($coleccion)->toJson();
    }

    public function create()
    {
        $action = route('ventas.cliente.store');
        $cliente = new Cliente();
        return view('ventas.clientes.create')->with(compact('action','cliente'));
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
            'tipo_cliente' => 'required',
            'departamento' => 'required',
            'zona' => 'required',
            'provincia' => 'required',
            'distrito' => 'required',
            'direccion' => 'required',
            'telefono_movil' => 'required|numeric',
            'activo' => 'required',
            'direccion_negocio' => 'required',
        ];
        $message = [
            'tipo_documento.required' => 'El campo Tipo de documento es obligatorio.',
            'documento.required' => 'El campo Nro. Documento es obligatorio',
            'documento.unique' => 'El campo Nro. Documento debe ser único',
            'documento.numeric' => 'El campo Nro. Documento debe ser numérico',
            'departamento.required' => 'El campo Departamento es obligatorio',
            'zona.required' => 'El campo Zona es obligatorio',
            'provincia.required' => 'El campo Provincia es obligatorio',
            'distrito.required' => 'El campo Distrito es obligatorio',
            'direccion.required' => 'El campo Dirección completa es obligatorio',
            'telefono_movil.required' => 'El campo Teléfono móvil es obligatorio',
            'telefono_movil.numeric' => 'El campo Teléfono móvil debe ser numérico',
            'activo.required' => 'El campo Estado es obligatorio',
            'direccion_negocio.required' => 'El campo Dirección de negocio completa es obligatorio',

        ];

        Validator::make($data, $rules, $message)->validate();
        $arrayDatos = $request->all();
        if ($arrayDatos['fecha_aniversario']=="-"){ unset($arrayDatos['fecha_aniversario']); }else{$arrayDatos['fecha_aniversario']= Carbon::createFromFormat('d/m/Y', $arrayDatos['fecha_aniversario'])->format('Y-m-d');}
        $cliente = new Cliente($arrayDatos);
        $cliente->tipo_documento = $request->get('tipo_documento');

        $cliente->documento = $request->get('documento');
        $cliente->tabladetalles_id = $request->input('tipo_cliente');
        $cliente->nombre = $request->get('nombre');
        $cliente->codigo = $request->get('codigo');
        $cliente->zona = $request->get('zona');
        $cliente->nombre_comercial = $request->get('nombre_comercial');

        $cliente->departamento_id = str_pad($request->get('departamento'), 2, "0", STR_PAD_LEFT);
        $cliente->provincia_id = str_pad($request->get('provincia'), 4, "0", STR_PAD_LEFT);
        $cliente->distrito_id = str_pad($request->get('distrito'), 6, "0", STR_PAD_LEFT);
        $cliente->direccion = $request->get('direccion');
        $cliente->correo_electronico = $request->get('correo_electronico');
        $cliente->telefono_movil = $request->get('telefono_movil');
        $cliente->telefono_fijo = $request->get('telefono_fijo');
        $cliente->activo = $request->get('activo');
        
        $cliente->facebook = $request->get('facebook');
        $cliente->instagram = $request->get('instagram');
        $cliente->web = $request->get('web');

        $cliente->hora_inicio = $request->get('hora_inicio');
        $cliente->hora_termino = $request->get('hora_termino');


        $cliente->nombre_propietario = $request->get('nombre_propietario');
        $cliente->direccion_propietario = $request->get('direccion_propietario');

        if ( $request->get('fecha_nacimiento_prop') != "-") {
            $cliente->fecha_nacimiento_prop  = Carbon::createFromFormat('d/m/Y', $request->get('fecha_nacimiento_prop'))->format('Y-m-d');
        }else{
            $cliente->fecha_nacimiento_prop  = NULL;
        }

        $cliente->celular_propietario   = $request->get('celular_propietario');
        $cliente->correo_propietario  = $request->get('correo_propietario');

        $cliente->save();

        //Registro de actividad
        $descripcion = "SE AGREGÓ EL CLIENTE CON EL NOMBRE: ". $cliente->nombre;
        $gestion = "CLIENTES";
        crearRegistro($cliente, $descripcion , $gestion);

        Session::flash('success','Cliente creado.');
        return redirect()->route('ventas.cliente.index')->with('guardar', 'success');
    }

    public function edit($id)
    {
        $cliente = Cliente::findOrFail($id);
        
        $put = True;
        $action = route('ventas.cliente.update', $id);

        return view('ventas.clientes.edit', [
            'cliente' => $cliente,
            'action' => $action,
            'put' => $put,
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
            'zona' => 'required',
            'departamento' => 'required',
            'provincia' => 'required',
            'distrito' => 'required',
            'direccion' => 'required',
            'telefono_movil' => 'required|numeric',
            'activo' => 'required',
            'correo_electronico' => 'required|email'
        ];
        $message = [
            'tipo_documento.required' => 'El campo Tipo de documento es obligatorio.',
            'documento.required' => 'El campo Nro. Documento es obligatorio',
            'documento.unique' => 'El campo Nro. Documento debe ser único',
            'documento.numeric' => 'El campo Nro. Documento debe ser numérico',
            'departamento.required' => 'El campo Departamento es obligatorio',
            'provincia.required' => 'El campo Provincia es obligatorio',
            'zona.required' => 'El campo Zona es obligatorio',
            'distrito.required' => 'El campo Distrito es obligatorio',
            'direccion.required' => 'El campo Dirección completa es obligatorio',
            'telefono_movil.required' => 'El campo Teléfono móvil es obligatorio',
            'telefono_movil.numeric' => 'El campo Teléfono móvil debe ser numérico',
            'activo.required' => 'El campo Estado es obligatorio',
            'correo_electronico.required' => 'El campo Correo electrónico es obligatorio',
            'correo_electronico.email' => 'El campo Correo electrónico es de tipo Email (@).'
        ];

        Validator::make($data, $rules, $message)->validate();

        $cliente = Cliente::findOrFail($id);
        $cliente->tipo_documento = $request->get('tipo_documento');
        $cliente->documento = $request->get('documento');
        $cliente->nombre = $request->get('nombre');
        
        $cliente->codigo = $request->get('codigo');
        $cliente->zona = $request->get('zona');
        $cliente->nombre_comercial = $request->get('nombre_comercial');

        $cliente->tabladetalles_id = $request->input('tipo_cliente');
        $cliente->departamento_id = str_pad($request->get('departamento'), 2, "0", STR_PAD_LEFT);
        $cliente->provincia_id = str_pad($request->get('provincia'), 4, "0", STR_PAD_LEFT);
        $cliente->distrito_id = str_pad($request->get('distrito'), 6, "0", STR_PAD_LEFT);
        $cliente->direccion = $request->get('direccion');
        $cliente->correo_electronico = $request->get('correo_electronico');
        $cliente->telefono_movil = $request->get('telefono_movil');
        $cliente->telefono_fijo = $request->get('telefono_fijo');

        $cliente->direccion_negocio = $request->get('direccion_negocio');
        if($request->get('fecha_aniversario')!="-"){
            $cliente->fecha_aniversario = Carbon::createFromFormat('d/m/Y', $request->get('fecha_aniversario'))->format('Y-m-d');
        }
        $cliente->activo = $request->get('activo');
        $cliente->observaciones = $request->get('observaciones');
        $cliente->facebook = $request->get('facebook');
        $cliente->instagram = $request->get('instagram');
        $cliente->web = $request->get('web');

        $cliente->hora_inicio = $request->get('hora_inicio');
        $cliente->hora_termino = $request->get('hora_termino');


        $cliente->nombre_propietario = $request->get('nombre_propietario');
        $cliente->direccion_propietario = $request->get('direccion_propietario');



        if ( $request->get('fecha_nacimiento_prop') != "-") {
            $cliente->fecha_nacimiento_prop  = Carbon::createFromFormat('d/m/Y', $request->get('fecha_nacimiento_prop'))->format('Y-m-d');
        }else{
            $cliente->fecha_nacimiento_prop  = NULL;
        }

        
        $cliente->celular_propietario   = $request->get('celular_propietario');
        $cliente->correo_propietario  = $request->get('correo_propietario');
        $cliente->update();

        //Registro de actividad
        $descripcion = "SE MODIFICÓ EL CLIENTE CON EL NOMBRE: ". $cliente->nombre;
        $gestion = "CLIENTES";
        modificarRegistro($cliente, $descripcion , $gestion);

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

        //Registro de actividad
        $descripcion = "SE ELIMINÓ EL CLIENTE CON EL NOMBRE: ". $cliente->nombre;
        $gestion = "CLIENTES";
        eliminarRegistro($cliente, $descripcion , $gestion);

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

    public function getCustomer(Request $request)
    {
        $data = $request->all();
        $cliente_id = $data['cliente_id'];

        $cliente = Cliente::findOrFail($cliente_id);
        return $cliente;
    }
}
