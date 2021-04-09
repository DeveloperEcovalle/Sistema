<?php

namespace App\Http\Controllers\Produccion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Produccion\Orden;
use App\Produccion\OrdenDetalleLote;
use App\Produccion\Devolucion;
use Session;
use Redirect;
use Illuminate\Support\Facades\Validator;

use App\Movimientos\MovimientoAlmacen;

class OrdenDetalleDevolucionController extends Controller
{
    public function loteReturns(Request $request)
    {
        $orden = Orden::findOrFail($request->get('orden_id'));
        $ordenDetalleLotes = OrdenDetalleLote::where('orden_produccion_detalle_id', $request->get('ordenDetalle'))->get();  
        return view('produccion.ordenes.devoluciones.edit',[
            'ordenDetalleLotes' => $ordenDetalleLotes,
            'orden' => $orden,
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->all();
        $rules = [
            'cantidad_buen_estado' => 'required',
            'cantidad_mal_estado' => 'required',
            'ordenDetalleLote_id' => 'required',
        ];

        $message = [
            'cantidad_buen_estado.required' => 'El campo Cantidad en Buen Estado es obligatorio',
            'cantidad_mal_estado.required' => 'El campo Cantidad en Mal Estado es obligatorio',
        ];
        
        Validator::make($data, $rules, $message)->validate();
        
        $devoluciones = Devolucion::where('detalle_lote_id', $request->get('ordenDetalleLote_id'))->get();
        //ELIMINAR MOVIMIENTOS
        $movimientosAlmacen = MovimientoAlmacen::where('orden_produccion_detalle_id',$devoluciones->first()->detalleLote->orden_produccion_detalle_id)
                                                ->where('nota','DEVOLUCION DE PROD')                     
                                                ->delete();
        $movimientosAlmacen = MovimientoAlmacen::where('orden_produccion_detalle_id',$devoluciones->first()->detalleLote->orden_produccion_detalle_id)
                                                ->where('nota','MAL ESTADO')                     
                                                ->delete();
        foreach ($devoluciones as $devolucion) {
            if ($devolucion->estado == '1'){
                $devolucion->cantidad = $request->get('cantidad_buen_estado');
                $devolucion->update();


                //MOVIMIENTOS
                MovimientoAlmacen::create([
                    'orden_produccion_detalle_id' =>  $devolucion->detalleLote->orden_produccion_detalle_id,
                    'almacen_inicio_id' => $devolucion->detalleLote->loteArticulo->articulo->almacen->id,
                    'almacen_final_id' => 3,
                    'cantidad' =>  $devolucion->cantidad + $request->get('cantidad_mal_estado') ,
                    'nota' => 'DEVOLUCION DE PROD',
                    'observacion' => 'DEV '.((float) $devolucion->cantidad).' BUENA(S)  Y '.$request->get('cantidad_mal_estado').' MAL(AS)',
                    'usuario_id' =>  auth()->user()->id,
                    'movimiento' => 'INGRESO',
                    'articulo_id' => $devolucion->detalleLote->loteArticulo->articulo->id,
                ]);
            }
            if ($devolucion->estado == '0') {
                $devolucion->cantidad = $request->get('cantidad_mal_estado');
                $devolucion->update();
                //MOVIMIENTOS
                MovimientoAlmacen::create([
                    'orden_produccion_detalle_id' =>  $devolucion->detalleLote->orden_produccion_detalle_id,
                    'almacen_inicio_id' => $devolucion->detalleLote->loteArticulo->articulo->almacen->id,
                    'almacen_final_id' => 1,
                    'cantidad' => $devolucion->cantidad ,
                    'nota' => 'MAL ESTADO',
                    'observacion' => 'ESTA MAL SE MALOGRO EN PROD',
                    'usuario_id' =>  auth()->user()->id,
                    'movimiento' => 'SALIDA',
                    'articulo_id' => $devolucion->detalleLote->loteArticulo->articulo->id,
                ]);
            }
        }
        Session::flash('success','Devoluciones Ingresadas.');
        return redirect::back()->with('guardar', 'success');
    }

    public function quantity(Request $request)
    {
        $data = $request->all();
        $lote_id = $data['lote_id'];
        $devoluciones = '';
        if ($lote_id) {
            $devoluciones = Devolucion::where('detalle_lote_id', $lote_id)->get();
        }
        return response()->json($devoluciones);
    }


}
