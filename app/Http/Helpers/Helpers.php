<?php

use App\Mantenimiento\Tabla\General;
use App\Mantenimiento\Ubigeo\Departamento;

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

if (!function_exists('grupos_sanguineos')) {
    function grupos_sanguineos()
    {
        return General::find(10)->detalles;
    }
}

// UBIGEO
if (!function_exists('departamentos')) {
    function departamentos()
    {
        return Departamento::all();
    }
}


