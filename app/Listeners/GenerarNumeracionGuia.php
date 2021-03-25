<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Mantenimiento\Empresa\Numeracion;
use DB;
use App\Ventas\Guia;

class GenerarNumeracionGuia
{

    public function handle($event)
    {
        $numeracion = Numeracion::where('empresa_id',$event->guia->documento->empresa_id)->where('estado','ACTIVO')->where('tipo_comprobante',135)->first();
        if ($numeracion) {

            $resultado = ($numeracion)->exists();
            $enviar = [
                'existe' => ($resultado == true) ? true : false,
                'numeracion' => $numeracion,
                'correlativo' => self::obtenerCorrelativo($event->guia,$numeracion)
            ];
            $collection = collect($enviar);
            return  $collection;
        }
    }


    public function obtenerCorrelativo($guia, $numeracion)
    {
     
        $serie_comprobantes = DB::table('empresa_numeracion_facturaciones')
                            ->join('empresas','empresas.id','=','empresa_numeracion_facturaciones.empresa_id')
                            ->join('cotizacion_documento','cotizacion_documento.empresa_id','=','empresas.id')
                            ->join('guias_remision','guias_remision.documento_id','=','cotizacion_documento.id')
                            ->where('empresa_numeracion_facturaciones.tipo_comprobante',135)
                            ->where('empresa_numeracion_facturaciones.empresa_id',$guia->documento->empresa_id)
                            ->where('guias_remision.sunat',"1")
                            ->select('guias_remision.*','empresa_numeracion_facturaciones.*')
                            ->orderBy('guias_remision.correlativo','DESC')
                            ->get();

        if (count($serie_comprobantes) == 0) {
            //OBTENER EL DOCUMENTO INICIADO 
            $guia->correlativo = $numeracion->numero_iniciar;
            $guia->serie = $numeracion->serie;
            $guia->update();

            //ACTUALIZAR LA NUMERACION (SE REALIZO EL INICIO)
            self::actualizarNumeracion($numeracion);
            return $guia->correlativo;

        }else{
            //DOCUMENTO DE VENTA ES NUEVO EN SUNAT 
            if($guia->sunat != '1' ){
                $ultimo_comprobante = $serie_comprobantes->first();
                $guia->correlativo = $ultimo_comprobante->correlativo+1;
                $guia->serie = $numeracion->serie;
                $guia->update();

                //ACTUALIZAR LA NUMERACION (SE REALIZO EL INICIO)
                self::actualizarNumeracion($numeracion);
                return $guia->correlativo;
            }
        }
        
       
    }


    public function actualizarNumeracion($numeracion)
    {   
        $numeracion->emision_iniciada = '1';
        $numeracion->update();
    }

}
