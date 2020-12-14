@extends('layout') @section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-sm-12">
        <h2 style="text-transform:uppercase;"><b>Detalle del Artículo: {{$articulo->descripcion}}</b></h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{route('compras.articulo.index')}}">Artículos</a>
            </li>
            <li class="breadcrumb-item active">
                <strong>Detalle</strong>
            </li>
        </ol>
    </div>
</div>
<div class="row">
            <div class="col-lg-12">
                <div class="wrapper wrapper-content animated fadeInUp">
                    <div class="ibox">
                        <div class="ibox-content">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="">
                                        <a href="{{route('compras.articulo.edit',$articulo->id)}}" class="btn btn-warning btn-xs float-right"><i class='fa fa-edit'></i>Editar Artículo</a>
                                        <h2 style="text-transform:uppercase;">{{$articulo->descripcion}}</h2>
                                    </div>
                                    <p><strong><i class="fa fa-caret-right"></i> Información general del Artículo:</strong></p>

                                </div>
                            </div>

                            <div class="row" style="text-transform:uppercase;">

                                <div class="col-md-6 b-r">

                                    <div class="form-group">
                                        <label><strong>Descripción: </strong></label>
                                        <p class="text-navy">{{$articulo->descripcion}}</p>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <label><strong>Categoria: </strong></label>
                                            <p >{{$articulo->categoria->descripcion}}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <label><strong>Presentación </strong></label> 
                                            <p>{{$articulo->presentacion}}</p>
                                        </div>
                                        
                                    </div>
                        
                                </div>

                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label><strong>Stock: </strong></label> 
                                            <p>{{$articulo->stock}}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <label><strong>Stock Min.: </strong></label> 
                                            <p>{{$articulo->stock_min}}</p>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <label><strong>Precio de Compra: </strong></label>
                                            <p >{{$articulo->precio_compra}}</p>
                                        </div>
                                    </div>


                                </div>


       
                                
                            </div>


                        </div>
                    </div>
                </div>
            </div>

        </div>
@stop
