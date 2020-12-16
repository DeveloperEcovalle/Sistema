@extends('layout') @section('content')
@section('compras-active', 'active')
@section('proveedor-active', 'active')
<div class="row wrapper border-bottom white-bg page-heading">
    
    <div class="col-lg-12">
        <h2 style="text-transform:uppercase;"><b>MODIFICAR PROVEEDOR # {{$proveedor->id}}</b></h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{route('home')}}">Panel de Control</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{route('compras.proveedor.index')}}">Proveedores</a>
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

                 <form action="{{route('compras.proveedor.update',$proveedor->id)}}" method="POST" id="enviar_proveedor">
                    @csrf @method('PUT')
          
                    <div class="row">
                        <div class="col-sm-6 b-r"><h4 class=""><b>Proveedor</b></h4>
                                <div class="row">
                                    <div class="col-md-12">
                                        <p>Registrar datos del nuevo Proveedor:</p>
                                    </div>
                                </div>                                        

                                <div class="form-group row">

                                    <div class="col-md-6">
                                        <label class="required">Documento: </label>
                                        <div class="row mt-1" align="center">
                                            <div class="col">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio"
                                                            name="tipo_documento" id="tipo_documento_ruc"
                                                            value="RUC" @if(old('tipo_documento', $proveedor->tipo_documento) == "RUC") {{'checked'}}@endif>
                                                    <label class="form-check-label">Ruc</label>
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio"
                                                            name="tipo_documento" id="tipo_documento_dni"
                                                            value="DNI" @if(old('tipo_documento', $proveedor->tipo_documento) == "DNI") {{'checked'}}@endif>
                                                    <label class="form-check-label">Dni</label>
                                                </div>
                                            </div>
                                        </div>

                                    </div>


                                    <div class="col-md-6">
                                            <label class="required">Tipo: </label>      
                                            <select class="select2_form form-control {{ $errors->has('tipo_persona') ? ' is-invalid' : '' }}" style="text-transform: uppercase; width:100%" value="{{old('tipo_persona',$proveedor->tipo_persona)}}" name="tipo_persona" id="tipo_persona" required>
                                                <option></option>
                                                @foreach ($tipos as $tipo)   
                                                    <option value="{{$tipo->descripcion}}" @if(old('tipo_persona', $proveedor->tipo_persona) == $tipo->descripcion ) {{'selected'}} @endif >{{$tipo->descripcion}}</option>
                                                @endforeach
                                            </select>

                                            <input type="text" disabled id="tipo_persona_dni" class="form-control" name="tipo_persona_dni" value="PERSONA CON DNI">

                                            @if ($errors->has('tipo_persona'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('tipo_persona') }}</strong>
                                                </span>
                                            @endif

                                    </div>

                                    
                                </div>

                                <div class="form-group row" >

                                    <div class="col-md-6" id="ruc_requerido">
                                            <label class="required">Ruc: </label>                                                     
                                            <input type="text" id="ruc" class="form-control {{ $errors->has('ruc') ? ' is-invalid' : '' }}" name="ruc" maxlength="11" value="{{old('ruc', $proveedor->ruc)}}"   style="text-transform:uppercase;">

                                            @if ($errors->has('ruc'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('ruc') }}</strong>
                                                </span>
                                            @endif

                                    </div>

                                    <div class="col-md-6" id="dni_requerido" style="display:none;">
                                            <label class="required">Dni: </label>                                                     
                                            <input type="text" id="dni" class="form-control {{ $errors->has('dni') ? ' is-invalid' : '' }}" name="dni" value="{{ old('dni',$proveedor->dni)}}" maxlength="8"  style="text-transform:uppercase;">

                                            @if ($errors->has('dni'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('dni') }}</strong>
                                                </span>
                                            @endif

                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label class="">Estado: </label> 
                                        <input type="text" id="estado" class="form-control {{ $errors->has('estado') ? ' is-invalid' : '' }}" name="estado" value="{{ $proveedor->activo == 1 ? 'ACTIVO' : 'INACTIVO'}}" style="text-transform:uppercase;" disabled>
                                                    @if ($errors->has('estado'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('estado') }}</strong>
                                                        </span>
                                                    @endif
                                    </div>



                                    
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <label class="required">Descripción: </label> 
                                        
                                        <input type="text" class="form-control {{ $errors->has('descripcion') ? ' is-invalid' : '' }}" name="descripcion" value="{{ old('descripcion',$proveedor->descripcion)}}" id="descripcion" style="text-transform:uppercase;" required>

                                        @if ($errors->has('descripcion'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('descripcion') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>



                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <label class="required">Dirección:</label> 
                                        <textarea type="text" id="direccion" name="direccion" class="form-control {{ $errors->has('direccion') ? ' is-invalid' : '' }}" value="{{old('direccion',$proveedor->direccion)}}" style="text-transform:uppercase;" required >{{old('direccion',$proveedor->direccion)}}</textarea>
                                        @if ($errors->has('direccion'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('direccion') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    
                                </div>

                                <div class="form-group row">
                                        <div class="col-md-6">
                                            <label class="required">Zona: </label>      
                                            <select class="select2_form form-control {{ $errors->has('zona') ? ' is-invalid' : '' }}" style="text-transform: uppercase; width:100%" value="{{old('zona',$proveedor->zona)}}" name="zona" id="zona" required >
                                                <option></option>
                                                @foreach ($zonas as $zona)   
                                                    <option value="{{$zona->descripcion}}" @if(old('zona',$proveedor->zona) == $zona->descripcion ) {{'selected'}} @endif >{{$zona->descripcion}}</option>
                                                @endforeach
                                            </select>

                                            @if ($errors->has('zona'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('zona') }}</strong>
                                                </span>
                                            @endif
                                        </div>

                                        <div class="col-md-6">

                                            <label class="required">Correo:</label> 
                                            <input type="email" placeholder="" class="form-control {{ $errors->has('correo') ? ' is-invalid' : '' }}" name="correo" id="correo" style="text-transform:uppercase;" value="{{old('correo',$proveedor->correo)}}" required>
                                            @if ($errors->has('correo'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('correo') }}</strong>
                                                </span>
                                            @endif
                                        
                                        
                                        </div>



                                </div>

                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label>Teléfono:</label> 
                                        <input type="text" placeholder="" class="form-control {{ $errors->has('telefono') ? ' is-invalid' : '' }}" name="telefono" id="telefono" style="text-transform:uppercase;" value="{{old('telefono',$proveedor->telefono)}}">
                                        @if ($errors->has('telefono'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('telefono') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="col-md-6">
                                        <label>Celular:</label> 
                                        <input type="text" placeholder="" class="form-control {{ $errors->has('celular') ? ' is-invalid' : '' }}" name="celular" id="celular" style="text-transform:uppercase;" value="{{old('celular',$proveedor->celular)}}">
                                        @if ($errors->has('celular'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('celular') }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                </div>

                                <div class="form-group">

                                    <label>Web:</label> 
                                    <input type="text" placeholder="" class="form-control {{ $errors->has('web') ? ' is-invalid' : '' }}" name="web" id="web" style="text-transform:uppercase;" value="{{old('web', $proveedor->web)}}">
                                    @if ($errors->has('web'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('web') }}</strong>
                                        </span>
                                    @endif


                                </div> 


                        </div>

                        <div class="col-sm-6">

                                <h4><b>Datos Adicionales</b></h4>
                                <p>Registrar datos adicionales del proveedor:</p>
                                                    
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="tabs-container">
                                                <ul class="nav nav-tabs">
                                                    <li title="Datos de Calidad"><a class="nav-link active" data-toggle="tab" href="#tab-1"><i class="fa fa-check-square"></i> Calidad</a></li>
                                                    <li title="Datos de Contacto"><a class="nav-link" data-toggle="tab" href="#tab-2"> <i class="fa fa-user"></i>  Contacto</a></li>
                                                    <li title="Datos de Tranporte"><a class="nav-link" data-toggle="tab" href="#tab-3"> <i class="fa fa-bus"></i> Transporte</a></li>
                                                    <li title="Datos de Almacen"><a class="nav-link" data-toggle="tab" href="#tab-4"> <i class="fa fa-building"></i> Almacen</a></li>
                                                </ul>
                                                <div class="tab-content">
                                                    <div id="tab-1" class="tab-pane active">
                                                        <div class="panel-body">
                                                            <p><b>Registrar datos del calidad:</b></p>
                                                            <div class="form-group">

                                                                <label>Nombre Completo:</label> 
                                                                <input type="text" placeholder="" class="form-control {{ $errors->has('calidad') ? ' is-invalid' : '' }}" name="calidad" id="calidad" style="text-transform:uppercase;" value="{{old('calidad',$proveedor->calidad)}}">
                                                                @if ($errors->has('calidad'))
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $errors->first('calidad') }}</strong>
                                                                    </span>
                                                                @endif

                                                            </div> 

                                                            <div class="form-group row">
                                                                <div class="col-md-6">
                                                                    <label>Teléfono:</label> 
                                                                    <input type="text" placeholder="" class="form-control {{ $errors->has('telefono_calidad') ? ' is-invalid' : '' }}" name="telefono_calidad" id="telefono_calidad" style="text-transform:uppercase;" value="{{old('telefono_calidad',$proveedor->telefono_calidad)}}">
                                                                    @if ($errors->has('telefono_calidad'))
                                                                        <span class="invalid-feedback" role="alert">
                                                                            <strong>{{ $errors->first('telefono_calidad') }}</strong>
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label>Celular:</label> 
                                                                    <input type="text" placeholder="" class="form-control {{ $errors->has('celular_calidad') ? ' is-invalid' : '' }}" name="celular_calidad" id="celular_calidad" style="text-transform:uppercase;" value="{{old('celular_calidad',$proveedor->celular_calidad)}}">
                                                                    @if ($errors->has('celular_calidad'))
                                                                        <span class="invalid-feedback" role="alert">
                                                                            <strong>{{ $errors->first('celular_calidad') }}</strong>
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            </div>

                                                            <div class="form-group">

                                                                <label>Correo:</label> 
                                                                <input type="email" placeholder="" class="form-control {{ $errors->has('correo_calidad') ? ' is-invalid' : '' }}" name="correo_calidad" id="correo_calidad" style="text-transform:uppercase;" value="{{old('correo_calidad',$proveedor->correo_calidad)}}">
                                                                @if ($errors->has('correo_calidad'))
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $errors->first('correo_calidad') }}</strong>
                                                                    </span>
                                                                @endif

                                                            </div> 

                                                        </div>
                                                    </div>

                                                    <div id="tab-2" class="tab-pane">
                                                        <div class="panel-body">
                                                            <p><b>Registrar datos del contacto:</b></p>
                                                            <div class="form-group">

                                                                <label>Nombre Completo:</label> 
                                                                <input type="text" placeholder="" class="form-control {{ $errors->has('web') ? ' is-invalid' : '' }}" name="contacto" id="contacto" style="text-transform:uppercase;" value="{{old('contacto',$proveedor->contacto)}}">
                                                                @if ($errors->has('contacto'))
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $errors->first('contacto') }}</strong>
                                                                    </span>
                                                                @endif

                                                            </div> 

                                                            <div class="form-group row">
                                                                <div class="col-md-6">
                                                                    <label>Teléfono:</label> 
                                                                    <input type="text" placeholder="" class="form-control {{ $errors->has('telefono_contacto') ? ' is-invalid' : '' }}" name="telefono_contacto" id="telefono_contacto" style="text-transform:uppercase;" value="{{old('telefono_contacto', $proveedor->telefono_contacto)}}">
                                                                    @if ($errors->has('telefono_contacto'))
                                                                        <span class="invalid-feedback" role="alert">
                                                                            <strong>{{ $errors->first('telefono_contacto') }}</strong>
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label>Celular:</label> 
                                                                    <input type="text" placeholder="" class="form-control {{ $errors->has('celular_contacto') ? ' is-invalid' : '' }}" name="celular_contacto" id="celular_contacto" style="text-transform:uppercase;" value="{{old('celular_contacto',$proveedor->celular_contacto)}}">
                                                                    @if ($errors->has('celular_contacto'))
                                                                        <span class="invalid-feedback" role="alert">
                                                                            <strong>{{ $errors->first('celular_contacto') }}</strong>
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>

                                                    <div id="tab-3" class="tab-pane">
                                                        <div class="panel-body">

                                                        <p><b>Registrar datos del transporte:</b></p>
                                                            <div class="form-group">

                                                                <label>Nombre Completo:</label> 
                                                                <input type="text" placeholder="" class="form-control {{ $errors->has('transporte') ? ' is-invalid' : '' }}" name="transporte" id="transporte" style="text-transform:uppercase;" value="{{old('transporte',$proveedor->transporte)}}">
                                                                @if ($errors->has('transporte'))
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $errors->first('transporte') }}</strong>
                                                                    </span>
                                                                @endif


                                                            </div> 

                                                            <div class="form-group">
                                                                    <label>Dirección:</label> 
                                                                    <textarea type="text" placeholder="" class="form-control {{ $errors->has('direccion_transporte') ? ' is-invalid' : '' }}" name="direccion_transporte" id="direccion_transporte" style="text-transform:uppercase;" value="{{old('direccion_transporte',$proveedor->direccion_transporte)}}">{{old('direccion_transporte',$proveedor->direccion_transporte)}}</textarea>
                                                                    @if ($errors->has('direccion_transporte'))
                                                                        <span class="invalid-feedback" role="alert">
                                                                            <strong>{{ $errors->first('direccion_transporte') }}</strong>
                                                                        </span>
                                                                    @endif
                                                            

                                                            </div>

                                                        </div>
                                                    </div>
                                                    <div id="tab-4" class="tab-pane">
                                                        <div class="panel-body">

                                                            <p><b>Registrar datos del almacen:</b></p>

                                                            <div class="form-group">
                                                                    <label>Dirección:</label> 
                                                                    <textarea type="text" placeholder="" class="form-control {{ $errors->has('direccion_almacen') ? ' is-invalid' : '' }}" name="direccion_almacen" id="direccion_almacen" style="text-transform:uppercase;" value="{{old('direccion_almacen',$proveedor->direccion_almacen)}}">{{old('direccion_almacen',$proveedor->direccion_almacen)}}</textarea>
                                                                    @if ($errors->has('direccion_almacen'))
                                                                        <span class="invalid-feedback" role="alert">
                                                                            <strong>{{ $errors->first('direccion_almacen') }}</strong>
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

                

                  
                
                
                <div class="hr-line-dashed"></div>
                <div class="form-group row">
                    
                    <div class="col-md-6 text-left" style="color:#fcbc6c">
                        <i class="fa fa-exclamation-circle"></i> <small>Los campos marcados con asterisco (<label class="required"></label>) son obligatorios.</small>
                    </div>

                     <div class="col-md-6 text-right">
                        <a  href="{{route('compras.proveedor.index')}}"  id="btn_cancelar" class="btn btn-w-m btn-default">
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

<style>
    .logo {
        width: 200px;
        height: 200px;
        border-radius: 10%;
    }

    /*Select 2*/
    .select2-container--default .select2-selection--single {
        background-color: #fff;
        border: 1px solid #e5e6e7;
        border-radius: 0px;
    
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: inherit;
        font-size: 13.5px;
        line-height: 1.6;
        text-transform: uppercase;
    }
    .select2-container .select2-selection--single {
        background-color: #FFFFFF;
        background-image: none;
        border: 1px solid #e5e6e7;
        border-radius: 1px;
        color: inherit;
        display: block;
        padding: 6px 10px;
        transition: border-color 0.15s ease-in-out 0s, box-shadow 0.15s ease-in-out 0s;
        width: 100%;
        height: 2.05rem;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 28px;
        width: 22px;
    }

    .select2-container--default .select2-results__option--highlighted.select2-results__option--selectable {
        background-color: #042b63;
    }
    select.form-control:not([size]):not([multiple]) {
        height: calc(2.25rem + 2px);
    }
    .select2-container--default .select2-results>.select2-results__options {
        text-transform: uppercase;
    }

    
    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: #1ab394;
        color: white;
    }
</style>
@endpush 

@push('scripts')

<!-- Select2 -->
<script src="{{asset('Inspinia/js/plugins/select2/select2.full.min.js')}}"></script>

<script>

    //Select2
    $(".select2_form").select2({
        placeholder: "SELECCIONAR",
        allowClear: true,
        height: '200px',
        width: '100%',
    });


    // Solo campos numericos
    $('#ruc').on('input', function () { 
        this.value = this.value.replace(/[^0-9]/g,'');
    });

    $('#dni').on('input', function () { 
        this.value = this.value.replace(/[^0-9]/g,'');
    });

    $('#telefono').on('input', function () { 
        this.value = this.value.replace(/[^0-9]/g,'');
    });

    $('#celular').on('input', function () { 
        this.value = this.value.replace(/[^0-9]/g,'');
    });

    $('#telefono_contacto').on('input', function () { 
        this.value = this.value.replace(/[^0-9]/g,'');
    });

    $('#celular_contacto').on('input', function () { 
        this.value = this.value.replace(/[^0-9]/g,'');
    });

    $('#telefono_calidad').on('input', function () { 
        this.value = this.value.replace(/[^0-9]/g,'');
    });

    $('#celular_calidad').on('input', function () { 
        this.value = this.value.replace(/[^0-9]/g,'');
    });


    //Old tipo de Documento
    @if (!old('tipo_documento',$proveedor->tipo_documento)) 
        $('#tipo_documento_ruc').prop("checked",true)
        $('#tipo_persona_dni').hide();    
    @endif

    @if (old('tipo_documento',$proveedor->tipo_documento) == "RUC")
        //Ocultar Tipo de Persona
        $('#ruc_requerido').show();
        $("#tipo_persona").select2().next().show();
        $("#tipo_persona").select2({
            placeholder: "SELECCIONAR",
            allowClear: true,
            height: '200px',
            width: '100%',
        });
        //Mostrar Campos Dni
        $('#dni_requerido').hide();
        $('#tipo_persona_dni').hide();
        $("#tipo_persona").prop('required', true);

    @endif

    @if (old('tipo_documento',$proveedor->tipo_documento) == "DNI")

        //Ocultar Tipo de Persona
        $('#ruc_requerido').hide();
        $("#tipo_persona").select2().next().hide();
        //Mostrar Campos Dni
        $('#dni_requerido').show();
        $('#tipo_persona_dni').show();
        $("#tipo_persona").prop('required', false);

    @endif
    
    
    $('input:radio[name="tipo_documento"]').change(function () {
        // Cambiar el estado
        $('#estado').val('INACTIVO')    
        /////////////////////////////
        if ($(this).val() == 'RUC') {
            //Limpiar Inputs
            $('#ruc').val('');
            $('#dni').val('');
            $('#descripcion').val('');
            $('#direccion').val('');
            $("#tipo_persona").val('');
        
            //////////////////////////

            //Ocultar Tipo de Persona
            $('#ruc_requerido').show();
            $("#tipo_persona").select2().next().show();
            $("#tipo_persona").select2({
                placeholder: "SELECCIONAR",
                allowClear: true,
                height: '200px',
                width: '100%',
            });
            //Mostrar Campos Dni
            $('#dni_requerido').hide();
            $('#tipo_persona_dni').hide();
            //Campos requeridos
            $("#dni").prop('required', false);
            $("#ruc").prop('required', true);
            $("#tipo_persona").prop('required', true);
        } else {
            //Limpiar Inputs
            $('#ruc').val('');
            $('#dni').val('');
            $('#descripcion').val('');
            $('#direccion').val('');
            //////////////////////////
            //Ocultar Tipo de Persona
            $('#ruc_requerido').hide();
            $("#tipo_persona").select2().next().hide();
            //Mostrar Campos Dni
            $('#dni_requerido').show();
            $('#tipo_persona_dni').show();
            //Campos requeridos
            $("#dni").prop('required', true);
            $("#ruc").prop('required', false);
            $("#tipo_persona").prop('required', false);

        }
    });

    $("#ruc").keyup(function(){ 
        $('#estado').val('INACTIVO');
    }) 

    $("#dni").keyup(function(){ 
        if ($('#estado').val('ACTIVO')) {
            $('#estado').val('INACTIVO');
        }
    }) 


    $('#enviar_proveedor').submit(function(e){
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

                    if ($('#estado').val() == "ACTIVO") {
                        $("#estado").prop('disabled', false)
                        $("#tipo_persona").prop('disabled', false);
                        $("#tipo_persona_dni").prop('disabled', false);
                        this.submit(); 
                    }else{
                        toastr.error('Ingrese un proveedor activo','Error');
                    }
                }else if (
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
    $( "#ruc" ).keypress(function() {
        if (event.which == 13) {
            event.preventDefault();
            var ruc = $("#ruc").val()
            evaluarRuc(ruc);
        }
    })
    function evaluarRuc(ruc) {
        if (ruc.length == 11) {
            
            Swal.fire({
                title: 'Consultar',
                text: "¿Desea consultar Ruc a Sunat?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: "#1ab394",
                confirmButtonText: 'Si, Confirmar',
                cancelButtonText: "No, Cancelar",
                showLoaderOnConfirm: true,
                preConfirm: (login) => {
                    var url= '{{ route("getApiruc", ":ruc")}}';
                    url = url.replace(':ruc',ruc);
                    return fetch(url)
                    .then(response => {
                        if (!response.ok) {
                            
                        throw new Error(response.statusText)
                        }
                        
                        return response.json()
                    })
                    .catch(error => {
                        console.log(error)
                        Swal.showValidationMessage(
                            `Ruc Inválido`
                        )
                    })
                },
                allowOutsideClick: () => !Swal.isLoading()
                }).then((result) => {
                    console.log(result)
                    $('#ruc').removeClass('is-invalid')
                    camposRuc(result)
                    consultaExitosa()  
                })
        }else{
            toastr.error('El campo Ruc debe de contar con 11 dígitos','Error');
        }
        
        
    }
    function camposRuc(objeto){
        var razonsocial = objeto.value.razonSocial;
        var direccion = objeto.value.direccion;
        var departamento = objeto.value.departamento;
        var provincia = objeto.value.provincia;
        var distrito = objeto.value.distrito;
        var estado = objeto.value.estado;
        
        if (razonsocial!='-' && razonsocial!="NULL" ) {
            $('#descripcion').val(razonsocial)    
        }

        if (direccion!='-' && direccion!="NULL" ) {
            $('#direccion').val(direccion+" - "+departamento+" - "+provincia+" - "+distrito)    
        }
        if (estado=="ACTIVO" ) {
            $('#estado').val(estado)    
        }else{
            toastr.error('Proveedor no se encuentra "Activo"','Error');
        }
    }
    
    // Consulta Dni
    $( "#dni" ).keypress(function() {
        if (event.which == 13) {
            event.preventDefault();
            var dni = $("#dni").val()
            evaluarDni(dni);
        }
    })
    // Consulta Dni
    function evaluarDni(dni) {
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
                    var url= '{{ route("getApidni", ":dni")}}';
                    url = url.replace(':dni',dni);
                    
                    return fetch(url)
                    .then(response => {
                        if (!response.ok) {
                        throw new Error(response.statusText)
                        }
                        return response.json()
                    })
                    .catch(error => {
                        console.log(error)
                        $('#estado').val('INACTIVO')
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
        }else{
            toastr.error('El campo Dni debe de contar con 8 dígitos','Error');
        }
        
        
    }
    function camposDni(objeto){

        var nombres = objeto.value.nombres;
        var apellidopa = objeto.value.apellidoPaterno;
        var apellidoma= objeto.value.apellidoMaterno;
        
        var nombre_completo = []
    
        if(nombres!="-" && nombres!=null ) {
            nombre_completo.push(nombres)        
        }
    
        if(apellidopa!="-" && apellidopa!=null ) {
            nombre_completo.push(apellidopa)        
        }
    
        if(apellidoma!="-" && apellidoma!=null ) {
            nombre_completo.push(apellidoma)        
        }

        $('#estado').val('ACTIVO')    
        $('#descripcion').val(nombre_completo.join(' '))

    }





</script>
@endpush