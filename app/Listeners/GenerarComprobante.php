<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use App\ventas\Documento\Detalle;
//CONVERTIR DE NUMEROS A LETRAS
use Luecano\NumeroALetras\NumeroALetras;

class GenerarComprobante
{

    public function handle($event)
    {
        //ARREGLO COMPROBANTE
        $arreglo_comprobante = array(
            "tipoOperacion" => $event->documento->tipoOperacion(),
            "tipoDoc"=> $event->documento->tipoDocumento(),
            "serie" => $event->serie,
            "correlativo" => $event->documento->correlativo,
            "fechaEmision" => self::obtenerFecha($event),
            "observacion" => $event->documento->observacion,
            "tipoMoneda" => $event->documento->simboloMoneda(),
            "client" => array(
                "tipoDoc" => $event->documento->cliente->tipoDocumento(),
                "numDoc" => $event->documento->cliente->documento,
                "rznSocial" => $event->documento->cliente->nombre,
                "address" => array(
                    "direccion" => $event->documento->cliente->direccion,
                )),
            "company" => array(
                "ruc" =>  $event->documento->empresa->ruc,
                "razonSocial" => $event->documento->empresa->razon_social,
                "address" => array(
                    "direccion" => $event->documento->empresa->direccion_fiscal,
                )),
            "mtoOperGravadas" => $event->documento->sub_total,
            "mtoOperExoneradas" => 0,
            "mtoIGV" => $event->documento->total_igv,
            
            "valorVenta" => $event->documento->sub_total,
            "totalImpuestos" => $event->documento->total_igv,
            "mtoImpVenta" => $event->documento->total ,
            "ublVersion" => "2.1",
            "details" => self::obtenerProductos($event->documento->id),
            "legends" =>  self::obtenerLeyenda($event),
        );

        return json_encode($arreglo_comprobante);


    }

    public function obtenerLeyenda($event)
    {
        $formatter = new NumeroALetras();
        $convertir = $formatter->toInvoice($event->documento->total, 2, 'SOLES');

        //CREAR LEYENDA DEL COMPROBANTE
        $arrayLeyenda = Array();
        $arrayLeyenda[] = array(  
            "code" => "1000",
            "value" => $convertir
        );
        return $arrayLeyenda;
    }

    public function obtenerProductos($id)
    {
        $detalles = Detalle::where('documento_id',$id)->get();
        $arrayProductos = Array();
        for($i = 0; $i < count($detalles); $i++){

            $arrayProductos[] = array(
                "codProducto" => $detalles[$i]->lote->producto->codigo,
                "unidad" => $detalles[$i]->lote->producto->getMedida(),
                "descripcion"=> $detalles[$i]->lote->producto->nombre.' - '.$detalles[$i]->lote->codigo,
                "cantidad" => $detalles[$i]->cantidad,
                "mtoValorUnitario" => $detalles[$i]->precio / 1.18,
                "mtoValorVenta" => ($detalles[$i]->precio / 1.18) * $detalles[$i]->cantidad,
                "mtoBaseIgv" => ($detalles[$i]->precio / 1.18) * $detalles[$i]->cantidad, 
                "porcentajeIgv" => 18,
                "igv" => ($detalles[$i]->precio - ($detalles[$i]->precio / 1.18 )) * $detalles[$i]->cantidad,
                "tipAfeIgv" => 10,
                "totalImpuestos" =>  ($detalles[$i]->precio - ($detalles[$i]->precio / 1.18 )) * $detalles[$i]->cantidad,
                "mtoPrecioUnitario" => $detalles[$i]->precio

            );
        }

        return $arrayProductos;
    }

    public function obtenerFecha($event)
    {
        $date = strtotime($event->documento->fecha_documento);
        $fecha_emision = date('Y-m-d', $date); 
        $hora_emision = date('H:i:s', $date); 
        $fecha = $fecha_emision.'T'.$hora_emision.'-05:00';

        return $fecha;
    }
}
