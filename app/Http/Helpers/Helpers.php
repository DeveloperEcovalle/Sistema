<?php

use App\Mantenimiento\Tabla\General;
use App\Mantenimiento\Ubigeo\Departamento;
use App\Mantenimiento\Ubigeo\Distrito;
use App\Mantenimiento\Ubigeo\Provincia;
use App\Parametro;
use Carbon\Carbon;
//Orden de compra
use App\Compras\Detalle;
use App\Compras\Orden;

// TABLAS-DETALLES
if (!function_exists('tipos_moneda')) {
    function tipos_moneda()
    {
        return General::find(1)->detalles;
    }
}

if (!function_exists('bancos')) {
    function bancos()
    {
        return General::find(2)->detalles;
    }
}

if (!function_exists('tipos_documento')) {
    function tipos_documento()
    {
        return General::find(3)->detalles;
    }
}

if (!function_exists('tipos_sexo')) {
    function tipos_sexo()
    {
        return General::find(4)->detalles;
    }
}

if (!function_exists('estados_civiles')) {
    function estados_civiles()
    {
        return General::find(5)->detalles;
    }
}

if (!function_exists('zonas')) {
    function zonas()
    {
        return General::find(6)->detalles;
    }
}

if (!function_exists('areas')) {
    function areas()
    {
        return General::find(7)->detalles;
    }
}

if (!function_exists('cargos')) {
    function cargos()
    {
        return General::find(8)->detalles;
    }
}

if (!function_exists('profesiones')) {
    function profesiones()
    {
        return General::find(9)->detalles;
    }
}

if (!function_exists('presentaciones')) {
    function presentaciones()
    {
        return General::find(10)->detalles;
    }
}

if (!function_exists('personas')) {
    function personas()
    {
        return General::find(11)->detalles;
    }
}

if (!function_exists('grupos_sanguineos')) {
    function grupos_sanguineos()
    {
        return General::find(12)->detalles;
    }
}

if (!function_exists('modo_compra')) {
    function modo_compra()
    {
        return General::find(14)->detalles;
    }
}

if (!function_exists('tipo_compra')) {
    function tipo_compra()
    {
        return General::find(16)->detalles;
    }
}

// UBIGEO
if (!function_exists('departamentos')) {
    function departamentos($id = null)
    {
        if (is_null($id)) {
            return Departamento::all();
        } else {
            $departamento_id = str_pad($id, 2, "0", STR_PAD_LEFT);
            return Departamento::where('id', $id)->get();
        }
    }
}

if (!function_exists('provincias')) {
    function provincias($id = null)
    {
        if (is_null($id)) {
            return Provincia::all();
        } else {
            $provincia_id = str_pad($id, 4, "0", STR_PAD_LEFT);
            return Provincia::where('id', $provincia_id)->get();
        }
    }
}

if (!function_exists('getProvinciasByDepartamento')) {
    function getProvinciasByDepartamento($departamento_id)
    {
        if (is_null($departamento_id)) {
            return collect([]);
        } else {
            $departamento_id = str_pad($departamento_id, 2, "0", STR_PAD_LEFT);
            return Provincia::where('departamento_id', $departamento_id)->get();
        }
    }
}

if (!function_exists('distritos')) {
    function distritos($id = null)
    {
        if (is_null($id)) {
            return Distrito::all();
        } else {
            $distrito_id = str_pad($id, 6, "0", STR_PAD_LEFT);
            return Distrito::where('id', $distrito_id)->get();
        }
    }
}

if (!function_exists('getDistritosByProvincia')) {
    function getDistritosByProvincia($provincia_id)
    {
        if (is_null($provincia_id)) {
            return collect([]);
        } else {
            $provincia_id = str_pad($provincia_id, 4, "0", STR_PAD_LEFT);
            return Distrito::where('provincia_id', $provincia_id)->get();
        }
    }
}

//Consultas a la Api
if (!function_exists('consultaRuc')) {
    function consultaRuc()
    {
        return Parametro::findOrFail(1);
    }
}
if (!function_exists('consultaDni')) {
    function consultaDni()
    {
        return Parametro::findOrFail(2);
    }
}

if (!function_exists('getFechaFormato')) {
    function getFechaFormato($fecha, $formato)
    {
        if (is_null($fecha) || empty($fecha))
            return "-";

        $fecha_formato = Carbon::parse($fecha)->format($formato);
        return ($fecha_formato) ? $fecha_formato : $fecha;
    }
}

// Documento tributarios
if (!function_exists('tipos_documentos_tributarios')) {
    function tipos_documentos_tributarios()
    {
        return General::findOrFail(15)->detalles;
    }
}

// Monto a Pagar Orden de compra
if (!function_exists('calcularMonto')) {
    function calcularMonto($id)
    {
        
        $detalles = Detalle::where('orden_id',$id)->get();
        $orden = Orden::findOrFail($id);
        $subtotal = 0; 
        $igv = '';
        $tipo_moneda = '';
        foreach($detalles as $detalle){
            $subtotal = ($detalle->cantidad * $detalle->precio) + $subtotal;
        }

        if (!$orden->igv) {
            $igv = $subtotal * 0.18;
            $total = $subtotal + $igv;
            $decimal_subtotal = number_format($subtotal, 2, '.', '');
            $decimal_total = number_format($total, 2, '.', '');
            $decimal_igv = number_format($igv, 2, '.', ''); 
        }else{
            $calcularIgv = $orden->igv/100;
            $base = $subtotal / (1 + $calcularIgv);
            $nuevo_igv = $subtotal - $base;
            $decimal_subtotal = number_format($base, 2, '.', '');
            $decimal_total = number_format($subtotal, 2, '.', '');
            $decimal_igv = number_format($nuevo_igv, 2, '.', ''); 
        }
        return $decimal_total;
    }

}

//Obtner Simbbolo de la moneda
if (!function_exists('simbolo_monedas')) {
    function simbolo_monedas($moneda_descripcion)
    {
        $simbolo= '';
        foreach(tipos_moneda() as $moneda){
            if ($moneda->descripcion == $moneda_descripcion) {
                $simbolo= $moneda->simbolo;
            }
        }
        return $simbolo;
    }
}