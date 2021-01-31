@extends('layout') @section('content')

@section('ventas-active', 'active')
@section('clientes-active', 'active')

<div class="row wrapper border-bottom white-bg page-heading">

    <div class="col-lg-12">
       <h2  style="text-transform:uppercase"><b>MODIFICAR TIENDA #{{$tienda->id}} DEL CLIENTE : {{$tienda->cliente->nombre}} </b></h2>

        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{route('home')}}">Panel de Control</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{route('ventas.cliente.index')}}">Clientes</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{route('clientes.tienda.index',$tienda->cliente->id)}}">Tiendas</a>
            </li>
  
            <li class="breadcrumb-item active">
                <strong>Modificar</strong>
            </li>

        </ol>
    </div>



</div>


<div class="wrapper wrapper-content animated fadeInRight">

    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">

                <div class="ibox-content">

                    <form action="{{route('clientes.tienda.update', $tienda->id)}}" method="POST" 
                        id="enviar_tienda">
                        @csrf @method('PUT')


                        <div class="row">
                            <div class="col-lg-12">
                                <div class="tabs-container">
                                    <ul class="nav nav-tabs">
                                        <li title="Datos de la Tienda">
                                            <a class="nav-link active" data-toggle="tab" href="#tab-1"><i class="fa fa-shopping-cart"></i> Tienda</a>
                                        </li>
                                        <li title="Datos de los Contactos">
                                            <a class="nav-link" data-toggle="tab"href="#tab-2" id="contactos"> <i class="fa fa-user"></i> Contactos</a>
                                        </li>
                                    </ul>
                                    <div class="tab-content">
                                            <div id="tab-1" class="tab-pane active">
                                                <div class="panel-body">

                                                <input type="hidden" name="cliente_id" value="{{$tienda->cliente_id}}">


                                                    <div class="row">
                                                        <div class="col-md-6 b-r">
                                                            <h4><b>Tienda</b></h4>
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <p>Modificar datos de la tienda:</p>
                                                                </div>
                                                            </div>

                                                            <div class="form-group row">
                                                                <div class="col-md-6">
                                                                    <label class="required">Tipo:</label>

                                                                    <select class="select2_form form-control {{ $errors->has('tipo_tienda') ? ' is-invalid' : '' }}" style="text-transform: uppercase; width:100%" value="{{old('tipo_tienda', $tienda->tipo_tienda)}}" name="tipo_tienda" id="tipo_tienda" required>
                                                                        <option></option>

                                                                        @foreach(tipos_tienda() as $tipo)
                                                                            <option value="{{ $tipo->descripcion }}" {{ (old('tipo_tienda', $tienda->tipo_tienda) == $tipo->descripcion ? "selected" : "") }}>{{ $tipo->descripcion }}</option>
                                                                        @endforeach

                                                                        @if ($errors->has('tipo_tienda'))
                                                                        <span class="invalid-feedback" role="alert">
                                                                            <strong>{{ $errors->first('tipo_tienda') }}</strong>
                                                                        </span>
                                                                        @endif
                                                                    </select>
                                                                    <div class="invalid-feedback"><b><span id="error-tipo"></span></b></div>

                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label class="required">Negocio:</label>
                                                                        <select class="select2_form form-control {{ $errors->has('tipo_negocio') ? ' is-invalid' : '' }}" style="text-transform: uppercase; width:100%" value="{{old('tipo_negocio', $tienda->tipo_negocio)}}" name="tipo_negocio" id="tipo_negocio" required>
                                                                            <option></option>

                                                                            @foreach(tipos_negocio() as $tipo)
                                                                                <option value="{{ $tipo->descripcion }}" {{ (old('tipo_negocio', $tienda->tipo_negocio) == $tipo->descripcion ? "selected" : "") }}>{{ $tipo->descripcion }}</option>
                                                                            @endforeach

                                                                            @if ($errors->has('tipo_negocio'))
                                                                            <span class="invalid-feedback" role="alert">
                                                                                <strong>{{ $errors->first('tipo_negocio') }}</strong>
                                                                            </span>
                                                                            @endif
                                                                        </select>
                                                                        <div class="invalid-feedback"><b><span id="error-negocio"></span></b></div>

                                                                </div>

                                                            </div>


                                                            <div class="form-group">
                                                                <label class="required">Nombre: </label>

                                                                <input type="text"
                                                                    class="form-control {{ $errors->has('nombre') ? ' is-invalid' : '' }}"
                                                                    name="nombre" value="{{ old('nombre',$tienda->nombre)}}" id="nombre"
                                                                    onkeyup="return mayus(this)" required>

                                                                @if ($errors->has('nombre'))
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $errors->first('nombre') }}</strong>
                                                                </span>
                                                                @endif
                                                                <div class="invalid-feedback"><b><span id="error-nombre"></span></b></div>
                                                            </div>


                                                            <div class="form-group row">
                                                                <div class="col-md-12">
                                                                    <label class="required">Dirección:</label>
                                                                    <textarea type="text" id="direccion" name="direccion" required
                                                                        class="form-control {{ $errors->has('direccion') ? ' is-invalid' : '' }}" value="{{old('direccion',$tienda->direccion)}}"
                                                                        onkeyup="return mayus(this)" >{{old('direccion',$tienda->direccion)}}</textarea>
                                                                    @if ($errors->has('direccion'))
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $errors->first('direccion') }}</strong>
                                                                    </span>
                                                                    @endif
                                                                    <div class="invalid-feedback"><b><span id="error-direccion"></span></b></div>
                                                                </div>

                                                            </div>



                                                            <div class="form-group">
                                                                <label class="">Correo: </label>

                                                                <input type="email"
                                                                    class="form-control {{ $errors->has('correo') ? ' is-invalid' : '' }}"
                                                                    name="correo" value="{{ old('correo',$tienda->correo)}}" id="correo"
                                                                    onkeyup="return mayus(this)">

                                                                @if ($errors->has('correo'))
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $errors->first('correo') }}</strong>
                                                                </span>
                                                                @endif
                                                            </div>



                                                        </div>
                                                        <div class="col-md-6">

                                                            <div class="form-group row">
                                                                <div class="col-md-6">
                                                                    <label>Teléfono:</label>
                                                                    <input type="text" placeholder=""
                                                                        class="form-control {{ $errors->has('telefono') ? ' is-invalid' : '' }}"
                                                                        name="telefono" id="telefono"  onkeyup="return mayus(this)"
                                                                        value="{{old('telefono',$tienda->telefono)}}">
                                                                    @if ($errors->has('telefono'))
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $errors->first('telefono') }}</strong>
                                                                    </span>
                                                                    @endif
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label>Celular:</label>
                                                                    <input type="text" placeholder=""
                                                                        class="form-control {{ $errors->has('celular') ? ' is-invalid' : '' }}"
                                                                        name="celular" id="celular"  onkeyup="return mayus(this)"
                                                                        value="{{old('celular',$tienda->celular)}}">
                                                                    @if ($errors->has('celular'))
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $errors->first('celular') }}</strong>
                                                                    </span>
                                                                    @endif
                                                                </div>

                                                            </div>
                                                            
                                                            <hr>

                                                            <div class="form-group">
                                                                <h4><b>Datos de las Redes Sociales</b></h4>
                                                                <p>Registrar datos de las redes sociales de la tienda:</p>
                                                            </div>

                                                            <div class="form-group">
                                                                
                                                                    <label class="">Facebook:</label>
                                                                        <div class="input-group">
                                                                            <span class="input-group-addon">
                                                                                <i class="fa fa-facebook"></i>
                                                                            </span>
                                                                            <input type="text" id="facebook" name="facebook"
                                                                                class="form-control {{ $errors->has('facebook') ? ' is-invalid' : '' }}" onkeyup="return mayus(this)"  value="{{old('facebook',$tienda->facebook)}}">

                                                                                @if ($errors->has('facebook'))
                                                                                <span class="invalid-feedback" role="alert">
                                                                                    <strong>{{ $errors->first('facebook') }}</strong>
                                                                                </span>
                                                                                @endif
                                                                        </div>

                                                                
                                                            </div>

                                                            <div class="form-group">
                                                            
                                                                    <label class="">Instagram:</label>
                                                                    <div class="input-group">
                                                                        <span class="input-group-addon">
                                                                            <i class="fa fa-instagram"></i>
                                                                        </span>
                                                                        <input type="text" id="instagram" name="instagram"
                                                                            class="form-control {{ $errors->has('instagram') ? ' is-invalid' : '' }}" onkeyup="return mayus(this)"  value="{{old('instagram',$tienda->instagram)}}">

                                                                            @if ($errors->has('instagram'))
                                                                            <span class="invalid-feedback" role="alert">
                                                                                <strong>{{ $errors->first('instagram') }}</strong>
                                                                            </span>
                                                                            @endif
                                                                    </div>
                                                                
                                                            </div>


                                                            <div class="form-group">

                                                                    <label class="">Web:</label>
                                                                    <div class="input-group">
                                                                        <span class="input-group-addon">
                                                                            <i class="fa fa-globe"></i>
                                                                        </span>
                                                                        <input type="text" id="web" name="web"
                                                                            class="form-control {{ $errors->has('web') ? ' is-invalid' : '' }}" onkeyup="return mayus(this)"  value="{{old('web',$tienda->web)}}">

                                                                            @if ($errors->has('web'))
                                                                            <span class="invalid-feedback" role="alert">
                                                                                <strong>{{ $errors->first('web') }}</strong>
                                                                            </span>
                                                                            @endif
                                                                    </div>
                                                                
                                                            </div>




                                                                
                                                            


                                                        </div>
                                                    </div>
                                                </div>
                                            
                                            </div>
                                            <div id="tab-2" class="tab-pane">
                                                <div class="panel-body">
                                                



                                                    <div class="form-group row">
                                                        <div class="col-md-6 b-r">
                                                            <div class="form-group">
                                                                
                                                                <h4><b>Contacto: "Administrador"</b></h4>
                                                                <p>Registrar datos del contacto Administrador:</p>
                                                                
                                                            </div>

                                                            <div class="form-group">
                                                                <label class="">Nombre</label>

                                                                <input type="text" id="nombre_administrador" name="nombre_administrador"
                                                                class="form-control {{ $errors->has('nombre_administrador') ? ' is-invalid' : '' }}"
                                                                value="{{old('nombre_administrador',$tienda->contacto_admin_nombre)}}" onkeyup="return mayus(this)">

                                                                @if ($errors->has('nombre_administrador'))
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $errors->first('nombre_administrador') }}</strong>
                                                                </span>
                                                                @endif

                                                            </div>

                                                            
                                                            <div class="form-group row">

                                                                <div class="col-lg-6 col-xs-12" id="fecha_nacimiento_administrador_campo">
                                                                    <label class="">Fecha de Nacimiento</label>
                                                                    <div class="input-group date">
                                                                        <span class="input-group-addon">
                                                                            <i class="fa fa-calendar"></i>
                                                                        </span>
                                                                        <input type="text" id="fecha_nacimiento_administrador" name="fecha_nacimiento_administrador"
                                                                            class="form-control {{ $errors->has('fecha_nacimiento_administrador') ? ' is-invalid' : '' }}"
                                                                            value="{{old('fecha_nacimiento_administrador',getFechaFormato($tienda->contacto_admin_fecha_nacimiento, 'd/m/Y'))}}"
                                                                            autocomplete="off" readonly>

                                                                            @if ($errors->has('fecha_nacimiento_administrador'))
                                                                            <span class="invalid-feedback" role="alert">
                                                                                <strong>{{ $errors->first('fecha_nacimiento_administrador') }}</strong>
                                                                            </span>
                                                                            @endif
                                                                    </div>
                                                                                    

                                                                </div>

                                                                <div class="col-lg-6 col-xs-12">
                                                                    <label class="">Cargo</label>
                                                                    <select class="select2_form form-control {{ $errors->has('cargo_administrador') ? ' is-invalid' : '' }}" style="text-transform: uppercase; width:100%" value="{{old('cargo_administrador',$tienda->contacto_admin_correo)}}" name="cargo_administrador" id="cargo_administrador" >
                                                                        <option></option>

                                                                        @foreach(cargos() as $cargo)
                                                                            <option value="{{ $cargo->descripcion }}" {{ (old('cargo_administrador', $tienda->contacto_admin_cargo) == $cargo->descripcion ? "selected" : "") }}>{{ $cargo->descripcion }}</option>
                                                                        @endforeach

                                                                        @if ($errors->has('cargo_administrador'))
                                                                        <span class="invalid-feedback" role="alert">
                                                                            <strong>{{ $errors->first('cargo_administrador') }}</strong>
                                                                        </span>
                                                                        @endif
                                                                    </select>


    
                                                                    
                                                                </div>

                                                            </div>



                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="">Correo Electrónico</label>

                                                                <input type="email" id="correo_administrador" name="correo_administrador"
                                                                class="form-control {{ $errors->has('correo_administrador') ? ' is-invalid' : '' }}"
                                                                value="{{old('correo_administrador',$tienda->contacto_admin_correo)}}" onkeyup="return mayus(this)">

                                                                @if ($errors->has('correo_administrador'))
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $errors->first('correo_administrador') }}</strong>
                                                                </span>
                                                                @endif

                                                            </div>

                                                            <div class="form-group row">
                                                                <div class="col-md-6">
                                                                    <label>Teléfono:</label>
                                                                    <input type="text" placeholder=""
                                                                        class="form-control {{ $errors->has('telefono_administrador') ? ' is-invalid' : '' }}"
                                                                        name="telefono_administrador" id="telefono_administrador"  onkeyup="return mayus(this)"
                                                                        value="{{old('telefono_administrador',$tienda->contacto_admin_telefono)}}">
                                                                    @if ($errors->has('telefono_administrador'))
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $errors->first('telefono_administrador') }}</strong>
                                                                    </span>
                                                                    @endif
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label>Celular:</label>
                                                                    <input type="text" placeholder=""
                                                                        class="form-control {{ $errors->has('celular_administrador') ? ' is-invalid' : '' }}"
                                                                        name="celular_administrador" id="celular_administrador"  onkeyup="return mayus(this)"
                                                                        value="{{old('celular_administrador',$tienda->contacto_admin_celular)}}">
                                                                    @if ($errors->has('celular_administrador'))
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $errors->first('celular_administrador') }}</strong>
                                                                    </span>
                                                                    @endif
                                                                </div>

                                                            </div>

                                                        </div>




                                                    </div>

                                                    <hr>

                                                    <div class="form-group row">
                                                        <div class="col-md-6 b-r">
                                                            <div class="form-group">
                                                                <h4><b>Contacto: "Crédito & Cobranza"</b></h4>
                                                                <p>Registrar datos del contacto Crédito & Cobranza:</p>
                                                            </div>

                                                            <div class="form-group">
                                                                <label class="">Nombre</label>

                                                                <input type="text" id="nombre_credito" name="nombre_credito"
                                                                class="form-control {{ $errors->has('nombre_credito') ? ' is-invalid' : '' }}"
                                                                value="{{old('nombre_credito',$tienda->contacto_credito_nombre)}}" onkeyup="return mayus(this)">

                                                                @if ($errors->has('nombre_credito'))
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $errors->first('nombre_credito') }}</strong>
                                                                </span>
                                                                @endif

                                                            </div>

                                                            
                                                            <div class="form-group row">

                                                                <div class="col-lg-6 col-xs-12" id="fecha_nacimiento_credito_campo">
                                                                    <label class="">Fecha de Nacimiento</label>
                                                                    <div class="input-group date">
                                                                        <span class="input-group-addon">
                                                                            <i class="fa fa-calendar"></i>
                                                                        </span>
                                                                        <input type="text" id="fecha_nacimiento_credito" name="fecha_nacimiento_credito"
                                                                            class="form-control {{ $errors->has('fecha_nacimiento_credito') ? ' is-invalid' : '' }}"
                                                                            value="{{old('fecha_nacimiento_credito',getFechaFormato($tienda->contacto_credito_fecha_nacimiento, 'd/m/Y'))}}"
                                                                            autocomplete="off" readonly>

                                                                            @if ($errors->has('fecha_nacimiento_credito'))
                                                                            <span class="invalid-feedback" role="alert">
                                                                                <strong>{{ $errors->first('fecha_nacimiento_credito') }}</strong>
                                                                            </span>
                                                                            @endif
                                                                    </div>
                                                                                    

                                                                </div>

                                                                <div class="col-lg-6 col-xs-12">
                                                                    <label class="">Cargo</label>
                                                                    <select class="select2_form form-control {{ $errors->has('cargo_credito') ? ' is-invalid' : '' }}" style="text-transform: uppercase; width:100%" value="{{old('cargo_credito',$tienda->contacto_credito_cargo)}}" name="cargo_credito" id="cargo_credito" >
                                                                        <option></option>
                                                                        @foreach (cargos() as $cargo)
                                                                            <option value="{{ $cargo->descripcion }}" {{ (old('cargo_credito', $tienda->contacto_credito_cargo) == $cargo->descripcion ? "selected" : "") }}>{{ $cargo->descripcion }}</option>

                                                                        @endforeach

                                                                        @if ($errors->has('cargo_credito'))
                                                                        <span class="invalid-feedback" role="alert">
                                                                            <strong>{{ $errors->first('cargo_credito') }}</strong>
                                                                        </span>
                                                                        @endif
                                                                    </select>


    
                                                                    
                                                                </div>

                                                            </div>

                                                        
                                                        </div>

                                                        <div class="col-md-6">
                                                        <div class="form-group">
                                                                <label class="">Correo Electrónico</label>

                                                                <input type="email" id="correo_credito" name="correo_credito"
                                                                class="form-control {{ $errors->has('correo_credito') ? ' is-invalid' : '' }}" value="{{old('correo_credito',$tienda->contacto_credito_correo)}}" onkeyup="return mayus(this)">

                                                                @if ($errors->has('correo_credito'))
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $errors->first('correo_credito') }}</strong>
                                                                </span>
                                                                @endif

                                                            </div>

                                                            <div class="form-group row">
                                                                <div class="col-md-6">
                                                                    <label>Teléfono:</label>
                                                                    <input type="text" placeholder=""
                                                                        class="form-control {{ $errors->has('telefono_credito') ? ' is-invalid' : '' }}"
                                                                        name="telefono_credito" id="telefono_credito"  onkeyup="return mayus(this)"
                                                                        value="{{old('telefono_credito',$tienda->contacto_credito_telefono)}}">
                                                                    @if ($errors->has('telefono_credito'))
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $errors->first('telefono_credito') }}</strong>
                                                                    </span>
                                                                    @endif
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label>Celular:</label>
                                                                        <input type="text" placeholder=""
                                                                        class="form-control {{ $errors->has('celular_credito') ? ' is-invalid' : '' }}"
                                                                        name="celular_credito" id="celular_credito"  onkeyup="return mayus(this)"
                                                                        value="{{old('celular_credito',$tienda->contacto_credito_celular)}}">
                                                                        @if ($errors->has('celular_credito'))
                                                                        <span class="invalid-feedback" role="alert">
                                                                            <strong>{{ $errors->first('celular_credito') }}</strong>
                                                                        </span>
                                                                        @endif
                                                                </div>

                                                            </div>
                                                            
                                                      

                                                        </div>

                                                    </div>

                                                    <hr>

                                                    <div class="form-group row">
                       
                                                        <div class="col-md-6 b-r">

                                                            <div class="form-group">
                                                                
                                                                <h4><b>Contacto: "Vendedor"</b></h4>
                                                                <p>Registrar datos del contacto Vendedor:</p>
                                                                
                                                            </div>

                                                            <div class="form-group">
                                                                <label class="">Nombre</label>

                                                                <input type="text" id="nombre_vendedor" name="nombre_vendedor"
                                                                class="form-control {{ $errors->has('nombre_vendedor') ? ' is-invalid' : '' }}"
                                                                value="{{old('nombre_vendedor',$tienda->contacto_vendedor_nombre)}}" onkeyup="return mayus(this)">

                                                                @if ($errors->has('nombre_vendedor'))
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $errors->first('nombre_vendedor') }}</strong>
                                                                </span>
                                                                @endif

                                                            </div>

                                                            
                                                            <div class="form-group row">

                                                                <div class="col-lg-6 col-xs-12" id="fecha_nacimiento_vendedor_campo">
                                                                    <label class="">Fecha de Nacimiento</label>
                                                                    <div class="input-group date">
                                                                        <span class="input-group-addon">
                                                                            <i class="fa fa-calendar"></i>
                                                                        </span>
                                                                        <input type="text" id="fecha_nacimiento_vendedor" name="fecha_nacimiento_vendedor"
                                                                            class="form-control {{ $errors->has('fecha_nacimiento_vendedor') ? ' is-invalid' : '' }}"
                                                                            value="{{old('fecha_nacimiento_vendedor',getFechaFormato($tienda->contacto_vendedor_fecha_nacimiento, 'd/m/Y'))}}"
                                                                            autocomplete="off" readonly>

                                                                            @if ($errors->has('fecha_nacimiento_vendedor'))
                                                                            <span class="invalid-feedback" role="alert">
                                                                                <strong>{{ $errors->first('fecha_nacimiento_vendedor') }}</strong>
                                                                            </span>
                                                                            @endif
                                                                    </div>
                                                                                    

                                                                </div>

                                                                <div class="col-lg-6 col-xs-12">
                                                                    <label class="">Cargo</label>
                                                                    <select class="select2_form form-control {{ $errors->has('cargo_vendedor') ? ' is-invalid' : '' }}" style="text-transform: uppercase; width:100%" value="{{old('cargo_vendedor',$tienda->contacto_vendedor_cargo)}}" name="cargo_vendedor" id="cargo_vendedor">
                                                                        <option></option>


                                                                        @foreach(cargos() as $cargo)
                                                                            <option value="{{ $cargo->descripcion }}" {{ (old('cargo_vendedor', $tienda->contacto_vendedor_cargo) == $cargo->descripcion ? "selected" : "") }}>{{ $cargo->descripcion }}</option>
                                                                        @endforeach

                                                                        @if ($errors->has('cargo_vendedor'))
                                                                        <span class="invalid-feedback" role="alert">
                                                                            <strong>{{ $errors->first('cargo_vendedor') }}</strong>
                                                                        </span>
                                                                        @endif
                                                                    </select>


    
                                                                    
                                                                </div>

                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                        <div class="form-group">
                                                                <label class="">Correo: </label>

                                                                <input type="email"
                                                                    class="form-control {{ $errors->has('correo_vendedor') ? ' is-invalid' : '' }}"
                                                                    name="correo_vendedor" value="{{ old('correo_vendedor',$tienda->contacto_vendedor_correo)}}" id="correo"
                                                                    onkeyup="return mayus(this)">

                                                                @if ($errors->has('correo_vendedor'))
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $errors->first('correo_vendedor') }}</strong>
                                                                </span>
                                                                @endif
                                                            </div>
                                                            
                                                        <div class="form-group row">
                                                                <div class="col-md-6">
                                                                        <label>Teléfono:</label>
                                                                        <input type="text" placeholder=""
                                                                            class="form-control {{ $errors->has('telefono_vendedor') ? ' is-invalid' : '' }}"
                                                                            name="telefono_vendedor" id="telefono_vendedor"  onkeyup="return mayus(this)"
                                                                            value="{{old('telefono_vendedor',$tienda->contacto_vendedor_telefono)}}">
                                                                        @if ($errors->has('telefono_vendedor'))
                                                                        <span class="invalid-feedback" role="alert">
                                                                            <strong>{{ $errors->first('telefono_vendedor') }}</strong>
                                                                        </span>
                                                                        @endif
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label>Celular:</label>
                                                                        <input type="text" placeholder=""
                                                                        class="form-control {{ $errors->has('celular_vendedor') ? ' is-invalid' : '' }}"
                                                                        name="celular_vendedor" id="celular_vendedor"  onkeyup="return mayus(this)"
                                                                        value="{{old('celular_vendedor',$tienda->contacto_vendedor_celular)}}">
                                                                        @if ($errors->has('celular_vendedor'))
                                                                        <span class="invalid-feedback" role="alert">
                                                                            <strong>{{ $errors->first('celular_vendedor') }}</strong>
                                                                        </span>
                                                                        @endif
                                                                </div>

                                                            </div>


                                                        </div>



                                                    </div>



                                                </div>
                                            </div>
                                    </div>
                                
                                </div>
                            </div>
                        </div>


                        <div class="hr-line-dashed"></div>
                        <div class="form-group row">

                            <div class="col-md-6 text-left" style="color:#fcbc6c">
                                <i class="fa fa-exclamation-circle"></i> <small>Los campos marcados con asterisco
                                    (<label class="required"></label>) son obligatorios.</small>
                            </div>

                            <div class="col-md-6 text-right">
                                <a  href="{{route('clientes.tienda.index',$tienda->cliente->id)}}" id="btn_cancelar"
                                    class="btn btn-w-m btn-default">
                                    <i class="fa fa-arrow-left"></i> Regresar
                                </a>
                                <button type="submit" id="btn_grabar" class="btn btn-w-m btn-primary">
                                    <i class="fa fa-save"></i> Grabar
                                </button>
                            </div>

                        </div>

                    </form>

                </div>


            </div>
        </div>

    </div>

</div>

@stop

@push('styles')

<link href="{{asset('Inspinia/css/plugins/select2/select2.min.css')}}" rel="stylesheet">
<!-- DataTable -->
<link href="{{asset('Inspinia/css/plugins/dataTables/datatables.min.css')}}" rel="stylesheet">

<link href="{{ asset('Inspinia/css/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css') }}"
    rel="stylesheet">
<link href="{{ asset('Inspinia/css/plugins/datapicker/datepicker3.css') }}" rel="stylesheet">
<link href="{{ asset('Inspinia/css/plugins/daterangepicker/daterangepicker-bs3.css') }}" rel="stylesheet">

<style>
.logo {
    width: 200px;
    height: 200px;
    border-radius: 10%;
}

div.dataTables_wrapper div.dataTables_paginate ul.pagination {  
    margin-left:2px;
}
</style>
@endpush

@push('scripts')
<!-- Select2 -->
<script src="{{asset('Inspinia/js/plugins/select2/select2.full.min.js')}}"></script>

<!-- DataTable -->
<script src="{{asset('Inspinia/js/plugins/dataTables/datatables.min.js')}}"></script>

<!-- Data picker -->
<script src="{{ asset('Inspinia/js/plugins/datapicker/bootstrap-datepicker.js') }}"></script>
<!-- Date range use moment.js same as full calendar plugin -->
<script src="{{ asset('Inspinia/js/plugins/fullcalendar/moment.min.js') }}"></script>
<!-- Date range picker -->
<script src="{{ asset('Inspinia/js/plugins/daterangepicker/daterangepicker.js') }}"></script>

<script>

$('#fecha_nacimiento_administrador_campo .input-group.date').datepicker({
    todayBtn: "linked",
    keyboardNavigation: false,
    forceParse: false,
    autoclose: true,
    language: 'es',
    format: "dd/mm/yyyy",
})

$('#fecha_nacimiento_credito_campo .input-group.date').datepicker({
    todayBtn: "linked",
    keyboardNavigation: false,
    forceParse: false,
    autoclose: true,
    language: 'es',
    format: "dd/mm/yyyy",
})

$('#fecha_nacimiento_vendedor_campo .input-group.date').datepicker({
    todayBtn: "linked",
    keyboardNavigation: false,
    forceParse: false,
    autoclose: true,
    language: 'es',
    format: "dd/mm/yyyy",
})

//Añadir Entidad Financiera
function agregarEntidad() {
    $('#modal_agregar_entidad').modal('show');
}
$.fn.select2.defaults.set('language', 'es');

//Select2
$(".select2_form").select2({
    placeholder: "SELECCIONAR",
    allowClear: true,
    height: '200px',
    width: '100%',
});

// Solo campos numericos
$('#ruc').on('input', function() {
    this.value = this.value.replace(/[^0-9]/g, '');
});

$('#dni_representante').on('input', function() {
    this.value = this.value.replace(/[^0-9]/g, '');
});

$('#telefono').on('input', function() {
    this.value = this.value.replace(/[^0-9]/g, '');
});

$('#celular').on('input', function() {
    this.value = this.value.replace(/[^0-9]/g, '');
});


$('#telefono_administrador').on('input', function() {
    this.value = this.value.replace(/[^0-9]/g, '');
});

$('#celular_administrador').on('input', function() {
    this.value = this.value.replace(/[^0-9]/g, '');
});

$('#telefono_credito').on('input', function() {
    this.value = this.value.replace(/[^0-9]/g, '');
});

$('#celular_credito').on('input', function() {
    this.value = this.value.replace(/[^0-9]/g, '');
});

$('#telefono_vendedor').on('input', function() {
    this.value = this.value.replace(/[^0-9]/g, '');
});

$('#celular_vendedor').on('input', function() {
    this.value = this.value.replace(/[^0-9]/g, '');
});


$('#enviar_tienda').submit(function(e) {
    e.preventDefault();
    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger',
        },
        buttonsStyling: false
    })

    Swal.fire({
        title: 'Opción Guardar',
        text: "¿Seguro que desea modificar cambios?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: "#1ab394",
        confirmButtonText: 'Si, Confirmar',
        cancelButtonText: "No, Cancelar",
    }).then((result) => {
        if (result.isConfirmed) {

                this.submit();



        } else if (
            /* Read more about handling dismissals below */
            result.dismiss === Swal.DismissReason.cancel
        ) {
            swalWithBootstrapButtons.fire(
                'Cancelado',
                'La Solicitud se ha cancelado.',
                'error'
            )
        }
    })
})


// Consulta Ruc
$("#ruc").keypress(function() {
    if (event.which == 13) {
        event.preventDefault();
        var ruc = $("#ruc").val()
        evaluarRuc(ruc);
    }
})
$("#ruc").keyup(function() {
    if ($('#estado').val('ACTIVO')) {
        $('#estado').val('INACTIVO');
    }
})


$('.tabs-container .nav-tabs #contactos').click(function() {
    limpiarErrores()
    var enviar = true;

    if ($('#tipo_tienda').val() == '') {
        enviar = false
        $('#tipo_tienda').addClass("is-invalid")
        toastr.error("Seleccione un tipo de tienda antes de ingresar un contacto.", 'Error');
        $('#error-tipo').text("El campo Tipo es obligatorio.")
    }

    if ($('#tipo_negocio').val() == '') {
        enviar = false
        $('#tipo_negocio').addClass("is-invalid")
        toastr.error("Seleccione un tipo de negocio antes de ingresar un contacto.", 'Error');
        $('#error-negocio').text("El campo Negocio es obligatorio.")
    }

    if ($('#nombre').val() == '') {
        enviar = false
        $('#nombre').addClass("is-invalid")
        toastr.error("Ingrese el nombre de la tienda antes de ingresar un contacto.", 'Error');
        $('#error-nombre').text("El campo Nombre es obligatorio.")
    }

    
    if ($('#direccion').val() == '') {
        enviar = false
        $('#direccion').addClass("is-invalid")
        toastr.error("Ingrese la dirección de la tienda antes de ingresar un contacto.", 'Error');
        $('#error-direccion').text("El campo Dirección es obligatorio.")
    }

    return enviar

})


function limpiarErrores() {
    $('#tipo_tienda').removeClass("is-invalid")
    $('#error-tipo').text("")

    $('#tipo_negocio').removeClass("is-invalid")
    $('#error-negocio').text("")

    $('#nombre').removeClass("is-invalid")
    $('#error-nombre').text("")

    $('#direccion').removeClass("is-invalid")
    $('#error-direccion').text("")
}


</script>
@endpush