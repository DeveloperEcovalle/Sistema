<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\ventas\Documento\Detalle;

class GenerarGuiaRemisionElectronica
{

    public function handle($event)
    {   

        //ARREGLO GUIA
        $arreglo_guia = array(

            "tipoDoc" => "09",
            "serie" => $event->serie,
            "correlativo"=> $event->guia->correlativo,
            "fechaEmision" => self::obtenerFecha($event->guia),

            "company" => array(  
                "ruc" => $event->guia->documento->empresa->ruc,
                "razonSocial" => $event->guia->documento->empresa->razon_social,
                "address" => array(
                    "direccion" => $event->guia->documento->empresa->direccion_fiscal,
                )),


            "destinatario" => array(  
                "tipoDoc" =>  $event->guia->documento->cliente->tipoDocumento(),
                "numDoc" => $event->guia->documento->cliente->documento,
                "rznSocial" => $event->guia->documento->cliente->nombre,
                "address" => array(
                    "direccion" => $event->guia->documento->cliente->direccion,
                )
            ),

            "observacion" => $event->guia->observacion,
            
            "envio" => array(
                "modTraslado" =>  "01",
                "codTraslado" =>  "01",
                "desTraslado" =>  "VENTA",
                "fecTraslado" =>  self::obtenerFecha($event->guia),//FECHA DEL TRANSLADO
                "codPuerto" => "123",
                "indTransbordo"=> false,
                "pesoTotal" => $event->guia->peso_productos,
                "undPesoTotal"=> "KGM",
                "numBultos" => $event->guia->cantidad_productos,
                "llegada" => array(
                    "ubigueo" =>  $event->guia->ubigeo_llegada,
                    "direccion" => self::limitarDireccion($event->guia->tienda->direccion,50,"..."),
                ),


                "partida" => array(
                    "ubigueo" => $event->guia->ubigeo_partida,
                    "direccion" => self::limitarDireccion($event->guia->documento->empresa->direccion_fiscal,50,"..."),
                ),
                
                "transportista" => self::condicionReparto($event->guia),

            ),

            "details" =>  self::obtenerProductos($event->guia),


        );
        return json_encode($arreglo_guia);
    }

    public function condicionReparto($guia)
    {
        if ($guia->tienda->condicion_reparto == '6') {
            //CREAR TRANSPORTISTA (OFICINA)
            $Transportista = array(  
                "tipoDoc"=> "6",
                "numDoc"=> $guia->tienda->ruc_transporte_oficina,
                "rznSocial"=> $guia->tienda->nombre_transporte_oficina,
                "placa"=> $guia->placa_vehiculo,
                "choferTipoDoc"=> "1",
                "choferDoc"=> $guia->dni_conductor
            );
        }else{
            $Transportista = array(  
                "tipoDoc"=> "6",
                "numDoc"=> $guia->tienda->ruc_transporte_domicilio,
                "rznSocial"=> $guia->tienda->nombre_transporte_domicilio,
                "placa"=> $guia->placa_vehiculo,
                "choferTipoDoc"=> "1",
                "choferDoc"=> $guia->dni_conductor
            );
        }

        return $Transportista;
    }


    public function obtenerProductos($guia)
    {
        $detalles = Detalle::where('documento_id',$guia->documento_id)->get();
        
        $arrayProductos = Array();
        for($i = 0; $i < count($detalles); $i++){

            $arrayProductos[] = array(
                "codigo" => $detalles[$i]->lote->producto->codigo,
                "unidad" => $detalles[$i]->lote->producto->getMedida(),
                "descripcion"=> $detalles[$i]->lote->producto->nombre.' - '.$detalles[$i]->lote->codigo,
                "cantidad" => $detalles[$i]->cantidad,
                "codProdSunat" => '10',
            );
        }

        return $arrayProductos;
    }


    public function obtenerFecha($guia)
    {
        $date = strtotime($guia->documento->fecha_documento);
        $fecha_emision = date('Y-m-d', $date); 
        $hora_emision = date('H:i:s', $date); 
        $fecha = $fecha_emision.'T'.$hora_emision.'-05:00';

        return $fecha;
    }


    public function limitarDireccion($cadena, $limite, $sufijo){
        
        if(strlen($cadena) > $limite){
            return substr($cadena, 0, $limite) . $sufijo;
        }
        
        return $cadena;
    }
}
