@extends('layout') @section('content')

@section('ventas-active', 'active')
@section('clientes-active', 'active')

<div class="row wrapper border-bottom white-bg page-heading">

    <div class="col-lg-12">
       <h2  style="text-transform:uppercase"><b>REGISTRAR NUEVA TIENDA DEL CLIENTE : {{$cliente->nombre}} </b></h2>

        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{route('home')}}">Panel de Control</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{route('ventas.cliente.index')}}">Clientes</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{route('clientes.tienda.index',$cliente->id)}}">Tiendas</a>
            </li>
            <li class="breadcrumb-item active">
                <strong>Registrar</strong>
            </li>

        </ol>
    </div>



</div>


<div class="wrapper wrapper-content animated fadeInRight">

    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">

                <div class="ibox-content">

                    <form action="{{route('clientes.tienda.store')}}" method="POST" 
                        id="enviar_empresa">
                        {{csrf_field()}}


                        <div class="row">
                            <div class="col-lg-12">
                                <div class="tabs-container">
                                    <ul class="nav nav-tabs">
                                        <li title="Datos de la Tienda">
                                            <a class="nav-link active" id="datos_tienda" data-toggle="tab" href="#tab-1" id="tiendas" ><i class="fa fa-shopping-cart"></i> Tienda</a>
                                        </li>
                                        <li title="Datos de Envio">
                                            <a class="nav-link" data-toggle="tab"href="#tab-2" id="envios" > <i class="fa fa-send"></i> Envio</a>
                                        </li>
                                        <li title="Datos de los Contactos">
                                            <a class="nav-link" data-toggle="tab"href="#tab-3" id="contactos" > <i class="fa fa-user"></i> Contactos</a>
                                        </li>
                                    </ul>
                                    <div class="tab-content">
                                            <div id="tab-1" class="tab-pane active">
                                                <div class="panel-body">

                                                <input type="hidden" name="cliente_id" value="{{$cliente->id}}">


                                                    <div class="row">
                                                        <div class="col-md-6 b-r">
                                                            <h4><b>Tienda</b></h4>
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <p>Registrar datos de la nueva tienda:</p>
                                                                </div>
                                                            </div>

                                                            <div class="form-group row">
                                                                <div class="col-md-6">
                                                                    <label class="required">Tipo:</label>

                                                                    <select class="select2_form form-control {{ $errors->has('tipo_tienda') ? ' is-invalid' : '' }}" style="text-transform: uppercase; width:100%" value="{{old('tipo_tienda')}}" name="tipo_tienda" id="tipo_tienda" required>
                                                                        <option></option>
                                                                        @foreach (tipos_tienda() as $tienda)
                                                                            <option value="{{$tienda->descripcion}}" @if(old('tipo_tienda') == $tienda->descripcion) {{'selected'}} @endif>{{$tienda->descripcion}}</option>
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
                                                                        <select class="select2_form form-control {{ $errors->has('tipo_negocio') ? ' is-invalid' : '' }}" style="text-transform: uppercase; width:100%" value="{{old('tipo_negocio')}}" name="tipo_negocio" id="tipo_negocio" required>
                                                                            <option></option>
                                                                            @foreach (tipos_negocio() as $negocio)
                                                                                <option value="{{$negocio->descripcion}}" @if(old('tipo_negocio') == $tienda->descripcion ) {{'selected'}} @endif>{{$negocio->descripcion}}</option>
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
                                                                    name="nombre" value="{{ old('nombre')}}" id="nombre"
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
                                                                        class="form-control {{ $errors->has('direccion') ? ' is-invalid' : '' }}" value="{{old('direccion')}}"
                                                                        onkeyup="return mayus(this)" >{{old('direccion')}}</textarea>
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
                                                                    name="correo" value="{{ old('correo')}}" id="correo"
                                                                    onkeyup="return mayus(this)">

                                                                @if ($errors->has('correo'))
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $errors->first('correo') }}</strong>
                                                                </span>
                                                                @endif
                                                            </div>

                                                            <div class="form-group row">
                                                                <div class="col-md-6">
                                                                    <label>Teléfono:</label>
                                                                    <input type="text" placeholder=""
                                                                        class="form-control {{ $errors->has('telefono') ? ' is-invalid' : '' }}"
                                                                        name="telefono" id="telefono"  onkeyup="return mayus(this)"
                                                                        value="{{old('telefono')}}">
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
                                                                        value="{{old('celular')}}">
                                                                    @if ($errors->has('celular'))
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $errors->first('celular') }}</strong>
                                                                    </span>
                                                                    @endif
                                                                </div>

                                                            </div>



                                                        </div>
                                                        <div class="col-md-6">



                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <p>Horario de Atención:</p>
                                                                </div>
                                                            </div>

                                                            
                                                            <div class="form-group row">
                                                                <div class="col-md-6">
                                                                    <label>Horario Inicio:</label>
                                                                    <input type="time" name="hora_inicio" class="form-control" value="00:00:00" max="24:00:00" min="00:00:00" step="1">
                                                                    @if ($errors->has('horario_inicio'))
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $errors->first('horario_inicio') }}</strong>
                                                                    </span>
                                                                    @endif
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label>Horario Termino:</label>                                             
                                                                    <input type="time" name="hora_termino" class="form-control" value="00:00:00" max="24:00:00" min="00:00:00" step="1">
                                                                    @if ($errors->has('horario_termino'))
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $errors->first('horario_termino') }}</strong>
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
                                                                                class="form-control {{ $errors->has('facebook') ? ' is-invalid' : '' }}" onkeyup="return mayus(this)"  value="{{old('facebook')}}">

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
                                                                            class="form-control {{ $errors->has('instagram') ? ' is-invalid' : '' }}" onkeyup="return mayus(this)"  value="{{old('instagram')}}">

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
                                                                            class="form-control {{ $errors->has('web') ? ' is-invalid' : '' }}" onkeyup="return mayus(this)"  value="{{old('web')}}">

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
                                                                
                                                                <h4><b>Datos de Envio</b></h4>
                                                                <p>Registrar datos de envio:</p>
                                                                
                                                            </div>

                                                            <div class="form-group">

                                                                <label class="required">Condicion de Reparto</label>
                                                                <select id="condicion_reparto" name="condicion_reparto" class="select2_form form-control {{ $errors->has('condicion_reparto') ? ' is-invalid' : '' }}">
                                                                    <option></option>
                                                                    @foreach(condicion_reparto() as $condicion_reparto)
                                                                        <option value="{{ $condicion_reparto->id }}" @if(old('condicion_reparto') == $condicion_reparto->descripcion) {{'selected'}} @endif>{{ $condicion_reparto->descripcion }}</option>
                                                                    @endforeach
                                                                </select>
                                                                @if ($errors->has('condicion_reparto'))
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $errors->first('condicion_reparto') }}</strong>
                                                                    </span>
                                                                @endif
                                                                <div class="invalid-feedback"><b><span id="error-condicion_reparto"></span></b></div>

                                                            </div>


                                                            <div class="form-group">
                                                                <p class="text-center">
                                                                    <a href=""><i class="fa fa-sign-in big-icon"></i></a>
                                                                </p>

                                                            </div>


                                                        </div>

                                                        <div class="col-md-6">

                                                            <div id="reparto_oficina">

                                                                <div class="form-group">
                                                                    
                                                                    <h4><b>Datos según la condición de Reparto "Oficina"</b></h4>
                                                                    <p>Registrar Transporte :</p>
                                                                    
                                                                </div>

                                                               

                                                                <div class="form-group">
                                                                    <label class="required">Nombre </label>

                                                                    <input type="text" class="form-control {{ $errors->has('nombre_transporte_oficina') ? ' is-invalid' : '' }}"
                                                                    name="nombre_transporte_oficina" value="{{ old('nombre_transporte_oficina')}}" id="nombre_transporte_oficina"
                                                                    onkeyup="return mayus(this)" disabled>

                                                                    @if ($errors->has('nombre_transporte_oficina'))
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $errors->first('nombre_transporte_oficina') }}</strong>
                                                                    </span>
                                                                    @endif

                                                                    <div class="invalid-feedback"><b><span id="error-nombre_transporte_oficina"></span></b></div>

                                                                </div>

                                                                <div class="form-group">

                                                                                                                                                                                    
                                                                    <label class="required">Dirección </label>

                                                                    <input type="text" class="form-control {{ $errors->has('direccion_transporte_oficina') ? ' is-invalid' : '' }}"
                                                                        name="direccion_transporte_oficina" value="{{ old('direccion_transporte_oficina')}}" id="direccion_transporte_oficina"
                                                                        onkeyup="return mayus(this)" disabled>

                                                                        @if ($errors->has('direccion_transporte_oficina'))
                                                                        <span class="invalid-feedback" role="alert">
                                                                            <strong>{{ $errors->first('direccion_transporte_oficina') }}</strong>
                                                                        </span>
                                                                        @endif

                                                                    <div class="invalid-feedback"><b><span id="error-direccion_transporte_oficina"></span></b></div>
                                                                    
                                                                
                                                                
                                                                </div>

                                                                <div class="form-group">
                                                            
                                                                    <p>Registrar Responsable (Pago de flete) :</p>
                                                                    
                                                                </div>


                                                                <div class="form-group row">

                                                                    <div class="col-md-7">
                          
                                                                        <label class="required">Nombre</label>

                                                                        <input type="text" class="form-control {{ $errors->has('responsable_pago_flete') ? ' is-invalid' : '' }}"
                                                                        name="responsable_pago_flete" value="{{ old('responsable_pago_flete')}}" id="responsable_pago_flete"
                                                                        onkeyup="return mayus(this)" disabled>

                                                                            @if ($errors->has('responsable_pago_flete'))
                                                                            <span class="invalid-feedback" role="alert">
                                                                                <strong>{{ $errors->first('responsable_pago_flete') }}</strong>
                                                                            </span>
                                                                            @endif

                                                                        <div class="invalid-feedback"><b><span id="error-responsable_pago_flete"></span></b></div>                                          
                                                                    
                                                                    </div>

                                                                    <div class="col-md-5">
                                                                        <label class="required">Modo</label>
                                                                        <select id="responsable_pago" name="responsable_pago" class="select2_form form-control {{ $errors->has('responsable_pago') ? ' is-invalid' : '' }}" disabled value="{{old('responsable_pago')}}"required>
                                                                            <option></option>
                                                                            @foreach(modo_responsables() as $responsable)
                                                                                <option value="{{ $responsable->id }}" {{ (old('responsable_pago') == $responsable->id ? "selected" : "") }} >{{ $responsable->descripcion }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                        @if ($errors->has('responsable_pago'))
                                                                            <span class="invalid-feedback" role="alert">
                                                                                <strong>{{ $errors->first('responsable_pago') }}</strong>
                                                                            </span>
                                                                        @endif

                                                                        <div class="invalid-feedback"><b><span id="error-responsable_pago"></span></b></div>  
                                                                    
                                                                    
                                                                    </div>



                                                                </div>

                                                                <div class="form-group">
                                                                    <p>Registrar Responsable (Recoger el envio) :</p>
                                                                </div>

                                                                <div class="form-group row">

                                                                    <div class="col-md-7">
                                                                        <label class="required">Dni</label>

                                                                        <div class="input-group">
                                                                            <input type="text" class="form-control {{ $errors->has('dni_responsable_recoger') ? ' is-invalid' : '' }}" name="dni_responsable_recoger" min="8" value="{{ old('dni_responsable_recoger')}}" id="dni_responsable_recoger" onkeyup="return mayus(this)" disabled> 
                                                                            <span class="input-group-append"><a style="color:white" onclick="consultarDni()" class="btn btn-primary"><i class="fa fa-search"></i> Reniec</a></span>

                                                                            @if ($errors->has('dni_responsable_recoger'))
                                                                            <span class="invalid-feedback" role="alert">
                                                                                <strong>{{ $errors->first('dni_responsable_recoger') }}</strong>
                                                                            </span>
                                                                            @endif
                                                                            <div class="invalid-feedback"><b><span id="error-dni_responsable_recoger"></span></b></div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-5">
                                                                        <label class="">Estado</label>

                                                                        <input type="text" class="form-control  text-center {{ $errors->has('estado_responsable_recoger') ? ' is-invalid' : '' }}"
                                                                        name="estado_responsable_recoger" value="{{ old('estado_responsable_recoger','SIN VERIFICAR')}}" id="estado_responsable_recoger"
                                                                        onkeyup="return mayus(this)" disabled>

                                                                            @if ($errors->has('estado_responsable_recoger'))
                                                                            <span class="invalid-feedback" role="alert">
                                                                                <strong>{{ $errors->first('estado_responsable_recoger') }}</strong>
                                                                            </span>
                                                                            @endif

                                                                        <div class="invalid-feedback"><b><span id="error-estado_responsable_recoger"></span></b></div>
                                                                    </div>



                                                                </div>

                                                                <div class="form-group row">
                                                                    <div class="col-md-7">
                                                                        
                                                                        <label class="required">Nombre</label>

                                                                        <input type="text" class="form-control {{ $errors->has('nombre_responsable_recoger') ? ' is-invalid' : '' }}"
                                                                        name="nombre_responsable_recoger" value="{{ old('nombre_responsable_recoger')}}" id="nombre_responsable_recoger"
                                                                        onkeyup="return mayus(this)" disabled>

                                                                            @if ($errors->has('nombre_responsable_recoger'))
                                                                            <span class="invalid-feedback" role="alert">
                                                                                <strong>{{ $errors->first('nombre_responsable_recoger') }}</strong>
                                                                            </span>
                                                                            @endif

                                                                        <div class="invalid-feedback"><b><span id="error-nombre_responsable_recoger"></span></b></div>

                                                                    </div>
                                                                    <div class="col-md-5">
                                                                        <label class="">Telefono</label>

                                                                        <input type="text" class="form-control {{ $errors->has('telefono_responsable_recoger') ? ' is-invalid' : '' }}"
                                                                        name="telefono_responsable_recoger" value="{{ old('telefono_responsable_recoger')}}" id="telefono_responsable_recoger"
                                                                        onkeyup="return mayus(this)" disabled>

                                                                            @if ($errors->has('telefono_responsable_recoger'))
                                                                            <span class="invalid-feedback" role="alert">
                                                                                <strong>{{ $errors->first('telefono_responsable_recoger') }}</strong>
                                                                            </span>
                                                                            @endif

                                                                        <div class="invalid-feedback"><b><span id="error-telefono_responsable_recoger"></span></b></div>
                                                                        
                                                                    </div>
                                                                </div>

                                                                <div class="form-group">
                                                                    <label>Observación:</label>
                                                                    <textarea type="text" placeholder=""
                                                                        class="form-control {{ $errors->has('observacion_envio') ? ' is-invalid' : '' }}"
                                                                        name="observacion_envio" id="observacion_envio"  onkeyup="return mayus(this)"
                                                                        value="{{old('observacion_envio')}}" disabled>{{old('observacion_envio')}}</textarea>
                                                                    @if ($errors->has('observacion_envio'))
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $errors->first('observacion_envio') }}</strong>
                                                                    </span>
                                                                    @endif


                                                                </div>

                                                            </div>

                                                            <div id="reparto_domicilio" style="display:none;">

                                                                <div class="form-group">
                                                                    
                                                                    <h4><b>Datos según la condición de Reparto "Domicilio"</b></h4>
                                                                    <p>Registrar Transporte :</p>
                                                                    
                                                                </div>

                                                                <div class="form-group">

                                                                   
                                                                    <label class="required">Nombre </label>

                                                                    <input type="text" class="form-control {{ $errors->has('nombre_transporte_domicilio') ? ' is-invalid' : '' }}"
                                                                    name="nombre_transporte_domicilio" value="{{ old('nombre_transporte_domicilio')}}" id="nombre_transporte_domicilio"
                                                                    onkeyup="return mayus(this)" disabled>

                                                                    @if ($errors->has('nombre_transporte_domicilio'))
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $errors->first('nombre_transporte_domicilio') }}</strong>
                                                                    </span>
                                                                    @endif

                                                                    <div class="invalid-feedback"><b><span id="error-nombre_transporte_domicilio"></span></b></div>
                                                                
                                                                </div>

                                                                  

                                                                <div class="form-group">

                                                                                                                                                                                    
                                                                    <label class="required">Dirección del domicilio </label>

                                                                    <input type="text" class="form-control {{ $errors->has('direccion_domicilio') ? ' is-invalid' : '' }}"
                                                                        name="direccion_domicilio" value="{{ old('direccion_domicilio')}}" id="direccion_domicilio"
                                                                        onkeyup="return mayus(this)" disabled>

                                                                        @if ($errors->has('direccion_domicilio'))
                                                                        <span class="invalid-feedback" role="alert">
                                                                            <strong>{{ $errors->first('direccion_domicilio') }}</strong>
                                                                        </span>
                                                                        @endif

                                                                    <div class="invalid-feedback"><b><span id="error-direccion_domicilio"></span></b></div>
                                                                    

                                                                </div>


                                                                <div class="form-group">
                                                                    <p>Registrar Contacto de recoger el envio:</p>
                                                                </div>


                                                                <div class="form-group row">
                                                                    <div class="col-md-6">
                                                                        
                                                                        <label class="">Nombre</label>

                                                                        <input type="text" class="form-control {{ $errors->has('nombre_contacto_recoger') ? ' is-invalid' : '' }}"
                                                                        name="nombre_contacto_recoger" value="{{ old('nombre_contacto_recoger')}}" id="nombre_contacto_recoger"
                                                                        onkeyup="return mayus(this)" disabled>

                                                                            @if ($errors->has('nombre_contacto_recoger'))
                                                                            <span class="invalid-feedback" role="alert">
                                                                                <strong>{{ $errors->first('nombre_contacto_recoger') }}</strong>
                                                                            </span>
                                                                            @endif

                                                                        <div class="invalid-feedback"><b><span id="error-nombre_contacto_recoger"></span></b></div>

                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <label class="">Telefono</label>

                                                                        <input type="text" class="form-control {{ $errors->has('telefono_contacto_recoger') ? ' is-invalid' : '' }}"
                                                                        name="telefono_contacto_recoger" value="{{ old('telefono_contacto_recoger')}}" id="telefono_contacto_recoger"
                                                                        onkeyup="return mayus(this)" disabled>

                                                                            @if ($errors->has('telefono_contacto_recoger'))
                                                                            <span class="invalid-feedback" role="alert">
                                                                                <strong>{{ $errors->first('telefono_contacto_recoger') }}</strong>
                                                                            </span>
                                                                            @endif

                                                                        <div class="invalid-feedback"><b><span id="error-telefono_contacto_recoger"></span></b></div>
                                                                        
                                                                    </div>
                                                                </div>

                                                                <div class="form-group">
                                                                    <label>Observación:</label>
                                                                    <textarea type="text" placeholder=""
                                                                        class="form-control {{ $errors->has('observacion_domicilio') ? ' is-invalid' : '' }}"
                                                                        name="observacion_domicilio" id="observacion_domicilio"  onkeyup="return mayus(this)"
                                                                        value="{{old('observacion_domicilio')}}" disabled>{{old('observacion_domicilio')}}</textarea>
                                                                    @if ($errors->has('observacion_domicilio'))
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $errors->first('observacion_domicilio') }}</strong>
                                                                    </span>
                                                                    @endif


                                                                </div>

                                                            </div>


                                                        </div>




                                                    </div>

                                                </div>
                                            </div>

                                            <div id="tab-3" class="tab-pane">
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
                                                                value="{{old('nombre_administrador')}}" onkeyup="return mayus(this)">

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
                                                                            value="{{old('fecha_nacimiento_administrador',getFechaFormato($fecha_hoy, 'd/m/Y'))}}"
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
                                                                    <select class="select2_form form-control {{ $errors->has('cargo_administrador') ? ' is-invalid' : '' }}" style="text-transform: uppercase; width:100%" value="{{old('cargo_administrador')}}" name="cargo_administrador" id="cargo_administrador">
                                                                        <option></option>
                                                                        @foreach (cargos() as $cargo)
                                                                            <option value="{{$cargo->descripcion}}" @if(old('cargo_administrador') ) {{'selected'}} @endif>{{$cargo->descripcion}}</option>
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
                                                                value="{{old('correo_administrador')}}" onkeyup="return mayus(this)">

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
                                                                        value="{{old('telefono_administrador')}}">
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
                                                                        value="{{old('celular_administrador')}}">
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
                                                                value="{{old('nombre_credito')}}" onkeyup="return mayus(this)">

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
                                                                            value="{{old('fecha_nacimiento_credito',getFechaFormato($fecha_hoy, 'd/m/Y'))}}"
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
                                                                    <select class="select2_form form-control {{ $errors->has('cargo_credito') ? ' is-invalid' : '' }}" style="text-transform: uppercase; width:100%" value="{{old('cargo_credito')}}" name="cargo_credito" id="cargo_credito">
                                                                        <option></option>
                                                                        @foreach (cargos() as $cargo)
                                                                            <option value="{{$cargo->descripcion}}" @if(old('cargo_credito') ) {{'selected'}} @endif>{{$cargo->descripcion}}</option>
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
                                                                class="form-control {{ $errors->has('correo_credito') ? ' is-invalid' : '' }}" value="{{old('correo_credito')}}" onkeyup="return mayus(this)">

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
                                                                        value="{{old('telefono_credito')}}">
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
                                                                        value="{{old('celular_credito')}}">
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
                                                                value="{{old('nombre_vendedor')}}" onkeyup="return mayus(this)">

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
                                                                            value="{{old('fecha_nacimiento_vendedor',getFechaFormato($fecha_hoy, 'd/m/Y'))}}"
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
                                                                    <select class="select2_form form-control {{ $errors->has('cargo_vendedor') ? ' is-invalid' : '' }}" style="text-transform: uppercase; width:100%" value="{{old('cargo_vendedor')}}" name="cargo_vendedor" id="cargo_vendedor">
                                                                        <option></option>
                                                                        @foreach (cargos() as $cargo)
                                                                            <option value="{{$cargo->descripcion}}" @if(old('cargo_vendedor') ) {{'selected'}} @endif>{{$cargo->descripcion}}</option>
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
                                                                    name="correo_vendedor" value="{{ old('correo_vendedor')}}" id="correo"
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
                                                                            value="{{old('telefono_vendedor')}}">
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
                                                                        value="{{old('celular_vendedor')}}">
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
                                <a href="{{route('clientes.tienda.index',$cliente->id)}}" id="btn_cancelar"
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

function validarCampos() {
  
  var campos = true
  if ($('#tipo_tienda').val() == '') {
      campos = false  
      $('#tiendas').click() 
  }
  if ($('#tipo_negocio').val() == ''){
      campos = false
      $('#tiendas').click()
  }

  if ($('#nombre').val() == ''){
      campos = false
      $('#tiendas').click()
  }

  if ($('#direccion').val() == ''){
      campos = false
      $('#tiendas').click()
  } 

  if ($("#condicion_reparto").val() == '68') {

    if ($('#nombre_transporte_oficina').val() == ''){
      campos = false
      $('#envios').click()
    } 

    if ($('#direccion_transporte_oficina').val() == ''){
      campos = false
      $('#envios').click()
    } 

    if ($('#responsable_pago_flete').val() == ''){
      campos = false
      $('#envios').click()
    } 

    if ($('#dni_responsable_recoger').val() == ''){
      campos = false
      $('#envios').click()
    } 

    if ($('#nombre_responsable_recoger').val() == ''){
      campos = false
      $('#envios').click()
    } 

  }else{

    if ($('#nombre_transporte_domicilio').val() == ''){
      campos = false
      $('#envios').click()
    } 

    if ($('#direccion_domicilio').val() == ''){
      campos = false
      $('#envios').click()
    } 

    if ($('#nombre_contacto_recoger').val() == ''){
      campos = false
      $('#envios').click()
    } 

  }


  return campos
}

$('#enviar_empresa').submit(function(e) {
  
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
        text: "¿Seguro que desea guardar cambios?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: "#1ab394",
        confirmButtonText: 'Si, Confirmar',
        cancelButtonText: "No, Cancelar",
    }).then((result) => {
        if (result.isConfirmed) {
                var campos = validarCampos()
                if (campos == true) {
                    $('#estado_responsable_recoger').prop('disabled',false)
                    this.submit();
                }else{
                    toastr.error('Hay campos obligatorios sin ingresar','Error');
                  
                }
                



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



$('.tabs-container .nav-tabs #envios').click(function() {
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


    switch($("#condicion_reparto").val()) {
    case "68":
        if ($('#nombre_transporte_oficina').val() == '') {
            enviar = false
            $('#nombre_transporte_oficina').addClass("is-invalid")
            toastr.error("Ingrese el nombre del Transporte.", 'Error');
            $('#error-nombre_transporte_oficina').text("El campo Nombre es obligatorio.")
        }

        if ($('#direccion_transporte_oficina').val() == '') {
            enviar = false
            $('#direccion_transporte_oficina').addClass("is-invalid")
            toastr.error("Ingrese la dirección del Transporte.", 'Error');
            $('#error-direccion_transporte_oficina').text("El campo Dirección es obligatorio.")
        }

        if ($('#responsable_pago_flete').val() == '') {
            enviar = false
            $('#responsable_pago_flete').addClass("is-invalid")
            toastr.error("Ingrese el Nombre del Responsable (Pago de Flete).", 'Error');
            $('#error-responsable_pago_flete').text("El campo Nombre del Responsable es obligatorio.")
        }

        if ($('#dni_responsable_recoger').val() == '') {
            enviar = false
            $('#dni_responsable_recoger').addClass("is-invalid")
            toastr.error("Ingrese el Dni.", 'Error');
            $('#error-dni_responsable_recoger').text("El campo Dni es obligatorio.")
        }

        if ($('#nombre_responsable_recoger').val() == '') {
            enviar = false
            $('#nombre_responsable_recoger').addClass("is-invalid")
            toastr.error("Ingrese el Nombre.", 'Error');
            $('#error-nombre_responsable_recoger').text("El campo Nombre es obligatorio.")
        }

        if ($('#responsable_pago').val() == '') {
            enviar = false
            $('#responsable_pago').addClass("is-invalid")
            toastr.error("Ingrese el Nombre.", 'Error');
            $('#error-responsable_pago').text("El campo Modo es obligatorio.")
        }

        break;
    case "69":
        if ($('#nombre_transporte_domicilio').val() == '') {
            enviar = false
            $('#nombre_transporte_domicilio').addClass("is-invalid")
            toastr.error("Ingrese el Nombre.", 'Error');
            $('#error-nombre_transporte_domicilio').text("El campo Nombre del Transporte es obligatorio.")
        }

        if ($('#direccion_domicilio').val() == '') {
            enviar = false
            $('#direccion_domicilio').addClass("is-invalid")
            toastr.error("Ingrese la dirección del domicilio.", 'Error');
            $('#error-direccion_domicilio').text("El campo Nombre del Transporte es obligatorio.")
        }

        if ($('#nombre_contacto_recoger').val() == '') {
            enviar = false
            $('#nombre_contacto_recoger').addClass("is-invalid")
            toastr.error("Ingrese el nombre del contacto.", 'Error');
            $('#error-nombre_contacto_recoger').text("El campo Nombrees obligatorio.")
        }

        break;
    default:    
        if ($('#condicion_reparto').val() == '') {
            enviar = false
            $('#condicion_reparto').addClass("is-invalid")
            toastr.error("Ingrese la condición de Reparto.", 'Error');
            $('#error-condicion_reparto').text("El campo Condición Reparto es obligatorio.")
        }
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

    //CONDICION DE ENVIO

    $('#nombre_transporte_oficina').removeClass("is-invalid")
    $('#error-nombre_transporte_oficina').text("")

    $('#direccion_transporte_oficina').removeClass("is-invalid")
    $('#error-direccion_transporte_oficina').text("")

    $('#responsable_pago_flete').removeClass("is-invalid")
    $('#error-responsable_pago_flete').text("")

    $('#dni_responsable_recoger').removeClass("is-invalid")
    $('#error-dni_responsable_recoger').text("")

    $('#nombre_responsable_recoger').removeClass("is-invalid")
    $('#error-nombre_responsable_recoger').text("")

    $('#nombre_responsable_pago').removeClass("is-invalid")
    $('#error-responsable_pago').text("")


    $('#nombre_transporte_domicilio').removeClass("is-invalid")
    $('#error-nombre_transporte_domicilio').text("")

    $('#direccion_domicilio').removeClass("is-invalid")
    $('#error-direccion_domicilio').text("")

    $('#nombre_contacto_recoger').removeClass("is-invalid")
    $('#error-nombre_contacto_recoger').text("")

    $('#condicion_reparto').removeClass("is-invalid")
    $('#error-condicion_reparto').text("")


    $('#estado_responsable_recoger').val('SIN VERIFICAR')

}

//ENVIOS TAB

$(document).ready(function() {
    switch($('#condicion_reparto').val()) {
    case "68":
            $('#reparto_domicilio').css("display","none")
            $('#reparto_oficina').css("display","")

            $('#nombre_transporte_oficina').prop('disabled', false)
            $('#direccion_transporte_oficina').prop('disabled', false)
            $('#responsable_pago_flete').prop('disabled', false)

            $('#dni_responsable_recoger').prop('disabled', false)
            $('#nombre_responsable_recoger').prop('disabled', false)
            $('#responsable_pago_flete').prop('disabled', false)
            $('#telefono_responsable_recoger').prop('disabled', false)
            $('#observacion_envio').prop('disabled', false)
            $('#responsable_pago').prop('disabled', false)
            $('#responsable_pago').val($('#responsable_pago option:first-child').val()).trigger('change');
        break;
    case "69":
            $('#reparto_domicilio').css("display","")
            $('#reparto_oficina').css("display","none")

            $('#nombre_transporte_domicilio').prop('disabled', false)
            $('#direccion_domicilio').prop('disabled', false)
            $('#nombre_contacto_recoger').prop('disabled', false)
            $('#telefono_contacto_recoger').prop('disabled', false)
            $('#observacion_domicilio').prop('disabled', false)
        break;
    default:
            $('#nombre_transporte_oficina').prop('disabled', true)
            $('#direccion_transporte_oficina').prop('disabled', true)
            $('#responsable_pago_flete').prop('disabled', true)
            $('#responsable_pago').prop('disabled', true)
            $('#responsable_pago').val($('#responsable_pago option:first-child').val()).trigger('change');

            $('#dni_responsable_recoger').prop('disabled', true)
            $('#nombre_responsable_recoger').prop('disabled', true)
            $('#responsable_pago_flete').prop('disabled', true)
            $('#telefono_responsable_recoger').prop('disabled', true)
            $('#observacion_envio').prop('disabled', true)

            $('#nombre_transporte_domicilio').prop('disabled', true)
            $('#direccion_domicilio').prop('disabled', true)
            $('#nombre_contacto_recoger').prop('disabled', true)
            $('#telefono_contacto_recoger').prop('disabled', true)
            $('#observacion_domicilio').prop('disabled', true)
    }
})

function limpiarDomicilio(){

    $('#nombre_transporte_domicilio').val('')
    $('#direccion_domicilio').val('')
    $('#nombre_contacto_recoger').val('')
    $('#telefono_contacto_recoger').val('')
    $('#observacion_domicilio').val('')
}

function limpiarOficina(){
        $('#nombre_transporte_oficina').val('')
        $('#direccion_transporte_oficina').val('')
        $('#responsable_pago_flete').val('')

        $('#dni_responsable_recoger').val('')
        $('#nombre_responsable_recoger').val('')
        $('#responsable_pago_flete').val('')

        $('#responsable_pago').val($('#responsable_pago option:first-child').val()).trigger('change');
        
        $('#telefono_responsable_recoger').val('')
        $('#observacion_envio').val('')
}

$("#dni_responsable_recoger").keyup(function() {
    if ($('#estado_responsable_recoger').val('ACTIVO')) {
        $('#estado_responsable_recoger').val('SIN VERIFICAR')
    }
})

$("#nombre_responsable_recoger").keyup(function() {
    if ($('#estado_responsable_recoger').val('ACTIVO')) {
        $('#estado_responsable_recoger').val('SIN VERIFICAR')
    }
})


$("#condicion_reparto").on('change',function(e){
    limpiarErrores()
    $('#estado_responsable_recoger').val('SIN VERIFICAR')
    switch($(this).val()) {
    case "68":
            $('#reparto_domicilio').css("display","none")
            $('#reparto_oficina').css("display","")

            $('#nombre_transporte_oficina').prop('disabled', false)
            $('#direccion_transporte_oficina').prop('disabled', false)
            $('#responsable_pago_flete').prop('disabled', false)

            $('#responsable_pago').prop('disabled', false)

            $('#dni_responsable_recoger').prop('disabled', false)
            $('#nombre_responsable_recoger').prop('disabled', false)
            $('#responsable_pago_flete').prop('disabled', false)
            $('#telefono_responsable_recoger').prop('disabled', false)
            $('#observacion_envio').prop('disabled', false)
            limpiarDomicilio()
        break;
    case "69":
            $('#reparto_domicilio').css("display","")
            $('#reparto_oficina').css("display","none")

            $('#nombre_transporte_domicilio').prop('disabled', false)
            $('#direccion_domicilio').prop('disabled', false)
            $('#nombre_contacto_recoger').prop('disabled', false)
            $('#telefono_contacto_recoger').prop('disabled', false)
            $('#observacion_domicilio').prop('disabled', false)
            limpiarOficina()        
        break;
    default:
            $('#nombre_transporte_oficina').prop('disabled', true)
            $('#direccion_transporte_oficina').prop('disabled', true)
            $('#responsable_pago_flete').prop('disabled', true)

            $('#dni_responsable_recoger').prop('disabled', true)
            $('#nombre_responsable_recoger').prop('disabled', true)
            $('#responsable_pago_flete').prop('disabled', true)
            $('#telefono_responsable_recoger').prop('disabled', true)
            $('#observacion_envio').prop('disabled', true)

            $('#nombre_transporte_domicilio').prop('disabled', true)
            $('#direccion_domicilio').prop('disabled', true)
            $('#nombre_contacto_recoger').prop('disabled', true)
            $('#telefono_contacto_recoger').prop('disabled', true)
            $('#observacion_domicilio').prop('disabled', true)
            limpiarOficina()
            limpiarDomicilio()
    }

})

// Consulta Dni
function consultarDni() {
    var dni = $('#dni_responsable_recoger').val()
    if (dni.length == 8) {

        Swal.fire({
            title: 'Consultar',
            text: "¿Desea consultar Dni a Reniec?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: "#1ab394",
            confirmButtonText: 'Si, Confirmar',
            cancelButtonText: "No, Cancelar",
            showLoaderOnConfirm: true,
            preConfirm: (login) => {
                var url = '{{ route("getApidni", ":dni")}}';
                url = url.replace(':dni', dni);

                return fetch(url)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(response.statusText)
                        }
                        return response.json()
                    })
                    .catch(error => {
                        console.log(error)
                        $('#estado_responsable_recoger').val('SIN VERIFICAR')
                        Swal.showValidationMessage(
                            `Dni Inválido`
                        )
                    })
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            camposDni(result)
        
            consultaExitosa()
        })
    } else {
        toastr.error('El campo Dni debe de contar con 8 dígitos', 'Error');
    }
}

function camposDni(objeto) {

    var nombres = objeto.value.nombres;
    var apellidopa = objeto.value.apellidoPaterno;
    var apellidoma = objeto.value.apellidoMaterno;

    var nombre_completo = []

    if (nombres != "-" && nombres != null) {
        nombre_completo.push(nombres)
    }

    if (apellidopa != "-" && apellidopa != null) {
        nombre_completo.push(apellidopa)
    }

    if (apellidoma != "-" && apellidoma != null) {
        nombre_completo.push(apellidoma)
    }

    $('#nombre_responsable_recoger').val(nombre_completo.join(' '))
    $('#estado_responsable_recoger').val('ACTIVO')

}


 





</script>
@endpush