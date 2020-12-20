<?php

namespace App\Http\Controllers\Ventas;

use App\Http\Controllers\Controller;
use App\Mantenimiento\Empresa;
use App\Ventas\Cliente;
use App\Ventas\Cotizacion;
use Illuminate\Http\Request;
use DataTables;
use Carbon\Carbon;
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
                'fecha_documento' => $cotizacion->fecha_documento,
                'total' => $cotizacion->total,
                'monto' => $cotizacion->monto
            ]);
        }
        return DataTables::of($coleccion)->toJson();
    }

    public function create()
    {
        $empresas = Empresa::where('estado', 'ACTIVO')->get();
        $clientes = Cliente::where('estado', 'ACTIVO')->get();
        return view('ventas.cotizaciones.create', compact('empresas', 'clientes'));
    }

    public function store(Request $request)
    {

    }

    public function edit($id)
    {
        $cotizacion = Cotizacion::findOrFail($id);
        return view('ventas.cotizaciones.edit', [
            'cotizacion' => $cotizacion
        ]);
    }

    public function update(Request $request, $id)
    {

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
