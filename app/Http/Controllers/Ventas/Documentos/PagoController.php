<?php

namespace App\Http\Controllers\Ventas\Documentos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Pos\Caja;
use DataTables;
use Carbon\Carbon;
use App\Ventas\Documento\Documento;

class PagoController extends Controller
{
    public function index($id)
    {
        $documento = Documento::findOrFail($id);
        // dd($documento);
        // foreach(tipos_moneda() as $moneda){
        //     if ($moneda->descripcion == $documento->moneda) {
        //         $tipo_moneda= $moneda->simbolo;
        //     }
        // }

        // $monto = calcularMontoDocumento($documento->id);
        // $suma_detalle_pago = calcularMontosAcuentaDocumentos($id);
        
        
        // CALCULAR SALDO
        
        // $saldo = $monto - $suma_detalle_pago;
        


        
        // if ($suma_detalle_pago == $monto) {;
        //     $documento->estado = "PAGADA";
        //     $documento->update();
        // }else{
        //     $documento->estado = "PENDIENTE";
        //     $documento->update();
        // }


        return view('ventas.documentos.pagos.index',[
            'documento' => $documento,
            // 'moneda' => $tipo_moneda,
            // 'monto' => $monto,
            // 'acuenta' =>  number_format($suma_detalle_pago, 2, '.', ''),
            // 'saldo' => number_format($saldo, 2, '.', ''),
        ]);
    }

    public function create($id)
    {
        $documento = Documento::findOrFail($id);
        // dd($documento);
        $fecha_hoy = Carbon::now()->toDateString();
        // $monedas =  tipos_moneda();
        $cajas = Caja::where('moneda','=','SOLES')->where('estado','APERTURADA')->get();
        // $suma_detalle_pago = calcularMontosAcuentaDocumentos($id);
        // $monto = calcularMontoDocumento($documento->id);
        // $monto_restante = $monto - $suma_detalle_pago;
        $monto = 0 ;


        return view('ventas.documentos.pagos.create',[
            'documento' => $documento,
            'fecha_hoy' => $fecha_hoy,
            // 'monedas' => $monedas,
            'cajas' => $cajas,
            // 'monto' =>  number_format($monto_restante, 2, '.', ''),
            'monto' => $monto,

        ]);
    }
}
