@extends('layout')

@section('content')

@section('ventas-active', 'active')
@section('clientes-active', 'active')

<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10 col-md-10">
       <h2  style="text-transform:uppercase"><b>REGISTRAR NUEVO CLIENTE</b></h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('home') }}">Panel de Control</a>
            </li>
            <li class="breadcrumb-item active">
                <a href="{{ route('ventas.cliente.index') }}">Clientes</a>
            </li>
            <li class="breadcrumb-item active">
                <strong>Registrar</strong>
            </li>
        </ol>
    </div>
</div>


<div class="wrapper wrapper-content animated fadeIn">

    <form class="wizard-big" action="{{ route('ventas.cliente.store') }}" method="POST" id="form_registrar_cliente">
        @csrf
        <h1>Datos Del Cliente</h1>
        <fieldset>
            <div class="row">
                <div class="form-group col-lg-4 col-xs-12">
                    <label class="required">Tipo de documento</label>
                    <select id="tipo_documento" name="tipo_documento" class="select2_form form-control {{ $errors->has('tipo_documento') ? ' is-invalid' : '' }}">
                        <option></option>
                        @foreach(tipos_documento() as $tipo_documento)
                            <option value="{{ $tipo_documento->simbolo }}" {{ (old('tipo_documento') == $tipo_documento->simbolo ? "selected" : "") }} >{{ $tipo_documento->simbolo }}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('tipo_documento'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('tipo_documento') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group col-lg-3 col-xs-12">
                    <label class="required">Nro. Documento</label>
                    <input type="text" id="documento" name="documento" class="form-control {{ $errors->has('documento') ? ' is-invalid' : '' }}" value="{{old('documento')}}" maxlength="8" onkeypress="return isNumber(event)" required>
                    @if ($errors->has('documento'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('documento') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group col-lg-3 col-xs-12">
                    <label class="required">Tipo Cliente</label>
                    <select id="tipo_cliente" name="tipo_cliente" class="select2_form form-control {{ $errors->has('tipo_cliente') ? ' is-invalid' : '' }}" style="width: 100%">
                        <option></option>
                        @foreach(tipo_clientes() as $tipo_cliente)
                            <option value="{{ $tipo_cliente->id }}" {{ (old('tipo_cliente') == $tipo_cliente->id ? "selected" : "") }} >{{ $tipo_cliente->descripcion }}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('tipo_cliente'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('tipo_cliente') }}</strong>
                        </span>
                    @endif
                </div>
                <input type="hidden" id="codigo_verificacion" name="codigo_verificacion">
                <div class="form-group col-lg-2 col-xs-12">
                    <label class="required">Estado</label>
                    <input type="text" id="activo" name="activo" class="form-control {{ $errors->has('activo') ? ' is-invalid' : '' }}" value="{{old('activo', 'INACTIVO')}}" maxlength="8" readonly>
                    @if ($errors->has('activo'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('activo') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="form-group col-lg-6 col-xs-12">
                    <label class="required" id="lblNombre">Nombre</label>
                    <input type="text" id="nombre" name="nombre" class="form-control {{ $errors->has('nombre') ? ' is-invalid' : '' }}" value="{{old('nombre')}}" maxlength="191" onkeyup="return mayus(this)" required>
                    @if ($errors->has('nombre'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('nombre') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group col-lg-6 col-xs-12">
                    <label class="required">Dirección completa</label>
                    <input type="text" id="direccion" name="direccion" class="form-control {{ $errors->has('direccion') ? ' is-invalid' : '' }}" value="{{old('direccion')}}" maxlength="191" onkeyup="return mayus(this)" required>
                    @if ($errors->has('direccion'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('direccion') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="form-group col-lg-4 col-xs-12">
                    <label class="required">Departamento</label>
                    <select id="departamento" name="departamento" class="select2_form form-control {{ $errors->has('departamento') ? ' is-invalid' : '' }}" style="width: 100%">
                        <option></option>
                        @foreach(departamentos() as $departamento)
                            <option value="{{ $departamento->id }}" {{ (old('departamento') == $departamento->id ? "selected" : "") }} >{{ $departamento->nombre }}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('departamento'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('departamento') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group col-lg-4 col-xs-12">
                    <label class="required">Provincia</label>
                    <select id="provincia" name="provincia" class="select2_form form-control {{ $errors->has('provincia') ? ' is-invalid' : '' }}" style="width: 100%">
                        <option></option>
                    </select>
                    @if ($errors->has('provincia'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('provincia') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group col-lg-4 col-xs-12">
                    <label class="required">Distrito</label>
                    <select id="distrito" name="distrito" class="select2_form form-control {{ $errors->has('distrito') ? ' is-invalid' : '' }}" style="width: 100%">
                        <option></option>
                    </select>
                    @if ($errors->has('distrito'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('provincia') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="form-group col-lg-4 col-xs-12">
                    <label class="required">Teléfono móvil</label>
                    <input type="text" id="telefono_movil" name="telefono_movil" class="form-control {{ $errors->has('telefono_movil') ? ' is-invalid' : '' }}" value="{{old('telefono_movil')}}" onkeypress="return isNumber(event)" maxlength="9" required>
                    @if ($errors->has('telefono_movil'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('telefono_movil') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group col-lg-4 col-xs-12">
                    <label>Teléfono fijo</label>
                    <input type="text" id="telefono_fijo" name="telefono_fijo" class="form-control {{ $errors->has('telefono_fijo') ? ' is-invalid' : '' }}" value="{{old('telefono_fijo')}}" onkeypress="return isNumber(event)" maxlength="10">
                    @if ($errors->has('telefono_fijo'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('telefono_fijo') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group col-lg-4 col-xs-12">
                    <label>Correo electrónico</label>
                    <input type="email" id="correo_electronico" name="correo_electronico" class="form-control {{ $errors->has('correo_electronico') ? ' is-invalid' : '' }}" value="{{old('correo_electronico')}}" maxlength="100" onkeyup="return mayus(this)">
                    @if ($errors->has('correo_electronico'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('correo_electronico') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="m-t-md col-lg-8">
                    <i class="fa fa-exclamation-circle leyenda-required"></i> <small class="leyenda-required">Los campos marcados con asterisco (*) son obligatorios.</small>
                </div>
            </div>
        </fieldset>

        <h1>Datos De Negocio</h1>
        <fieldset  style="position: relative;">
        <h3>DATOS DE NEGOCIO</h3>
        <div class="row">
            <div class="form-group col-lg-5 col-xs-12">
                <label>Direccion de Negocio</label>
                <input type="text" id="direccion_negocio" name="direccion_negocio" class="form-control {{ $errors->has('direccion_negocio') ? ' is-invalid' : '' }}" value="{{old('direccion_negocio')}}" maxlength="191" onkeyup="return mayus(this)" required>
                    @if ($errors->has('direccion_negocio'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('direccion_negocio') }}</strong>
                        </span>
                    @endif
            </div>
            <div class="form-group col-lg-3 col-xs-12" id="fecha_aniversario">
                <label class="required">Fecha de Aniversario</label>
                <div class="input-group date">
                    <span class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                    </span>
                    <input type="text" id="fecha_aniversario" name="fecha_aniversario" class="form-control {{ $errors->has('fecha_aniversario') ? ' is-invalid' : '' }}" value="{{old('fecha_aniversario')}}" readonly >
                </div>
            </div>
            <div class="form-group col-lg-4 col-xs-12">
                <label>observaciones</label>
                <textarea type="text" id="observaciones" name="observaciones" class="form-control {{ $errors->has('observaciones') ? ' is-invalid' : '' }}" value="{{old('observaciones')}}" rows="1" onkeyup="return mayus(this)"></textarea>
            </div>
        </div>

        <h3>CONTACTO COMERCIAL - Tienda 1</h3>
        <div class="row">
            <div class="form-group col-lg-4 col-xs-12">
                <label>Nombres y apellidos</label>
                <input type="text" id="direccion_negocio" name="direccion_negocio" class="form-control {{ $errors->has('direccion_negocio') ? ' is-invalid' : '' }}" value="{{old('direccion_negocio')}}" maxlength="191" onkeyup="return mayus(this)" required>
                    @if ($errors->has('direccion_negocio'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('direccion_negocio') }}</strong>
                        </span>
                    @endif
            </div>
            <div class="form-group col-lg-3 col-xs-12" id="fecha_nacimiento1">
                <label class="required">Fecha de Nacimiento</label>
                <div class="input-group date">
                    <span class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                    </span>
                    <input type="text" id="fecha_nacimiento1" name="fecha_nacimiento1" class="form-control {{ $errors->has('fecha_nacimiento1') ? ' is-invalid' : '' }}" value="{{old('fecha_nacimiento1')}}" autocomplete="off" readonly required >
                </div>
            </div>
            <div class="form-group col-lg-3 col-xs-12">
                <label class="required">Correo electrónico</label>
                <input type="email" id="correo_electronico" name="correo_electronico" class="form-control {{ $errors->has('correo_electronico') ? ' is-invalid' : '' }}" value="{{old('correo_electronico')}}" maxlength="100" onkeyup="return mayus(this)" required>
            </div>
            <div class="form-group col-lg-2 col-xs-12">
                <label class="required">Celular</label>
                <input type="text" id="celular" name="celular" class="form-control {{ $errors->has('celular') ? ' is-invalid' : '' }}" value="{{old('celular')}}" onkeypress="return isNumber(event)" maxlength="9" required>
            </div>
        </div>

        <h3>CONTACTO COMERCIAL - Tienda 2</h3>
        <div class="row">
            <div class="form-group col-lg-4 col-xs-12">
                <label>Nombres y apellidos</label>
                <input type="text" id="direccion_negocio" name="direccion_negocio" class="form-control {{ $errors->has('direccion_negocio') ? ' is-invalid' : '' }}" value="{{old('direccion_negocio')}}" maxlength="191" onkeyup="return mayus(this)" required>
                    @if ($errors->has('direccion_negocio'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('direccion_negocio') }}</strong>
                        </span>
                    @endif
            </div>
            <div class="form-group col-lg-3 col-xs-12" id="fecha_nacimiento1">
                <label class="required">Fecha de Nacimiento</label>
                <div class="input-group date">
                    <span class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                    </span>
                    <input type="text" id="fecha_nacimiento1" name="fecha_nacimiento1" class="form-control {{ $errors->has('fecha_nacimiento1') ? ' is-invalid' : '' }}" value="{{old('fecha_nacimiento1')}}" autocomplete="off" readonly required >
                </div>
            </div>
            <div class="form-group col-lg-3 col-xs-12">
                <label class="required">Correo electrónico</label>
                <input type="email" id="correo_electronico" name="correo_electronico" class="form-control {{ $errors->has('correo_electronico') ? ' is-invalid' : '' }}" value="{{old('correo_electronico')}}" maxlength="100" onkeyup="return mayus(this)" required>
            </div>
            <div class="form-group col-lg-2 col-xs-12">
                <label class="required">Celular</label>
                <input type="text" id="celular" name="celular" class="form-control {{ $errors->has('celular') ? ' is-invalid' : '' }}" value="{{old('celular')}}" onkeypress="return isNumber(event)" maxlength="9" required>
            </div>
        </div>

        <h3>CONTACTO COMERCIAL - Tienda 3</h3>
        <div class="row">
            <div class="form-group col-lg-4 col-xs-12">
                <label>Nombres y apellidos</label>
                <input type="text" id="direccion_negocio" name="direccion_negocio" class="form-control {{ $errors->has('direccion_negocio') ? ' is-invalid' : '' }}" value="{{old('direccion_negocio')}}" maxlength="191" onkeyup="return mayus(this)" required>
                    @if ($errors->has('direccion_negocio'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('direccion_negocio') }}</strong>
                        </span>
                    @endif
            </div>
            <div class="form-group col-lg-3 col-xs-12" id="fecha_nacimiento1">
                <label class="required">Fecha de Nacimiento</label>
                <div class="input-group date">
                    <span class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                    </span>
                    <input type="text" id="fecha_nacimiento1" name="fecha_nacimiento1" class="form-control {{ $errors->has('fecha_nacimiento1') ? ' is-invalid' : '' }}" value="{{old('fecha_nacimiento1')}}" autocomplete="off" readonly required >
                </div>
            </div>
            <div class="form-group col-lg-3 col-xs-12">
                <label class="required">Correo electrónico</label>
                <input type="email" id="correo_electronico" name="correo_electronico" class="form-control {{ $errors->has('correo_electronico') ? ' is-invalid' : '' }}" value="{{old('correo_electronico')}}" maxlength="100" onkeyup="return mayus(this)" required>
            </div>
            <div class="form-group col-lg-2 col-xs-12">
                <label class="required">Celular</label>
                <input type="text" id="celular" name="celular" class="form-control {{ $errors->has('celular') ? ' is-invalid' : '' }}" value="{{old('celular')}}" onkeypress="return isNumber(event)" maxlength="9" required>
            </div>
        </div>

        <div class="row">
            <div class="m-t-md col-lg-8">
                <i class="fa fa-exclamation-circle leyenda-required"></i> <small class="leyenda-required">Los campos marcados con asterisco (*) son obligatorios.</small>
            </div>
        </div>
        </fieldset>

        <h1>Datos Del Envio</h1>
        <fieldset  style="position: relative;">
        <div class="row">
            <div class="form-group col-lg-5 col-xs-12">
                <label>Condicion de Reparto</label>
                <select id="moneda_credito" name="moneda_credito" class="select2_form form-control {{ $errors->has('moneda_credito') ? ' is-invalid' : '' }}">
                    <option></option>
                    @foreach(tipos_moneda() as $moneda)
                        <option value="{{ $moneda->simbolo }}" {{ (old('moneda_credito') == $moneda->simbolo ? "selected" : "") }}>{{ $moneda->descripcion }}</option>
                    @endforeach
                </select>
                @if ($errors->has('moneda_credito'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('moneda_credito') }}</strong>
                    </span>
                @endif
            </div>
            <div class="form-group col-lg-5 col-xs-12">
                <label>Direccion de Entrega del Bien</label>
                <input type="text" id="direccion_negocio" name="direccion_negocio" class="form-control {{ $errors->has('direccion_negocio') ? ' is-invalid' : '' }}" value="{{old('direccion_negocio')}}" maxlength="191" onkeyup="return mayus(this)" required>
                    @if ($errors->has('direccion_negocio'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('direccion_negocio') }}</strong>
                        </span>
                    @endif
            </div>
            <div class="form-group col-lg-5 col-xs-12">
                <label>Nombre de la Empresa de Transporte para el Envio:</label>
                <input type="text" id="direccion_negocio" name="direccion_negocio" class="form-control {{ $errors->has('direccion_negocio') ? ' is-invalid' : '' }}" value="{{old('direccion_negocio')}}" maxlength="191" onkeyup="return mayus(this)" required>
                    @if ($errors->has('direccion_negocio'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('direccion_negocio') }}</strong>
                        </span>
                    @endif
            </div>
            <div class="form-group col-lg-5 col-xs-12">
                <label>Responsable del Pago del Flete de Envio:</label>
                <input type="text" id="direccion_negocio" name="direccion_negocio" class="form-control {{ $errors->has('direccion_negocio') ? ' is-invalid' : '' }}" value="{{old('direccion_negocio')}}" maxlength="191" onkeyup="return mayus(this)" required>
                    @if ($errors->has('direccion_negocio'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('direccion_negocio') }}</strong>
                        </span>
                    @endif
            </div>
            <div class="form-group col-lg-5 col-xs-12">
                <label>Nombre de la Persona quien Recoge Paquete / Si es Envio Oficina:</label>
                <input type="text" id="direccion_negocio" name="direccion_negocio" class="form-control {{ $errors->has('direccion_negocio') ? ' is-invalid' : '' }}" value="{{old('direccion_negocio')}}" maxlength="191" onkeyup="return mayus(this)" required>
                    @if ($errors->has('direccion_negocio'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('direccion_negocio') }}</strong>
                        </span>
                    @endif
            </div>
            <div class="form-group col-lg-5 col-xs-12">
                <label>Nro DNI de la Persona Quien Recoge:</label>
                <input type="text" id="direccion_negocio" name="direccion_negocio" class="form-control {{ $errors->has('direccion_negocio') ? ' is-invalid' : '' }}" value="{{old('direccion_negocio')}}" maxlength="191" onkeyup="return mayus(this)" required>
                    @if ($errors->has('direccion_negocio'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('direccion_negocio') }}</strong>
                        </span>
                    @endif
            </div>
            <div class="form-group col-lg-5 col-xs-12">
                <label>TELEFONO</label>
                <input type="text" id="direccion_negocio" name="direccion_negocio" class="form-control {{ $errors->has('direccion_negocio') ? ' is-invalid' : '' }}" value="{{old('direccion_negocio')}}" maxlength="191" onkeyup="return mayus(this)" required>
                    @if ($errors->has('direccion_negocio'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('direccion_negocio') }}</strong>
                        </span>
                    @endif
            </div>
            <div class="form-group col-lg-5 col-xs-12">
                <label>OBSERVACION</label>
                <input type="text" id="direccion_negocio" name="direccion_negocio" class="form-control {{ $errors->has('direccion_negocio') ? ' is-invalid' : '' }}" value="{{old('direccion_negocio')}}" maxlength="191" onkeyup="return mayus(this)" required>
                    @if ($errors->has('direccion_negocio'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('direccion_negocio') }}</strong>
                        </span>
                    @endif
            </div>
        </div>
        <div class="row">
            <div class="m-t-md col-lg-8">
                <i class="fa fa-exclamation-circle leyenda-required"></i> <small class="leyenda-required">Los campos marcados con asterisco (*) son obligatorios.</small>
            </div>
        </div>
        </fieldset>
    </form>

</div>

@stop

@push('styles')
    <link href="{{ asset('Inspinia/css/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css') }}" rel="stylesheet">
    <link href="{{ asset('Inspinia/css/plugins/datapicker/datepicker3.css') }}" rel="stylesheet">
    <link href="{{ asset('Inspinia/css/plugins/daterangepicker/daterangepicker-bs3.css') }}" rel="stylesheet">
    <link href="{{ asset('Inspinia/css/plugins/select2/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('Inspinia/css/plugins/steps/jquery.steps.css') }}" rel="stylesheet">
    <style>
        .logo {
            width: 190px;
            height: 190px;
            border-radius: 10%;
            position: absolute;
        }
    </style>
@endpush

@push('scripts')

    <!-- iCheck -->
    <script src="{{ asset('Inspinia/js/plugins/iCheck/icheck.min.js') }}"></script>
    <!-- Data picker -->
    <script src="{{ asset('Inspinia/js/plugins/datapicker/bootstrap-datepicker.js') }}"></script>
    <!-- Select2 -->
    <script src="{{ asset('Inspinia/js/plugins/select2/select2.full.min.js') }}"></script>
    <!-- Steps -->
    <script src="{{ asset('Inspinia/js/plugins/steps/jquery.steps.min.js') }}"></script>

    <script>

        var departamento_api = '';
        var provincia_api = '';
        var distrito_api = '';

        $(document).ready(function() {

            $('.i-checks').iCheck({
                checkboxClass: 'icheckbox_square-green',
                radioClass: 'iradio_square-green',
            });

            $(".select2_form").select2({
                placeholder: "SELECCIONAR",
                allowClear: true,
                height: '200px',
                width: '100%',
            });

            $("#tipo_documento").on("change", cambiarTipoDocumento);

            $("#documento").on('change', consultarDocumento);

            $("#departamento").on("change", cargarProvincias);

            $('#provincia').on("change", cargarDistritos);

            $("input[type='email']").on('change', function() {
                if (!emailIsValid($(this).val())) {
                    toastr.error('El formato del email es incorrecto','Error');
                    $(this).focus();
                }
            });

            $('#fecha_aniversario .input-group.date').datepicker({
                todayBtn: "linked",
                keyboardNavigation: false,
                forceParse: false,
                autoclose: true,
                language: 'es',
                format: "dd/mm/yyyy"
            });

            $('#fecha_nacimiento1 .input-group.date').datepicker({
                todayBtn: "linked",
                keyboardNavigation: false,
                forceParse: false,
                autoclose: true,
                language: 'es',
                format: "dd/mm/yyyy"
            });

        });

        function setLongitudDocumento() {
            var tipo_documento = $('#tipo_documento').val();
            if (tipo_documento !== undefined && tipo_documento !== null && tipo_documento !== "" && tipo_documento.length > 0) {
                clearDatosPersona(true);
                switch (tipo_documento) {
                    case 'DNI':
                        $("#documento").attr('maxlength', 8);
                        $("#activo").val("INACTIVO");
                        break;

                    case 'RUC':
                        $("#documento").attr('maxlength', 11);
                        $("#activo").val("INACTIVO");
                        break;

                    case 'CARNET EXT.':
                        $("#documento").attr('maxlength', 20);
                        $("#activo").val("ACTIVO");
                        break;

                    case 'PASAPORTE':
                        $("#documento").attr('maxlength', 20);
                        $("#activo").val("ACTIVO");
                        break;

                    case 'P. NAC.':
                        $("#documento").attr('maxlength', 25);
                        $("#activo").val("ACTIVO");
                        break;
                }
            }
        }

        function consultarDocumento() {
            var tipo_documento = $('#tipo_documento').val();
            var documento = $('#documento').val();

            // Consultamos nuestra BBDD
            $.ajax({
                dataType : 'json',
                type : 'post',
                url : '{{ route('ventas.cliente.getDocumento') }}',
                data : {
                    '_token' : $('input[name=_token]').val(),
                    'tipo_documento' : tipo_documento,
                    'documento' : documento,
                    'id': null
                }
            }).done(function (result){
                if (result.existe) {
                    toastr.error('El '+ tipo_documento +' ingresado ya se encuentra registrado para un cliente','Error');
                    clearDatosPersona(true);
                } else {
                    if (tipo_documento === "DNI") {
                        if (documento.length === 8) {
                            consultarAPI(tipo_documento, documento);
                        } else {
                            toastr.error('El DNI debe de contar con 8 dígitos','Error');
                            clearDatosPersona(false);
                        }
                    } else if (tipo_documento === "RUC") {
                        if (documento.length === 11) {
                            consultarAPI(tipo_documento, documento);
                        } else {
                            toastr.error('El RUC debe de contar con 11 dígitos','Error');
                            clearDatosPersona(false);
                        }
                    }
                }
            });
        }

        function consultarAPI(tipo_documento, documento) {

            if (tipo_documento === 'DNI' || tipo_documento === 'RUC') {
                var url = (tipo_documento === 'DNI') ? '{{ route("getApidni", ":documento")}}' : '{{ route("getApiruc", ":documento")}}';
                url = url.replace(':documento',documento);
                var textAlert = (tipo_documento === 'DNI') ? "¿Desea consultar DNI a RENIEC?" : "¿Desea consultar RUC a SUNAT?";
                Swal.fire({
                    title: 'Consultar',
                    text: textAlert,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: "#1ab394",
                    confirmButtonText: 'Si, Confirmar',
                    cancelButtonText: "No, Cancelar",
                    showLoaderOnConfirm: true,
                    preConfirm: (login) => {
                        return fetch(url)
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error(response.statusText)
                                }
                                return response.json()
                            })
                            .catch(error => {
                                Swal.showValidationMessage(
                                    `El documento ingresado es incorrecto`
                                );
                                clearDatosPersona(true);
                            })
                    },
                    allowOutsideClick: () => !Swal.isLoading()
                }).then((result) => {
                    if (result.value !== undefined && result.isConfirmed) {
                        $('#documento').removeClass('is-invalid')
                        if (tipo_documento === 'DNI')
                            camposDNI(result);
                        else
                            camposRUC(result);

                        consultaExitosa();
                    }
                });
            }
        }

        function camposDNI(objeto) {
            if (objeto.value === undefined)
                return;

            var nombres = objeto.value.nombres;
            var apellido_paterno = objeto.value.apellidoPaterno;
            var apellido_materno = objeto.value.apellidoMaterno;
            var codigo_verificacion = objeto.value.codVerifica;

            var nombre = "";
            if (nombres !== '-' && nombres !== "NULL" ) {
                nombre += nombres;
            }
            if (apellido_paterno !== '-' && apellido_paterno !== "NULL" ) {
                nombre += (nombre.length === 0) ? apellido_paterno : ' ' + apellido_paterno
            }
            if (apellido_materno !== '-' && apellido_materno !== "NULL" ) {
                nombre += (nombre.length === 0) ? apellido_materno : ' ' + apellido_materno
            }
            $("#nombre").val(nombre);
            $("#activo").val("ACTIVO");
            if (codigo_verificacion !== '-' && codigo_verificacion !== "NULL" ) {
                $('#codigo_verificacion').val(codigo_verificacion);
            }
        }

        function clearDatosPersona(limpiarDocumento) {
            if (limpiarDocumento)
                $('#documento').val("");

            $('#nombre').val("");
            $('#codigo_verificacion').val("");
            $('#direccion').val("");
            $('#departamento').val("").trigger("change");
            $('#provincia').val("").trigger("change");
            $('#distrito').val("").trigger("change");
            $("#provincia").empty();
            $("#distrito").empty();
            $('#correo_electronico').val("");
            $('#telefono_movil').val("");
            $('#telefono_fijo').val("");
            if ($("#section_datos_contacto").is(":visible")) {
                $('#nombre_contacto').val("");
                $('#telefono_contacto').val("");
                $('#correo_electronico_contacto').val("");
            }
            $('#moneda_credito').val("");
            $('#limite_credito').val("");
            departamento_api = ''; provincia_api = ''; distrito_api = '';
        }

        $("#form_registrar_cliente").steps({
            bodyTag: "fieldset",
            transitionEffect: "fade",
            labels: {
                current: "actual paso:",
                pagination: "Paginación",
                finish: "Finalizar",
                next: "Siguiente",
                previous: "Anterior",
                loading: "Cargando ..."
            },
            onStepChanging: function (event, currentIndex, newIndex)
            {
                // Always allow going backward even if the current step contains invalid fields!
                if (currentIndex > newIndex)
                {
                    return true;
                }

                var form = $(this);

                // Clean up if user went backward before
                if (currentIndex < newIndex)
                {
                    // To remove error styles
                    $(".body:eq(" + newIndex + ") label.error", form).remove();
                    $(".body:eq(" + newIndex + ") .error", form).removeClass("error");
                }

                // Start validation; Prevent going forward if false
                return validarDatos(currentIndex + 1);
            },
            onStepChanged: function (event, currentIndex, priorIndex)
            {

            },
            onFinishing: function (event, currentIndex)
            {
                var form = $(this);

                // Start validation; Prevent form submission if false
                return true;
            },
            onFinished: function (event, currentIndex)
            {
                var form = $(this);

                // Submit form input
                form.submit();
            }
        });

        function validarDatos(paso) {
            //console.log("paso: " + paso);
            switch (paso) {
                case 1:
                    return validarDatosPersonales();
                    //return validarDatosContacto();

                case 2:
                    return validarDatosContacto();
                    //return validarDatosLaborales();

                case 3:
                    return validarDatosLaborales();

                case 4:
                    return validarDatosAdicionales();

                default:
                    return false;
            }
        }

        function validarDatosPersonales() {
            debugger;
            var tipo_documento = $("#tipo_documento").val();
            var documento = $("#documento").val();
            var nombres = $("#nombre").val();
            var departamento = $("#departamento").val();
            var provincia = $("#provincia").val();
            var distrito = $("#distrito").val();
            var telefono_movil = $("#telefono_movil").val();
            var tipo_cliente = $("#tipo_cliente").val();
            $('.datepicker-days').removeAttr("style").hide();
            var sexo = $("#sexo_hombre").is(':checked') ? 'H' : 'M';

            if ((tipo_documento !== null && tipo_documento.length === 0) || documento.length === 0 || nombres.length === 0 || departamento.length === 0 || provincia.length === 0 || sexo.length === 0 || distrito.length === 0 || telefono_movil.length === 0 || tipo_cliente.length === 0) {
                toastr.error('Complete la información de los campos obligatorios (*)','Error');
                return false;
            }

            switch (tipo_documento) {
                case 'DNI':
                    if (documento.length !== 8) {
                        toastr.error('El DNI debe de contar con 8 dígitos','Error');
                        return false;
                    }
                    break;

                case 'CARNET EXT.':
                    if (documento.length !== 20) {
                        toastr.error('El CARNET DE EXTRANJERIA debe de contar con 20 dígitos','Error');
                        return false;
                    }
                    break;

                case 'PASAPORTE':
                    if (documento.length !== 20) {
                        toastr.error('El PASAPORTE debe de contar con 20 dígitos','Error');
                        return false;
                    }
                    break;

                case 'P. NAC.':
                    if (documento.length !== 25) {
                        toastr.error('La PARTIDAD DE NACIMIENTO debe de contar con 25 dígitos','Error');
                        return false;
                    }
                    break;
            }

            return true;
        }

        function validarDatosContacto() {
            var fecha_aniversario = $("#fecha_aniversario").find("input").val();

            if ( fecha_aniversario.length === 0 ) {
                toastr.error('Complete la información de los campos obligatorios (*)','Error');
                return false;
            }
            return true;
        }

        function validarDatosLaborales() {
            debugger;
            var area = $("#area").val();
            var profesion = $("#profesion").val();
            var cargo = $("#cargo").val();
            var sueldo = $("#sueldo").val();
            var sueldo_bruto = $("#sueldo_bruto").val();
            var sueldo_neto = $("#sueldo_neto").val();
            var moneda_sueldo = $("#moneda_sueldo").val();
            var tipo_banco = $("#tipo_banco").val();
            var numero_cuenta = $("#numero_cuenta").val();

            var fecha_inicio_actividad = $("#fecha_inicio_actividad").find("input").val();
            var fecha_fin_actividad = $("#fecha_fin_actividad").find("input").val();
            var fecha_inicio_planilla = $("#fecha_inicio_planilla").find("input").val();
            var fecha_fin_planilla = $("#fecha_fin_planilla").find("input").val();
            $('.datepicker-days').removeAttr("style").hide();

            if ((area === null || area.length === 0) || (profesion === null || profesion.length === 0) || (cargo === null || cargo.length === 0)
                || sueldo.length === 0 || sueldo_bruto.length === 0 || sueldo_neto.length === 0 || (moneda_sueldo === null || moneda_sueldo.length === 0)
                || fecha_inicio_actividad.length === 0) {
                toastr.error('Complete la información de los campos obligatorios (*)','Error');
                return false;
            }
            /*if (fecha_inicio_actividad.length > 0 && fecha_fin_actividad.length > 0) {
                if (fechaInicioActividad > fechaFinActividad) {
                    toastr.error('La fecha de inicio de actividad no debe ser mayor a la fecha fin de actividad','Error');
                    return false;
                }
            }
            if (fecha_inicio_planilla.length > 0 && fecha_fin_planilla.length > 0) {
                if (fechaInicioPlanilla > fechaFinPlanilla) {
                    toastr.error('La fecha de inicio de planilla no debe ser mayor a la fecha fin de planilla','Error');
                    return false;
                }
            }*/

            return true;
        }

        function validarEmail() {
            if (!emailIsValid($('#correo_electronico').val())) {
                toastr.error('El formato del email es incorrecto','Error');
                $('#correo_electronico').focus();
            }
        }

        function cambiarTipoDocumento() {
            var tipo_documento = $("#tipo_documento").val();
            if ( (tipo_documento !== "" || tipo_documento.length) && tipo_documento === 'RUC')
                $("#section_datos_contacto").show();
            else
                $("#section_datos_contacto").hide();

            setLongitudDocumento();
        }

        function cargarProvincias() {
            var departamento_id = $("#departamento").val();
            if (departamento_id !== "" || departamento_id.length > 0) {
                $.ajax({
                    type: 'post',
                    dataType: 'json',
                    data: {
                        _token: $('input[name=_token]').val(),
                        departamento_id: departamento_id
                    },
                    url: "{{ route('mantenimiento.ubigeo.provincias') }}",
                    success: function (data) {
                        // Limpiamos data
                        $("#provincia").empty();
                        $("#distrito").empty();

                        if (!data.error) {
                            // Mostramos la información
                            if (data.provincias != null) {
                                if (provincia_api !== '') {
                                    $("#provincia").select2({
                                        data: data.provincias
                                    }).val(provincia_api).trigger('change');
                                } else {
                                    $("#provincia").select2({
                                        data: data.provincias
                                    }).val($('#provincia').find(':selected').val()).trigger('change');
                                }
                            }
                        } else {
                            toastr.error(data.message, 'Mensaje de Error', {
                                "closeButton": true,
                                positionClass: 'toast-bottom-right'
                            });
                        }
                    }
                });
            }
        }

        function cargarDistritos() {
            var provincia_id = $("#provincia").val();
            if (provincia_id !== "" || provincia_id.length > 0) {
                $.ajax({
                    type: 'post',
                    dataType: 'json',
                    data: {
                        _token: $('input[name=_token]').val(),
                        provincia_id: provincia_id
                    },
                    url: "{{ route('mantenimiento.ubigeo.distritos') }}",
                    success: function (data) {
                        // Limpiamos data
                        $("#distrito").empty();

                        if (!data.error) {
                            // Mostramos la información
                            if (data.distritos != null) {
                                var selected = $('#distrito').find(':selected').val();
                                if (distrito_api !== '') {
                                    $("#distrito").select2({
                                        data: data.distritos
                                    }).val(distrito_api).trigger('change');
                                } else {
                                    $("#distrito").select2({
                                        data: data.distritos
                                    });
                                }
                            }
                        } else {
                            toastr.error(data.message, 'Mensaje de Error', {
                                "closeButton": true,
                                positionClass: 'toast-bottom-right'
                            });
                        }
                    }
                });
            }
        }

        function camposRUC(objeto) {

            if (objeto.value === undefined)
                return;

            var razonsocial = objeto.value.razonSocial;
            var direccion = objeto.value.direccion;
            var departamento = objeto.value.departamento;
            var provincia = objeto.value.provincia;
            var distrito = objeto.value.distrito;
            var estado = objeto.value.estado;

            if (razonsocial!='-' && razonsocial!="NULL" ) {
                $('#nombre').val(razonsocial);
            }

            if (estado=="ACTIVO" ) {
                $('#activo').val(estado);
            }else{
                toastr.error('Cliente con RUC no se encuentra "Activo"','Error');
            }

            if (direccion!='-' && direccion!="NULL" ) {
                $('#direccion').val(direccion);
            }

            camposUbigeoApi(departamento, provincia, distrito);
        }

        function camposUbigeoApi(departamento, provincia, distrito) {

            departamento_api = ''; provincia_api = ''; distrito_api = '';

            if (departamento !== '-' && departamento !== null && provincia !== '-' && provincia !== null
                && distrito !== '-' && distrito !== null) {
                $.ajax({
                    type: 'post',
                    dataType: 'json',
                    data: {
                        _token: $('input[name=_token]').val(),
                        departamento: departamento,
                        provincia: provincia,
                        distrito: distrito
                    },
                    url: "{{ route('mantenimiento.ubigeo.api_ruc') }}",
                    success: function (data) {
                        // Limpiamos data
                        $("#provincia").empty();
                        $("#distrito").empty();

                        if (!data.error) {
                            // Mostramos la información
                            if (data.ubigeo != null) {

                                departamento_api = data.ubigeo.departamento_id;
                                provincia_api = data.ubigeo.provincia_id;
                                distrito_api = data.ubigeo.id;

                                $("#departamento").val(departamento_api).trigger('change');
                            }
                        } else {
                            toastr.error(data.message, 'Mensaje de Error', {
                                "closeButton": true,
                                positionClass: 'toast-bottom-right'
                            });
                        }
                    }
                });
            }
        }

    </script>
@endpush
