@extends('layout') @section('content')
@section('compras-active', 'active')
@section('proveedor-active', 'active')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-sm-12">
        <h2 style="text-transform:uppercase;"><b>Detalle del proveedor: {{$proveedor->descripcion}}</b></h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{route('home')}}">Panel de Control</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{route('compras.proveedor.index')}}">Proveedores</a>
            </li>
            <li class="breadcrumb-item active">
                <strong>Detalle</strong>
            </li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-lg-9">
        <div class="wrapper wrapper-content animated fadeInUp">
            <div class="ibox">
                <div class="ibox-content">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="">
                                <a href="{{route('compras.proveedor.edit',$proveedor->id)}}"
                                    class="btn btn-warning btn-xs float-right"><i class='fa fa-edit'></i>Editar
                                    Proveedor</a>
                                <h2 style="text-transform:uppercase;">{{$proveedor->descripcion}}</h2>
                            </div>
                            <p style="text-transform:uppercase;"><strong><i class="fa fa-caret-right"></i> Información
                                    general del proveedor:</strong></p>

                        </div>
                    </div>

                    <div class="row" style="text-transform:uppercase;">

                        <div class="col-md-6 b-r">

                            @if($proveedor->ruc)
                            <div class="row">
                                <div class="col-md-6">
                                    <label><strong>Ruc: </strong></label>
                                    <p class="text-navy">{{$proveedor->ruc}}</p>
                                </div>
                                <div class="col-md-6">
                                    <label><strong>Tipo: </strong></label>
                                    <p class="">{{$proveedor->tipo_persona}}</p>
                                </div>

                            </div>
                            @else
                            <div class="row">
                                <div class="col-md-6">
                                    <label><strong>Dni: </strong></label>
                                    <p class="text-navy">{{$proveedor->dni}}</p>
                                </div>
                                <div class="col-md-6">
                                    <label><strong>Tipo: </strong></label>
                                    <p class="">{{$proveedor->tipo_persona}}</p>
                                </div>
                            </div>
                            @endif

                            <div class="form-group">
                                <label><strong>Descripción: </strong></label>
                                <p>{{$proveedor->descripcion}}</p>
                            </div>

                            <div class="form-group">
                                <label><strong>Dirección: </strong></label>
                                <p>{{$proveedor->direccion}}</p>
                            </div>

                            <div class="form-group">
                                <label><strong>Zona: </strong></label>
                                <p>{{$proveedor->zona}}</p>
                            </div>

                            <div class="form-group">
                                <label><strong>Dirección: </strong></label>
                                <p>{{$proveedor->direccion}}</p>
                            </div>



                        </div>

                        <div class="col-md-6">

                            <div class="form-group">
                                <label><strong>Correo: </strong></label>
                                <p>{{$proveedor->correo}}</p>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <label><strong>Telefono: </strong></label>
                                    @if($proveedor->telefono != "")
                                    <p>{{$proveedor->telefono}}</p>
                                    @else
                                    <p>-</p>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <label><strong>Celular: </strong></label>
                                    @if($proveedor->celular != "")
                                    <p>{{$proveedor->celular}}</p>
                                    @else
                                    <p>-</p>
                                    @endif
                                </div>
                            </div>

                        </div>




                    </div>

                    <hr>

                    <p style="text-transform:uppercase;"><strong> <i class="fa fa-caret-right"></i> Información del
                            calidad:</strong></p>
                    <div class="row" style="text-transform:uppercase;">

                        <div class="col-md-6 b-r">

                            <div class="form-group">
                                <label><strong>Nombre: </strong></label>
                                @if($proveedor->calidad != "")
                                <p>{{$proveedor->calidad}}</p>
                                @else
                                <p>-</p>
                                @endif

                            </div>
                            <div class="form-group">
                                <label><strong>Correo: </strong></label>
                                @if($proveedor->correo != "")
                                <p>{{$proveedor->correo}}</p>
                                @else
                                <p>-</p>
                                @endif

                            </div>

                        </div>

                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-6">
                                    <label><strong>Telefono: </strong></label>
                                    @if($proveedor->telefono_calidad != "")
                                    <p>{{$proveedor->telefono_calidad}}</p>
                                    @else
                                    <p>-</p>
                                    @endif
                                </div>

                                <div class="col-md-6">
                                    <label><strong>Celular: </strong></label>
                                    @if($proveedor->celular_calidad != "")
                                    <p>{{$proveedor->celular_calidad}}</p>
                                    @else
                                    <p>-</p>
                                    @endif
                                </div>
                            </div>

                        </div>
                    </div>


                    <hr>
                    <p style="text-transform:uppercase;"><strong> <i class="fa fa-caret-right"></i> Información del
                            contacto:</strong></p>
                    <div class="row" style="text-transform:uppercase;">

                        <div class="col-md-6 b-r">

                            <div class="form-group">
                                <label><strong>Nombre: </strong></label>
                                @if($proveedor->contacto != "")
                                <p>{{$proveedor->contacto}}</p>
                                @else
                                <p>-</p>
                                @endif

                            </div>

                        </div>

                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-6">
                                    <label><strong>Telefono: </strong></label>
                                    @if($proveedor->telefono_contacto != "")
                                    <p>{{$proveedor->telefono_contacto}}</p>
                                    @else
                                    <p>-</p>
                                    @endif
                                </div>

                                <div class="col-md-6">
                                    <label><strong>Celular: </strong></label>
                                    @if($proveedor->celular_contacto != "")
                                    <p>{{$proveedor->celular_contacto}}</p>
                                    @else
                                    <p>-</p>
                                    @endif
                                </div>
                            </div>

                        </div>

                    </div>

                    <hr>
                    <p style="text-transform:uppercase;"><strong><i class="fa fa-caret-right"></i> Información del
                            Transporte:</strong></p>
                    <div class="row" style="text-transform:uppercase;">

                        <div class="col-md-6 b-r">

                            <div class="form-group">
                                <label><strong>Nombre: </strong></label>
                                @if($proveedor->transporte != "")
                                <p>{{$proveedor->transporte}}</p>
                                @else
                                <p>-</p>
                                @endif
                            </div>

                        </div>

                        <div class="col-md-6">

                            <div class="form-group">
                                <label><strong>Dirección: </strong></label>

                                @if($proveedor->direccion_transporte != "")
                                <p>{{$proveedor->direccion_transporte}}</p>
                                @else
                                <p>-</p>
                                @endif
                            </div>

                        </div>

                    </div>


                    <hr>
                    <p style="text-transform:uppercase;"><strong><i class="fa fa-caret-right"></i> Información del
                            Almacen:</strong></p>
                    <div class="row" style="text-transform:uppercase;">

                        <div class="col-md-6">

                            <div class="form-group">
                                <label><strong>Dirección: </strong></label>

                                @if($proveedor->direccion_almacen != "")
                                <p>{{$proveedor->direccion_almacen}}</p>
                                @else
                                <p>-</p>
                                @endif
                            </div>

                        </div>

                    </div>






                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="wrapper wrapper-content project-manager" style="text-transform:uppercase;">
            <h4>Registro</h4>
            <p><b>Información del registro:<b></p>
            <p class="text-center">
                <i class="fa fa-building big-icon"></i>

            </p>
            <hr>
            <div class="row">
                <div class="col-lg-12">
                    <dl class="row mb-0">
                        <div class="col-sm-4 text-sm-left">
                            <dt>CREADO:</dt>
                        </div>
                        <div class="col-sm-8 text-sm-right">
                            <dd class="mb-1">
                                {{ Carbon\Carbon::parse($proveedor->created_at)->format('d/m/y - G:i:s') }}</dd>
                        </div>
                    </dl>
                    <dl class="row mb-0">
                        <div class="col-sm-4 text-sm-left">
                            <dt>ACTUALIZADO:</dt>
                        </div>
                        <div class="col-sm-8 text-sm-right">
                            <dd class="mb-1">
                                {{ Carbon\Carbon::parse($proveedor->updated_at)->format('d/m/y - G:i:s') }}</dd>
                        </div>
                    </dl>

                </div>
            </div>
        </div>
    </div>
</div>
@stop