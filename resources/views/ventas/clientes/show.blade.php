@extends('layout') @section('content')

@section('ventas-active', 'active')
@section('clientes-active', 'active')

<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10 col-md-10">
       <h2  style="text-transform:uppercase"><b>Datos del Cliente: {{ $cliente->nombre }}</b></h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('home') }}">Panel de Control</a>
            </li>
            <li class="breadcrumb-item active">
                <a href="{{ route('ventas.cliente.index') }}">Clientes</a>
            </li>
            <li class="breadcrumb-item active">
                <strong>Datos</strong>
            </li>
        </ol>
    </div>
</div>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-content">
                    <div>
                        <a href="{{ route('ventas.cliente.edit', $cliente->id) }}" class="btn btn-warning btn-xs float-right">
                            <i class="fa fa-edit"></i>EDITAR CLIENTE
                        </a>
                       <h2  style="text-transform:uppercase">{{ $cliente->nombre }}</h2>
                    </div>
                    <h4><b><i class="fa fa-caret-right"></i> DATOS GENERALES</b></h4>
                    <div class="row">
                        <div class="form-group col-lg-2 col-xs-12">
                            <label><strong>TIPO DE DOCUMENTO</strong></label>
                            <p>{{ $cliente->tipo_documento }}</p>
                        </div>
                        <div class="form-group col-lg-2 col-xs-12">
                            <label><strong>DOCUMENTO</strong></label>
                            <p>{{ $cliente->documento }}</p>
                        </div>
                        <div class="form-group col-lg-2 col-xs-12">
                            <label><strong>ESTADO</strong></label>
                            <p>{{ ($cliente->activo == 1) ? 'ACTIVO' : 'INACTIVO' }}</p>
                        </div>
                        <div class="form-group col-lg-6 col-xs-12">
                            <label><strong>NOMBRE</strong></label>
                            <p>{{ $cliente->nombre }}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-lg-2 col-xs-12">
                            <label><strong>DEPARTAMENTO</strong></label>
                            <p>{{ $cliente->getDepartamento() }}</p>
                        </div>
                        <div class="form-group col-lg-2 col-xs-12">
                            <label><strong>PROVINCIA</strong></label>
                            <p>{{ $cliente->getProvincia() }}</p>
                        </div>
                        <div class="form-group col-lg-2 col-xs-12">
                            <label><strong>DISTRITO</strong></label>
                            <p>{{ $cliente->getDistrito() }}</p>
                        </div>
                        <div class="form-group col-lg-6 col-xs-12">
                            <label><strong>DIRECCION</strong></label>
                            <p>{{ $cliente->direccion }}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-lg-2 col-xs-12">
                            <label><strong>TELÉFONO MÓVIL</strong></label>
                            <p>{{ $cliente->telefono_movil }}</p>
                        </div>
                        <div class="form-group col-lg-2 col-xs-12">
                            <label><strong>TELÉFONO FIJO</strong></label>
                            <p>{{ ($cliente->telefono_fijo) ? $cliente->telefono_fijo : '-' }}</p>
                        </div>
                        <div class="form-group col-lg-4 col-xs-12">
                            <label><strong>CORREO ELECTRÓNICO</strong></label>
                            <p>{{ ($cliente->correo_electronico) ? $cliente->correo_electronico : '-' }}</p>
                        </div>
                    </div>
                    <hr>
                    @if ($cliente->tipo_documento == 'RUC')
                        <h4><b><i class="fa fa-caret-right"></i> DATOS DEL CONTACTO</b></h4>
                        <div class="row">
                            <div class="form-group col-lg-3 col-xs-12">
                                <label><strong>NOMBRE COMPLETO</strong></label>
                                <p>{{ ($cliente->nombre_contacto) ? $cliente->nombre_contacto : '-' }}</p>
                            </div>
                            <div class="form-group col-lg-3 col-xs-12">
                                <label><strong>TELÉFONO MÓVIL</strong></label>
                                <p>{{ $cliente->telefono_contacto }}</p>
                            </div>
                            <div class="form-group col-lg-3 col-xs-12">
                                <label><strong>CORREO ELECTRÓNICO</strong></label>
                                <p>{{ ($cliente->correo_electronico_contacto) ? $cliente->correo_electronico_contacto : '-' }}</p>
                            </div>
                        </div>
                        <hr>
                    @endif
                    <h4><b><i class="fa fa-caret-right"></i> DATOS ADICIONALES</b></h4>
                    <div class="row">
                        <div class="form-group col-lg-3 col-xs-12">
                            <label><strong>LÍMITE DE CRÉDITO</strong></label>
                            <p>{{ ($cliente->moneda_limite_credito) ? $cliente->moneda_limite_credito : '-' }} {{ ($cliente->limite_credito) ? $cliente->limite_credito : '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@stop

@push('styles')
    <link href="{{ asset('Inspinia/css/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css') }}" rel="stylesheet">
@endpush

@push('scripts')
    <!-- iCheck -->
    <script src="{{ asset('Inspinia/js/plugins/iCheck/icheck.min.js') }}"></script>
@endpush
