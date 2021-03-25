<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use App\Mantenimiento\Empresa\Numeracion;
use App\ventas\Documento\Documento;
use DB;

class ConsultarTipoNumeracion
{
    public function handle($event)
    {
        
        $numeracion = Numeracion::where('empresa_id',$event->documento->empresa_id)->where('estado','ACTIVO')->where('tipo_comprobante',$event->documento->tipo_venta)->first();
        if ($numeracion) {

            $resultado = ($numeracion)->exists();
            $enviar = [
                'existe' => ($resultado == true) ? true : false,
                'numeracion' => $numeracion,
                'correlativo' => self::obtenerCorrelativo($event->documento,$numeracion)
            ];
            $collection = collect($enviar);
            return  $collection;
        }
    }

    public function obtenerCorrelativo($documento, $numeracion)
    {
        $serie_comprobantes = DB::table('empresa_numeracion_facturaciones')
                            ->join('empresas','empresas.id','=','empresa_numeracion_facturaciones.empresa_id')
                            ->join('cotizacion_documento','cotizacion_documento.empresa_id','=','empresas.id')
                            ->where('empresa_numeracion_facturaciones.tipo_comprobante',$documento->tipo_venta)
                            ->where('empresa_numeracion_facturaciones.empresa_id',$documento->empresa_id)
                            ->where('cotizacion_documento.tipo_venta',$documento->tipo_venta)
                            ->where('cotizacion_documento.sunat',"1")
                            ->select('cotizacion_documento.*','empresa_numeracion_facturaciones.*')
                            ->orderBy('cotizacion_documento.correlativo','DESC')
                            ->get();


        if (count($serie_comprobantes) == 0) {
            //OBTENER EL DOCUMENTO INICIADO 
            $documento->correlativo = $numeracion->numero_iniciar;
            $documento->serie = $numeracion->serie;
            $documento->update();

            //ACTUALIZAR LA NUMERACION (SE REALIZO EL INICIO)
            self::actualizarNumeracion($numeracion);
            return $documento->correlativo;

        }else{
            //DOCUMENTO DE VENTA ES NUEVO EN SUNAT 
            if($documento->sunat != '1' ){
                $ultimo_comprobante = $serie_comprobantes->first();
                $documento->correlativo = $ultimo_comprobante->correlativo+1;
                $documento->serie = $numeracion->serie;
                $documento->update();

                //ACTUALIZAR LA NUMERACION (SE REALIZO EL INICIO)
                self::actualizarNumeracion($numeracion);
                return $documento->correlativo;
            }
        }
        
       
    }


    public function actualizarNumeracion($numeracion)
    {   
        $numeracion->emision_iniciada = '1';
        $numeracion->update();
    }



}
