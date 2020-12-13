@extends('layout')

@section('content')

@section('mantenimiento-active', 'active')
@section('empleados-active', 'active')

<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10 col-md-10">
        <h2 style="text-transform:uppercase;"><b>Registrar Empleado</b></h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="#">Mantenimiento</a>
            </li>
            <li class="breadcrumb-item active">
                <a href="{{ route('mantenimiento.empleado.index') }}">Empleados</a>
            </li>
            <li class="breadcrumb-item active">
                <strong>Registrar</strong>
            </li>
        </ol>
    </div>
</div>


<div class="wrapper wrapper-content animated fadeIn">
    <form role="form" action="{{ route('mantenimiento.empleado.store') }}">
        @csrf
        <div class="row">
            <div class="col-lg-12">
                <div class="tabs-container">
                    <ul class="nav nav-tabs" role="tablist">
                        <li><a class="nav-link active" data-toggle="tab" href="#tab-personales"> Datos Personales</a></li>
                        <li><a class="nav-link" data-toggle="tab" href="#tab-contacto">Datos de Contacto</a></li>
                        <li><a class="nav-link" data-toggle="tab" href="#tab-laborales">Datos Laborales</a></li>
                        <li><a class="nav-link" data-toggle="tab" href="#tab-adicionales">Datos Adicionales</a></li>
                    </ul>
                    <div class="tab-content">
                        <div role="tabpanel" id="tab-personales" class="tab-pane active">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="form-group col-lg-4 col-xs-12">
                                        <label class="required">Tipo de documento</label>
                                        <select id="tipo_documento" name="tipo_documento" class="form-control">
                                            @foreach(tipos_documento() as $tipo_documento)
                                                <option value="{{ $tipo_documento->simbolo }}">{{ $tipo_documento->descripcion }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-lg-4 col-xs-12">
                                        <label class="required">Nro. Documento</label>
                                        <input type="text" id="documento" name="documento" class="form-control" maxlength="25" onkeypress="return isNumber(event)" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-lg-4 col-xs-12">
                                        <label class="required">Nombre(s)</label>
                                        <input type="text" id="nombres" name="nombres" class="form-control" maxlength="100" onkeyup="return mayus(this)" required>
                                    </div>
                                    <div class="form-group col-lg-4 col-xs-12">
                                        <label class="required">Primer apellido</label>
                                        <input type="text" id="primer_apellido" name="primer_apellido" class="form-control" onkeyup="return mayus(this)" maxlength="100" required>
                                    </div>
                                    <div class="form-group col-lg-4 col-xs-12">
                                        <label class="required">Segundo apellido</label>
                                        <input type="text" id="segundo_apellido" name="segundo_apellido" class="form-control" onkeyup="return mayus(this)" maxlength="100" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-lg-4 col-xs-12" id="fecha_nacimiento">
                                        <label class="required">Fecha de Nacimiento</label>
                                        <div class="input-group date">
                                                    <span class="input-group-addon">
                                                        <i class="fa fa-calendar"></i>
                                                    </span>
                                            <input type="text" id="fecha_nacimiento" name="fecha_nacimiento" class="form-control" autocomplete="off" >
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-4 col-xs-12">
                                        <label class="required">Sexo</label>
                                        <div class="row">
                                            <div class="col-sm-6 col-xs-6">
                                                <div class="radio">
                                                    <input type="radio" name="sexo" id="sexo_hombre" value="M" checked="">
                                                    <label for="sexo_hombre">
                                                        Hombre
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-sm-6 col-xs-6">
                                                <div class="radio">
                                                    <input type="radio" name="sexo" id="sexo_mujer" value="F">
                                                    <label for="sexo_mujer">
                                                        Mujer
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-4 col-xs-12">
                                        <label>Estado Civil</label>
                                        <select id="estado_civil" name="estado_civil" class="form-control">
                                            @foreach(estados_civiles() as $estado_civil)
                                                <option value="{{ $estado_civil->simbolo }}">{{ $estado_civil->descripcion }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div role="tabpanel" id="tab-contacto" class="tab-pane">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="form-group col-lg-4 col-xs-12">
                                        <label class="required">Departamento</label>
                                        <select id="departamento" name="departamento" class="form-control" style="width: 100%">
                                            <option></option>
                                            @foreach(departamentos() as $departamento)
                                                <option value="{{ $departamento->id }}">{{ $departamento->nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-lg-4 col-xs-12">
                                        <label class="required">Provincia</label>
                                        <select id="provincia" name="provincia" class="form-control" style="width: 100%">
                                            <option></option>
                                        </select>
                                    </div>
                                    <div class="form-group col-lg-4 col-xs-12">
                                        <label class="required">Distrito</label>
                                        <select id="distrito" name="distrito" class="form-control" style="width: 100%">
                                            <option></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-lg-12 col-xs-12">
                                        <label class="required">Dirección completa</label>
                                        <input type="text" id="direccion" name="direccion" class="form-control" maxlength="191" onkeyup="return mayus(this)" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-lg-4 col-xs-12">
                                        <label class="required">Correo electrónico</label>
                                        <input type="email" id="email" class="form-control" maxlength="100" onkeyup="return mayus(this)" required>
                                    </div>
                                    <div class="form-group col-lg-4 col-xs-12">
                                        <label class="required">Teléfono móvil</label>
                                        <input type="text" id="telefono_movil" name="telefono_movil" class="form-control" onkeyup="return mayus(this)" maxlength="50" required>
                                    </div>
                                    <div class="form-group col-lg-4 col-xs-12">
                                        <label>Teléfono fijo</label>
                                        <input type="text" id="telefono_fijo" name="telefono_fijo" class="form-control" onkeyup="return mayus(this)" maxlength="50">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div role="tabpanel" id="tab-laborales" class="tab-pane">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="form-group col-lg-4 col-xs-12">
                                        <label class="required">Área</label>
                                        <select id="area" name="area" class="form-control" style="width: 100%">
                                            <option></option>
                                            @foreach(areas() as $area)
                                                <option value="{{ $area->simbolo }}">{{ $area->descripcion }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-lg-4 col-xs-12">
                                        <label class="required">Profesión</label>
                                        <select id="profesion" name="profesion" class="form-control" style="width: 100%">
                                            <option></option>
                                            @foreach(profesiones() as $profesion)
                                                <option value="{{ $profesion->simbolo }}">{{ $profesion->descripcion }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-lg-4 col-xs-12">
                                        <label class="required">Cargo</label>
                                        <select id="cargo" name="cargo" class="form-control" style="width: 100%">
                                            <option></option>
                                            @foreach(cargos() as $cargo)
                                                <option value="{{ $cargo->simbolo }}">{{ $cargo->descripcion }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-lg-4 col-xs-12">
                                        <label class="required">Sueldo</label>
                                        <input type="text" id="sueldo" name="sueldo" class="form-control" maxlength="50" onkeyup="return mayus(this)" required>
                                    </div>
                                    <div class="form-group col-lg-4 col-xs-12">
                                        <label class="required">Sueldo bruto</label>
                                        <input type="text" id="sueldo_bruto" name="sueldo_bruto" class="form-control" onkeyup="return mayus(this)" maxlength="50" required>
                                    </div>
                                    <div class="form-group col-lg-4 col-xs-12">
                                        <label class="required">Sueldo neto</label>
                                        <input type="text" id="sueldo_neto" name="sueldo_neto" class="form-control" onkeyup="return mayus(this)" maxlength="50" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-lg-4 col-xs-12">
                                        <label class="required">Moneda sueldo</label>
                                        <select id="moneda_sueldo" name="moneda_sueldo" class="form-control" style="width: 100%">
                                            <option></option>
                                            @foreach(tipos_moneda() as $moneda)
                                                <option value="{{ $moneda->simbolo }}">{{ $moneda->descripcion }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-lg-4 col-xs-12">
                                        <label>Banco</label>
                                        <select id="tipo_banco" name="tipo_banco" class="form-control" style="width: 100%">
                                            <option></option>
                                            @foreach(bancos() as $banco)
                                                <option value="{{ $banco->simbolo }}">{{ $banco->descripcion }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-lg-4 col-xs-12">
                                        <label>Número de cuenta</label>
                                        <input type="text" id="numero_cuenta" name="numero_cuenta" class="form-control" maxlength="20" onkeyup="return mayus(this)" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-lg-4 col-xs-12" id="fecha_inicio_actividad">
                                        <label class="required">Fecha inicio actividad</label>
                                        <div class="input-group date">
                                                    <span class="input-group-addon">
                                                        <i class="fa fa-calendar"></i>
                                                    </span>
                                            <input type="text" id="fecha_inicio_actividad" name="fecha_inicio_actividad" class="form-control" autocomplete="off" >
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-4 col-xs-12" id="fecha_fin_actividad">
                                        <label>Fecha fin actividad</label>
                                        <div class="input-group date">
                                                    <span class="input-group-addon">
                                                        <i class="fa fa-calendar"></i>
                                                    </span>
                                            <input type="text" id="fecha_fin_actividad" name="fecha_fin_actividad" class="form-control" autocomplete="off" >
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-lg-4 col-xs-12" id="fecha_inicio_planilla">
                                        <label class="required">Fecha inicio planilla</label>
                                        <div class="input-group date">
                                                    <span class="input-group-addon">
                                                        <i class="fa fa-calendar"></i>
                                                    </span>
                                            <input type="text" id="fecha_inicio_planilla" name="fecha_inicio_planilla" class="form-control" autocomplete="off" >
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-4 col-xs-12" id="fecha_fin_planilla">
                                        <label>Fecha fin planilla</label>
                                        <div class="input-group date">
                                                    <span class="input-group-addon">
                                                        <i class="fa fa-calendar"></i>
                                                    </span>
                                            <input type="text" id="fecha_fin_planilla" name="fecha_fin_planilla" class="form-control" autocomplete="off" >
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div role="tabpanel" id="tab-adicionales" class="tab-pane">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="form-group col-lg-4 col-xs-12">
                                        <label class="required">Teléfono de referencia</label>
                                        <input type="text" id="telefono_referencia" name="telefono_referencia" class="form-control" maxlength="50" onkeyup="return mayus(this)">
                                    </div>
                                    <div class="form-group col-lg-4 col-xs-12">
                                        <label>Contacto de referencia</label>
                                        <input type="text" id="contacto_referencia" name="contacto_referencia" class="form-control" maxlength="191" onkeyup="return mayus(this)">
                                    </div>
                                    <div class="form-group col-lg-4 col-xs-12">
                                        <label>Número de hijos</label>
                                        <input type="number" id="numero_hijos" name="numero_hijos" class="form-control" >
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-lg-4 col-xs-12">
                                        <label>Grupo sanguíneo</label>
                                        <select id="grupo_sanguineo" name="grupo_sanguineo" class="form-control" style="width: 100%">
                                            <option></option>
                                            @foreach(grupos_sanguineos() as $grupo_sanguineo)
                                                <option value="{{ $grupo_sanguineo->simbolo }}">{{ $grupo_sanguineo->descripcion }} ({{ $grupo_sanguineo->simbolo }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-lg-8 col-xs-12">
                                        <label>Alergias</label>
                                        <input type="number" id="alergias" name="alergias" class="form-control" onkeyup="return mayus(this)">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="m-t-md col-lg-8">
                <i class="fa fa-exclamation-circle leyenda-required"></i>
                <small class="leyenda-required">Los campos marcados con asterisco (*) son obligatorios.</small>
            </div>
            <div class="m-t-md col-lg-4 col-xs-12">
                <div style="float: right">
                    <button type="button" id="btn_cancelar" class="btn btn-w-m btn-default">
                        <i class="fa fa-arrow-left"></i> Regresar
                    </button>
                    <button type="submit" id="btn_grabar" class="btn btn-w-m btn-primary">
                        <i class="fa fa-save"></i> Grabar
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

@stop
@push('styles')
    <link href="{{ asset('Inspinia/css/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css') }}" rel="stylesheet">
    <link href="{{ asset('Inspinia/css/plugins/datapicker/datepicker3.css') }}" rel="stylesheet">
    <link href="{{ asset('Inspinia/css/plugins/daterangepicker/daterangepicker-bs3.css') }}" rel="stylesheet">
    <link href="{{ asset('Inspinia/css/plugins/select2/select2.min.css') }}" rel="stylesheet">
@endpush

@push('scripts')
    <!-- iCheck -->
    <script src="{{ asset('Inspinia/js/plugins/iCheck/icheck.min.js') }}"></script>
    <!-- Data picker -->
    <script src="{{ asset('Inspinia/js/plugins/datapicker/bootstrap-datepicker.js') }}"></script>
    <!-- Date range use moment.js same as full calendar plugin -->
    <script src="{{ asset('Inspinia/js/plugins/fullcalendar/moment.min.js') }}"></script>
    <!-- Date range picker -->
    <script src="{{ asset('Inspinia/js/plugins/daterangepicker/daterangepicker.js') }}"></script>
    <!-- Select2 -->
    <script src="{{ asset('Inspinia/js/plugins/select2/select2.full.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('.i-checks').iCheck({
                checkboxClass: 'icheckbox_square-green',
                radioClass: 'iradio_square-green',
            });

            $("#tipo_documento").select2({
                placeholder: "Seleccione",
                allowClear: true
            });

            $("#estado_civil").select2({
                placeholder: "Seleccione",
                allowClear: true
            });

            $("#departamento").select2({
                placeholder: "Seleccione",
                allowClear: true
            });

            $("#departamento").select2({
                placeholder: "Seleccione",
                allowClear: true
            });

            $("#departamento").select2({
                placeholder: "Seleccione",
                allowClear: true
            }).on("change", function (e) {
                var departamento_id = this.value;
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
                                    $("#provincia").select2({
                                        data: data.provincias
                                    }).val($('#provincia').find(':selected').val()).trigger('change');
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
            });

            $('#provincia').select2({
                placeholder: "Seleccione",
                allowClear: true
            }).on("change", function (e) {
                var provincia_id = this.value;
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
                                    $("#distrito").select2({
                                        data: data.distritos
                                    });
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
            });

            $('#distrito').select2({
                placeholder: "Seleccione",
                allowClear: true
            });

            $('#area').select2({
                placeholder: "Seleccione",
                allowClear: true
            });

            $('#profesion').select2({
                placeholder: "Seleccione",
                allowClear: true
            });

            $('#cargo').select2({
                placeholder: "Seleccione",
                allowClear: true
            });

            $('#moneda_sueldo').select2({
                placeholder: "Seleccione",
                allowClear: true
            });

            $('#tipo_banco').select2({
                placeholder: "Seleccione",
                allowClear: true
            });

            $('#grupo_sanguineo').select2({
                placeholder: "Seleccione",
                allowClear: true
            });

            $('#fecha_nacimiento .input-group.date').datepicker({
                todayBtn: "linked",
                keyboardNavigation: false,
                forceParse: false,
                autoclose: true,
                language: 'es'
            });

        })
    </script>
@endpush
