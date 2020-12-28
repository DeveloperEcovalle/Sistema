@extends('layout') @section('content')
@section('compras-active', 'active')
@section('orden-compra-active', 'active')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-sm-12">
        <h2 style="text-transform:uppercase;"><b>Detalle del pago de la orden de compra # {{$orden->id}}</b></h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{route('home')}}">Panel de Control</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{route('compras.orden.index')}}">Ordenes de Compra</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{route('compras.pago.index', $orden->id)}}">Pagos</a>
                
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
                                        <a href="{{route('compras.pago.edit',  [ 'pago' => $pago->id, 'orden'=>  $orden->id ])}}" class="btn btn-warning btn-xs float-right"><i class='fa fa-edit'></i>Editar Pago</a>
                                        <h2 style="text-transform:uppercase;">Pago de la orden de compra # {{$orden->id}}</h2>
                                    </div>
                                    <p style="text-transform:uppercase;"><strong><i class="fa fa-caret-right"></i> Información general del pago:</strong></p>

                                </div>
                            </div>

                            <div class="row" style="text-transform:uppercase;">

                                <div class="col-md-6 b-r">

                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <label><strong>Fecha de Pago: </strong></label>
                                            <p class="text-navy">{{ Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/y') }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <label><strong>Monto: </strong></label>
                                            <p >{{$tipo_moneda.' '.$pago->monto}}</p>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <label><strong>Moneda: </strong></label> 
                                            @if($pago->moneda != "")
                                                <p>{{$pago->moneda}}</p>
                                            @else
                                                <p>-</p>
                                            @endif
                                        </div>
                                        <div class="col-md-6">
                                            <label><strong>Tipo de Cambio: </strong></label> 
                                            @if($pago->tipo_cambio != "")
                                                <p>{{$pago->tipo_cambio}}</p>
                                            @else
                                                <p>-</p>
                                            @endif
                                        </div>
                                    </div>

                        
                                </div>

                                <div class="col-md-6">

                                    <div class="form-group">
                                            <label><strong>Observación: </strong></label> 
                                            @if($pago->observacion != "")
                                                <p>{{$pago->observacion}}</p>
                                            @else
                                                <p>-</p>
                                            @endif
                                       
                                    </div>

                                </div>


       
                                
                            </div>

                            <hr>

                            <p style="text-transform:uppercase;"><strong> <i class="fa fa-caret-right"></i> Entidad Financiera :</strong></p>
                            <div class="row" style="text-transform:uppercase;">

                                    <div class="col-md-6 b-r">

                                        <div class="form-group">
                                            <label><strong>Descripcion: </strong></label>
                                            <p >{{$pago->banco->descripcion}}</p>
                                            
                                        </div>
                                        <div class="form-group">
                                            <label><strong>Moneda: </strong></label>
                                            <p >{{$pago->banco->tipo_moneda}}</p>
                                            
                                        </div>

                                    </div>

                                    <div class="col-md-6">

                                        <div class="form-group">
                                            <label><strong>N° Cuenta: </strong></label>
                                            <p >{{$pago->banco->num_cuenta}}</p>
                                            
                                        </div>
                                        <div class="form-group">
                                            <label><strong>N° CCI: </strong></label>
                                            <p >{{$pago->banco->cci}}</p>
                                            
                                        </div>

                                    </div>

                            </div>
                            <hr>

                            <p style="text-transform:uppercase;"><strong> <i class="fa fa-caret-right"></i> Proveedor :</strong></p>
                            <div class="row" style="text-transform:uppercase;">

                                    <div class="col-md-6 b-r">

                                        @if($pago->banco->proveedor->ruc)
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label><strong>Ruc: </strong></label>
                                                <p class="text-navy">{{$pago->banco->proveedor->ruc}}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <label><strong>Tipo: </strong></label>
                                                <p class="">{{$pago->banco->proveedor->tipo_persona}}</p>
                                            </div>

                                        </div>
                                        @else
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label><strong>Dni: </strong></label>
                                                <p class="text-navy">{{$pago->banco->proveedor->dni}}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <label><strong>Tipo: </strong></label>
                                                <p class="">{{$pago->banco->proveedor->tipo_persona}}</p>
                                            </div>
                                        </div>
                                        @endif

                                        <div class="form-group">
                                            <label><strong>Proveedor: </strong></label>
                                            <p >{{$pago->banco->proveedor->descripcion}}</p>
                                            
                                        </div>


                                    </div>

                                    <div class="col-md-6">

                                        <div class="form-group row">
                                            <div class="col-md-6">
                                                <label><strong>Telefono: </strong></label>
                                                @if($pago->banco->proveedor->telefono != "")
                                                    <p>{{$pago->banco->proveedor->telefono}}</p>
                                                @else
                                                    <p>-</p>
                                                @endif
                                            </div>

                                            <div class="col-md-6">
                                                <label><strong>Celular: </strong></label>
                                                @if($pago->banco->proveedor->celular != "")
                                                    <p>{{$pago->banco->proveedor->celular}}</p>
                                                @else
                                                    <p>-</p>
                                                @endif
                                            </div>
                                            
                                            
                                        </div>

                                        <div class="form-group">
                                            <label><strong>Direccion: </strong></label>
                                            <p>{{$pago->banco->proveedor->direccion}}</p>
                                            
                                        </div>


                                    </div>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="wrapper wrapper-content project-manager" style="text-transform:uppercase;">
                    <h4>Pago</h4>
                    <p><b>Información adicional:</b><p>
                    <div class="text-center">
                        <i class="fa fa-money big-icon"></i>


                    <div>
                    <div class="text-center m-t-md">
                        @if($pago->ruta_archivo)
                            <a title="{{$pago->nombre_archivo}}" download="{{$pago->nombre_archivo}}" href="{{Storage::url($pago->ruta_archivo)}}" class="btn btn-xs btn-block btn-primary"><i class="fa fa-download"></i> Descargar Archivo</a>
                        @endif

                    
                    </div>
                    <hr>
                    <div class="row">
                                <div class="col-lg-12">
                                    <dl class="row mb-0">
                                        <div class="col-sm-4 text-sm-left"><dt>CREADO:</dt> </div>
                                        <div class="col-sm-8 text-sm-right"><dd class="mb-1">  {{ Carbon\Carbon::parse($pago->created_at)->format('d/m/y - G:i:s') }}</dd> </div>
                                    </dl>
                                    <dl class="row mb-0">
                                        <div class="col-sm-4 text-sm-left"><dt>ACTUALIZADO:</dt> </div>
                                        <div class="col-sm-8 text-sm-right"> <dd class="mb-1">  {{ Carbon\Carbon::parse($pago->updated_at)->format('d/m/y - G:i:s') }}</dd></div>
                                    </dl>

                                </div>
                    </div>
                </div>
            </div>
        </div>
@stop
