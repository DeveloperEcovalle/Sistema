<?php

namespace App\Http\Controllers\Ventas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Ventas\Cliente;
use App\Ventas\Tienda;
use Carbon\Carbon;
use Session;
use DB;
use DataTables;
use Illuminate\Support\Facades\Validator;

class TiendaController extends Controller
{

    public function getShop($id)
    {
        return datatables()->query(
            DB::table('cliente_tiendas')
            ->select('cliente_tiendas.*')->where('cliente_tiendas.estado','ACTIVO')->where('cliente_tiendas.cliente_id',$id))->toJson();
    }


    public function index($id)
    {
        $cliente = Cliente::findOrFail($id);
        return view('ventas.clientes.tiendas.index',[
            'cliente' => $cliente
        ]);
    }

    public function create($id)
    {
        $fecha_hoy = Carbon::now()->toDateString();
        $cliente = Cliente::findOrFail($id);
        return view('ventas.clientes.tiendas.create',[
            'cliente' => $cliente,
            'fecha_hoy' => $fecha_hoy,
        ]);
    }

    public function store(Request $request)
    {

        $data = $request->all();

        $rules = [
            'tipo_negocio' => 'required',
            'tipo_tienda' => 'required',
            'nombre' => 'required',
            'direccion' => 'required',
        ];
        $message = [
            'tipo_tienda.required' => 'El campo Tipo es obligatorio.',
            'tipo_negocio.required' => 'El campo Tipo de negocio es obligatorio.',
            'nombre.required' => 'El campo Nombre es obligatorio.',
            'direccion.required' => 'El campo Dirección es obligatorio.',
        ];


        $tienda = new Tienda();
        $tienda->cliente_id = $request->get('cliente_id');
        $tienda->nombre = $request->get('nombre');
        $tienda->tipo_negocio = $request->get('tipo_negocio');
        $tienda->tipo_tienda = $request->get('tipo_tienda');
        $tienda->direccion = $request->get('direccion');

        $tienda->facebook = $request->get('facebook');
        $tienda->instagram = $request->get('instagram');
        $tienda->web = $request->get('web');

        $tienda->correo = $request->get('correo');
        $tienda->telefono = $request->get('telefono');
        $tienda->celular = $request->get('celular');


        $tienda->contacto_admin_nombre = $request->get('nombre_administrador');
        $tienda->contacto_admin_cargo = $request->get('cargo_administrador');
        $tienda->contacto_admin_fecha_nacimiento = Carbon::createFromFormat('d/m/Y', $request->get('fecha_nacimiento_administrador'))->format('Y-m-d');
        $tienda->contacto_admin_correo = $request->get('correo_administrador');
        $tienda->contacto_admin_telefono = $request->get('telefono_administrador');
        $tienda->contacto_admin_celular = $request->get('celular_administrador');


        $tienda->contacto_credito_nombre = $request->get('nombre_credito');
        $tienda->contacto_credito_cargo = $request->get('cargo_credito');
        $tienda->contacto_credito_fecha_nacimiento = Carbon::createFromFormat('d/m/Y', $request->get('fecha_nacimiento_credito'))->format('Y-m-d');
        $tienda->contacto_credito_correo = $request->get('correo_credito');
        $tienda->contacto_credito_telefono = $request->get('telefono_credito');
        $tienda->contacto_credito_celular = $request->get('celular_credito');


        $tienda->contacto_vendedor_nombre = $request->get('nombre_vendedor');
        $tienda->contacto_vendedor_cargo = $request->get('cargo_vendedor');
        $tienda->contacto_vendedor_fecha_nacimiento = Carbon::createFromFormat('d/m/Y', $request->get('fecha_nacimiento_vendedor'))->format('Y-m-d');
        $tienda->contacto_vendedor_correo = $request->get('correo_vendedor');
        $tienda->contacto_vendedor_telefono = $request->get('telefono_vendedor');
        $tienda->contacto_vendedor_celular = $request->get('celular_vendedor');


        $tienda->hora_inicio = $request->get('hora_inicio');
        $tienda->hora_fin = $request->get('hora_termino');


        $tienda->condicion_reparto = $request->get('condicion_reparto');

        
        $tienda->nombre_transporte_oficina = $request->get('nombre_transporte_oficina');
        $tienda->direccion_transporte_oficina = $request->get('direccion_transporte_oficina');
        $tienda->responsable_pago_flete = $request->get('responsable_pago_flete');
        $tienda->responsable_pago = $request->get('responsable_pago');

        $tienda->dni_responsable_recoger = $request->get('dni_responsable_recoger');
        $tienda->estado_responsable_recoger = $request->get('estado_responsable_recoger');
        $tienda->nombre_responsable_recoger = $request->get('nombre_responsable_recoger');
        $tienda->telefono_responsable_recoger = $request->get('telefono_responsable_recoger');
        $tienda->observacion_envio = $request->get('observacion_envio');

        $tienda->nombre_transporte_domicilio = $request->get('nombre_transporte_domicilio');
        $tienda->direccion_domicilio = $request->get('direccion_domicilio');
        $tienda->nombre_contacto_recoger = $request->get('nombre_contacto_recoger');
        $tienda->telefono_contacto_recoger = $request->get('telefono_contacto_recoger');
        $tienda->observacion_domicilio = $request->get('observacion_domicilio');


        $tienda->save();

        Session::flash('success','Tienda creada.');
        return redirect()->route('clientes.tienda.index',$request->get('cliente_id'))->with('guardar', 'success');
    }

    public function destroy($id)
    {
        
        $tienda = Tienda::findOrFail($id);
        $tienda->estado = 'ANULADO';
        $tienda->update();

        Session::flash('success','Tienda eliminada.');
        return redirect()->route('clientes.tienda.index',$tienda->cliente_id)->with('eliminar', 'success');

    }

    
    public function edit($id)
    {
        $tienda = Tienda::findOrFail($id);
        
        return view('ventas.clientes.tiendas.edit', [
            'tienda' => $tienda,
        ]);

    }

    public function update(Request $request, $id)
    {

        $data = $request->all();

        $rules = [
            'tipo_negocio' => 'required',
            'tipo_tienda' => 'required',
            'nombre' => 'required',
            'direccion' => 'required',
        ];
        $message = [
            'tipo_tienda.required' => 'El campo Tipo es obligatorio.',
            'tipo_negocio.required' => 'El campo Tipo de negocio es obligatorio.',
            'nombre.required' => 'El campo Nombre es obligatorio.',
            'direccion.required' => 'El campo Dirección es obligatorio.',
        ];

        $tienda =  Tienda::findOrFail($id);
        $tienda->nombre = $request->get('nombre');
        $tienda->tipo_negocio = $request->get('tipo_negocio');
        $tienda->tipo_tienda = $request->get('tipo_tienda');
        $tienda->direccion = $request->get('direccion');

        $tienda->facebook = $request->get('facebook');
        $tienda->instagram = $request->get('instagram');
        $tienda->web = $request->get('web');

        $tienda->correo = $request->get('correo');
        $tienda->telefono = $request->get('telefono');
        $tienda->celular = $request->get('celular');


        $tienda->contacto_admin_nombre = $request->get('nombre_administrador');
        $tienda->contacto_admin_cargo = $request->get('cargo_administrador');
        $tienda->contacto_admin_fecha_nacimiento = Carbon::createFromFormat('d/m/Y', $request->get('fecha_nacimiento_administrador'))->format('Y-m-d');
        $tienda->contacto_admin_correo = $request->get('correo_administrador');
        $tienda->contacto_admin_telefono = $request->get('telefono_administrador');
        $tienda->contacto_admin_celular = $request->get('celular_administrador');


        $tienda->contacto_credito_nombre = $request->get('nombre_credito');
        $tienda->contacto_credito_cargo = $request->get('cargo_credito');
        $tienda->contacto_credito_fecha_nacimiento = Carbon::createFromFormat('d/m/Y', $request->get('fecha_nacimiento_credito'))->format('Y-m-d');
        $tienda->contacto_credito_correo = $request->get('correo_credito');
        $tienda->contacto_credito_telefono = $request->get('telefono_credito');
        $tienda->contacto_credito_celular = $request->get('celular_credito');


        $tienda->contacto_vendedor_nombre = $request->get('nombre_vendedor');
        $tienda->contacto_vendedor_cargo = $request->get('cargo_vendedor');
        $tienda->contacto_vendedor_fecha_nacimiento = Carbon::createFromFormat('d/m/Y', $request->get('fecha_nacimiento_vendedor'))->format('Y-m-d');
        $tienda->contacto_vendedor_correo = $request->get('correo_vendedor');
        $tienda->contacto_vendedor_telefono = $request->get('telefono_vendedor');
        $tienda->contacto_vendedor_celular = $request->get('celular_vendedor');

        
        $tienda->hora_inicio = $request->get('hora_inicio');
        $tienda->hora_fin = $request->get('hora_termino');


        $tienda->condicion_reparto = $request->get('condicion_reparto');

        
        $tienda->nombre_transporte_oficina = $request->get('nombre_transporte_oficina');
        $tienda->direccion_transporte_oficina = $request->get('direccion_transporte_oficina');
        $tienda->responsable_pago_flete = $request->get('responsable_pago_flete');
        $tienda->responsable_pago = $request->get('responsable_pago');

        $tienda->dni_responsable_recoger = $request->get('dni_responsable_recoger');
        $tienda->estado_responsable_recoger = $request->get('estado_responsable_recoger');
        $tienda->nombre_responsable_recoger = $request->get('nombre_responsable_recoger');
        $tienda->telefono_responsable_recoger = $request->get('telefono_responsable_recoger');
        $tienda->observacion_envio = $request->get('observacion_envio');

        $tienda->nombre_transporte_domicilio = $request->get('nombre_transporte_domicilio');
        $tienda->direccion_domicilio = $request->get('direccion_domicilio');
        $tienda->nombre_contacto_recoger = $request->get('nombre_contacto_recoger');
        $tienda->telefono_contacto_recoger = $request->get('telefono_contacto_recoger');
        $tienda->observacion_domicilio = $request->get('observacion_domicilio');

        $tienda->update();

        Session::flash('success','Tienda modificada.');
        return redirect()->route('clientes.tienda.index',$tienda->cliente_id)->with('modificar', 'success');
    }

    
    public function show($id)
    {
        
        $tienda = Tienda::findOrFail($id);
        return view('ventas.clientes.tiendas.show', [
            'tienda' => $tienda
        ]);

    }


    
}
