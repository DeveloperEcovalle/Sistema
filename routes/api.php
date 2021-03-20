<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//CLIENTES 
Route::get('clientes',function ()
{
    $clientes = DB::table('clientes')
        ->select('clientes.*')->distinct()->get();

    return response()->json([
        'clientes' => $clientes,
    ]);

});

//CLIENTES REALIZARON UNA VENTA PARAMETROS 
// [
//     ZONA => CENTRO,
//     DEPARTAMENTO => ANCASH,
//     MONTO => 10000.00 VALORES MAYORES A ESA VENTA
//     fecha_fin =>
//     fecha_inicio =>
//     comparacion => 
// ]

Route::get('clientes/parametros',function (Request $request)
{

        $clientes = DB::table('clientes')
                    ->when($request->get('zona'), function ($query, $request) {
                        return $query->where('clientes.zona', $request);
                    })
                    ->when($request->get('departamento'), function ($query, $request) {
                        return $query->where('clientes.departamento_id', $request);
                    });

        $clientes = $clientes->get();
        $coleccion = collect([]);       
        
        foreach ($clientes as $cliente) {
            $venta_monto = DB::table('cotizacion_documento')

                        ->where('cotizacion_documento.cliente_id',$cliente->id)
                    
                        ->when($request->get('fecha_inicio'), function ($query, $request) {
                            return $query->where('cotizacion_documento.fecha_documento','>=',$request);
                        })

                        ->when($request->get('fecha_fin'), function ($query, $request) {
                            return $query->where('cotizacion_documento.fecha_documento','>=',$request);
                        })

                        ->when($request->all(), function ($query, $request) {
                            if ( !empty($request['comparacion'])  &&  !empty($request['monto'])  ) {
                                return $query->where('cotizacion_documento.total', $request['comparacion'], $request['monto']);
                            }
                        })
                        ->sum('cotizacion_documento.total');

            $coleccion->push([
                'cliente' => $cliente,
                'total' => $venta_monto
                ]);
        }

        return response()->json($coleccion);
});


//COLABORADORES
Route::get('colaboradores',function ()
{
    $colaboradores = DB::table('colaboradores')
        ->join('personas','colaboradores.persona_id','personas.id')
        ->select('colaboradores.*','personas.*')->distinct()
        ->get();

    return response()->json([
        'colaboradores' => $colaboradores,
    ]);

});

//TABLAS GENERALES
Route::get('tabla/detalles/{id}',function ($id)
{
    $detalles = DB::table('tabladetalles')
        ->select('tabladetalles.id', 'tabladetalles.descripcion','tabladetalles.simbolo')
        ->where('tabladetalles.tabla_id',$id )
        ->where('tabladetalles.estado','ACTIVO')
        ->get();

    return response()->json([
        'detalles' => $detalles,
    ]);

});

//DEPARTAMENTO 'ZONA'
Route::get('departamentos/{zona}',function ($zona)
{
    $departamentos = DB::table('departamentos')
        ->select('departamentos.id','departamentos.nombre')
        ->where('departamentos.zona',$zona)
        ->get();

    return response()->json([
        'departamentos' => $departamentos,
    ]);

});







