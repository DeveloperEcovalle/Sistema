<?php

namespace App\Http\Controllers\Ventas;

use App\Http\Controllers\Controller;
use App\Mantenimiento\Empresa;
use App\Produccion\Producto;
use App\Ventas\Cliente;
use App\Ventas\Cotizacion;
use App\Ventas\CotizacionDetalle;
use Illuminate\Http\Request;
use DataTables;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Session;
use Illuminate\Support\Facades\Validator;

class CotizacionController extends Controller
{
    public function index()
    {
        return view('ventas.cotizaciones.index');
    }

    public function getTable()
    {
        $cotizaciones = Cotizacion::where('estado', '<>', 'ANULADO')->get();
        $coleccion = collect([]);
        foreach($cotizaciones as $cotizacion) {
            $coleccion->push([
                'id' => $cotizacion->id,
                'empresa' => $cotizacion->empresa->razon_social,
                'cliente' => $cotizacion->cliente->nombre,
                'fecha_documento' => Carbon::parse($cotizacion->fecha_documento)->format( 'd/m/Y'),
                'total' => $cotizacion->total,
                'estado' => $cotizacion->estado
            ]);
        }
        return DataTables::of($coleccion)->toJson();
    }

    public function create()
    {
        $empresas = Empresa::where('estado', 'ACTIVO')->get();
        $clientes = Cliente::where('estado', 'ACTIVO')->get();
        $fecha_hoy = Carbon::now()->toDateString();
        $productos = Producto::where('estado', 'ACTIVO')->get();

        return view('ventas.cotizaciones.create', compact('empresas', 'clientes', 'fecha_hoy', 'productos'));
    }

    public function store(Request $request)
    {
        $data = $request->all();
        //dd($request);

        $rules = [
            'empresa' => 'required',
            'cliente' => 'required',
            'moneda' => 'required',
            'fecha_documento' => 'required|date_format:d/m/Y',
            'fecha_atencion' => 'nullable|date_format:d/m/Y',
            'monto_sub_total' => 'required',
            'monto_total_igv' => 'required',
            'monto_total' => 'required',
            'detalles' => 'required|string'
        ];

        $message = [
            'empresa.required' => 'El campo Empresa es obligatorio',
            'cliente.unique' => 'El campo Cliente es obligatorio',
            'moneda' => 'El campo Moneda es obligatorio',
            'fecha_documento.required' => 'El campo Fecha de Documento es obligatorio',
            'fecha_documento.date_format:d/m/Y' => 'El formato de la Fecha de Documento es incorrecto',
            'fecha_atencion.date_format:d/m/Y' => 'El formato de la Fecha de Atención es incorrecto',
            'monto_sub_total.required' => 'El campo Total Afecto es obligatorio',
            'monto_total_igv.required' => 'El campo Total IGV es obligatorio',
            'monto_total.required' => 'El campo Total es obligatorio',
            'detalles.required' => 'Debe exitir al menos un detalle de la cotización',
            'detalles.string' => 'El formato de texto de los detalles es incorrecto',
        ];

        Validator::make($data, $rules, $message)->validate();

        DB::transaction(function () use ($request) {

            $cotizacion = new Cotizacion();
            $cotizacion->empresa_id = $request->get('empresa');
            $cotizacion->cliente_id = $request->get('cliente');
            $cotizacion->moneda = $request->get('moneda');
            $cotizacion->fecha_documento = Carbon::createFromFormat('d/m/Y', $request->get('fecha_documento'))->format('Y-m-d');
            $cotizacion->fecha_atencion = Carbon::createFromFormat('d/m/Y', $request->get('fecha_atencion'))->format('Y-m-d');
            $cotizacion->total_afecto = $request->get('monto_total_igv');
            $cotizacion->sub_total = $request->get('monto_sub_total');
            $cotizacion->total_igv = $request->get('monto_total_igv');
            $cotizacion->total = $request->get('monto_total');
            $cotizacion->total_exento = $request->get('monto_total_igv');
            $cotizacion->user_id = Auth::id();
            $cotizacion->save();

            foreach (json_decode($request->get('detalles')) as $detalle) {
                $cotizacion_detalle = new CotizacionDetalle();
                $cotizacion_detalle->cotizacion_id = $cotizacion->id;
                $cotizacion_detalle->producto_id = $detalle->producto_id;
                $cotizacion_detalle->cantidad = $detalle->cantidad;
                $cotizacion_detalle->precio = $detalle->precio;
                $cotizacion_detalle->importe = $detalle->cantidad * $detalle->precio;
                $cotizacion_detalle->save();
            }

        });

        Session::flash('success','Cotización creada.');
        return redirect()->route('ventas.cotizacion.index')->with('guardar', 'success');
    }

    public function edit($id)
    {
        $cotizacion = Cotizacion::findOrFail($id);
        $empresas = Empresa::where('estado', 'ACTIVO')->get();
        $clientes = Cliente::where('estado', 'ACTIVO')->get();
        $fecha_hoy = Carbon::now()->toDateString();
        $productos = Producto::where('estado', 'ACTIVO')->get();

        $detalles = [];
        foreach ($cotizacion->detalles->where('estado', 'ACTIVO') as $detalle) {
            $data = [
                'id' => (string)$detalle->id,
                'producto_id' => (string)$detalle->producto_id,
                'producto' => $detalle->producto->codigo.'-'.$detalle->producto->nombre,
                'cantidad' => (int)$detalle->cantidad,
                'precio' => (float)$detalle->precio,
                'importe' => $detalle->importe
            ];
            array_push($detalles, $data);
        }

        return view('ventas.cotizaciones.edit', [
            'cotizacion' => $cotizacion,
            'empresas' => $empresas,
            'clientes' => $clientes,
            'fecha_hoy' => $fecha_hoy,
            'productos' => $productos,
            'detalles' => json_encode($detalles)
        ]);
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();

        $rules = [
            'empresa' => 'required',
            'cliente' => 'required',
            'moneda' => 'required',
            'fecha_documento' => 'required|date_format:d/m/Y',
            'fecha_atencion' => 'nullable|date_format:d/m/Y',
            'monto_sub_total' => 'required',
            'monto_total_igv' => 'required',
            'monto_total' => 'required',
            'detalles' => 'required|string'
        ];

        $message = [
            'empresa.required' => 'El campo Empresa es obligatorio',
            'cliente.unique' => 'El campo Cliente es obligatorio',
            'moneda' => 'El campo Moneda es obligatorio',
            'fecha_documento.required' => 'El campo Fecha de Documento es obligatorio',
            'fecha_documento.date_format:d/m/Y' => 'El formato de la Fecha de Documento es incorrecto',
            'fecha_atencion.date_format:d/m/Y' => 'El formato de la Fecha de Atención es incorrecto',
            'monto_sub_total.required' => 'El campo Total Afecto es obligatorio',
            'monto_total_igv.required' => 'El campo Total IGV es obligatorio',
            'monto_total.required' => 'El campo Total es obligatorio',
            'detalles.required' => 'Debe exitir al menos un detalle de la cotización',
            'detalles.string' => 'El formato de texto de los detalles es incorrecto',
        ];

        Validator::make($data, $rules, $message)->validate();

        DB::transaction(function () use ($request) {
            $cotizacion = new Cotizacion();
            $cotizacion->empresa_id = $request->get('empresa');
            $cotizacion->cliente_id = $request->get('cliente');
            $cotizacion->moneda = $request->get('moneda');
            $cotizacion->fecha_documento = Carbon::createFromFormat('d/m/Y', $request->get('fecha_documento'))->format('Y-m-d');
            $cotizacion->fecha_atencion = Carbon::createFromFormat('d/m/Y', $request->get('fecha_atencion'))->format('Y-m-d');
            $cotizacion->total_afecto = $request->get('monto_total_igv');
            $cotizacion->sub_total = $request->get('monto_sub_total');
            $cotizacion->total_igv = $request->get('monto_total_igv');
            $cotizacion->total = $request->get('monto_total');
            $cotizacion->total_exento = $request->get('monto_total_igv');
            $cotizacion->user_id = Auth::id();
            $cotizacion->save();

            foreach (json_decode($request->get('detalles')) as $detalle) {
                if (!is_null($detalle->id)) {
                    // Actualizamos
                    $cotizacion_detalle = $cotizacion->detalles->firstWhere('id', $detalle->id);
                    $cotizacion_detalle->cantidad = $detalle->cantidad;
                    $cotizacion_detalle->precio = $detalle->precio;
                    $cotizacion_detalle->importe = $detalle->importe;
                    $cotizacion_detalle->update();
                } else {
                    // Creamos
                    $cotizacion_detalle = new CotizacionDetalle();
                    $cotizacion_detalle->cotizacion_id = $cotizacion->id;
                    $cotizacion_detalle->producto_id = $detalle->producto_id;
                    $cotizacion_detalle->cantidad = $detalle->cantidad;
                    $cotizacion_detalle->precio = $detalle->precio;
                    $cotizacion_detalle->importe = $detalle->importe;
                    $cotizacion_detalle->save();
                }
            }

        });

        Session::flash('success','Cotización modificada.');
        return redirect()->route('ventas.cotizacion.index')->with('guardar', 'success');
    }

    public function show($id)
    {
        $cotizacion = Cotizacion::findOrFail($id);
        return view('ventas.cotizaciones.show', [
            'cotizacion' => $cotizacion
        ]);
    }

    public function destroy($id)
    {
        $cotizacion = Cotizacion::findOrFail($id);
        $cotizacion->estado = 'ANULADO';
        $cotizacion->update();

        $cotizacion->detalles()->update(['estado'=> 'ANULADO']);

        Session::flash('success','Cotización eliminada.');
        return redirect()->route('ventas.cotizacion.index')->with('eliminar', 'success');
    }
}
