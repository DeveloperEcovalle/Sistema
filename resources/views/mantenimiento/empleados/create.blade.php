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
    <form role="form" action="empleados.html">
        <div class="row">
            <div class="col-lg-12">
                <div class="tabs-container">
                    <ul class="nav nav-tabs" role="tablist">
                        <li><a class="nav-link active" data-toggle="tab" href="#tab-personales"> Datos personales</a></li>
                        <li><a class="nav-link" data-toggle="tab" href="#tab-laborales">Datos Laborales</a></li>
                        <li><a class="nav-link" data-toggle="tab" href="#tab-adicionales">Datos Adicionales</a></li>
                    </ul>
                    <div class="tab-content">
                        <div role="tabpanel" id="tab-personales" class="tab-pane active">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="form-group col-lg-4 col-xs-12">
                                        <label class="required">Nombre(s)</label>
                                        <input type="text" id="nombre" class="form-control" required>
                                    </div>
                                    <div class="form-group col-lg-4 col-xs-12">
                                        <label class="required">Primer apellido</label>
                                        <input type="text" id="primer_apellido" class="form-control" required>
                                    </div>
                                    <div class="form-group col-lg-4 col-xs-12">
                                        <label class="required">Segundo apellido</label>
                                        <input type="text" id="segundo_apellido" class="form-control">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-lg-4 col-xs-12">
                                        <label class="required">Tipo de documento</label>
                                        <select id="tipo_documento" class="form-control">
                                            <option></option>
                                            <option value="1">D.N.I</option>
                                            <option value="2">R.U.C</option>
                                            <option value="3">Pasaporte</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-lg-4 col-xs-12">
                                        <label class="required">Nro. Documento</label>
                                        <input type="text" id="documento" class="form-control" required>
                                    </div>
                                    <div class="form-group col-lg-4 col-xs-12">
                                        <label class="required">Sexo</label>
                                        <div class="row">
                                            <div class="col-sm-6 col-xs-6">
                                                <div class="radio">
                                                    <input type="radio" name="radio1" id="radio1" value="M" checked="">
                                                    <label for="radio1">
                                                        Hombre
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-sm-6 col-xs-6">
                                                <div class="radio">
                                                    <input type="radio" name="radio1" id="radio2" value="F">
                                                    <label for="radio2">
                                                        Mujer
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div id="fecha_nacimiento" class="form-group col-lg-4 col-xs-12 ">
                                        <label>Fecha de Nacimiento</label>
                                        <div class="input-group date">
                                                    <span class="input-group-addon">
                                                        <i class="fa fa-calendar"></i>
                                                    </span>
                                            <input type="text" class="form-control" value="03/04/2014">
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-4 col-xs-12">
                                        <label>Teléfono personal</label>
                                        <input type="text" id="telefono_personal" class="form-control" required>
                                    </div>
                                    <div class="form-group col-lg-4 col-xs-12">
                                        <label>Correo electrónico</label>
                                        <input type="email" id="email" class="form-control" required>
                                    </div>
                                </div>


                            </div>
                        </div>
                        <div role="tabpanel" id="tab-laborales" class="tab-pane">
                            <div class="panel-body">
                                <strong>Donec quam felis</strong>

                                <p>Thousand unknown plants are noticed by me: when I hear the buzz of the little world among the stalks, and grow familiar with the countless indescribable forms of the insects
                                    and flies, then I feel the presence of the Almighty, who formed us in his own image, and the breath </p>

                                <p>I am alone, and feel the charm of existence in this spot, which was created for the bliss of souls like mine. I am so happy, my dear friend, so absorbed in the exquisite
                                    sense of mere tranquil existence, that I neglect my talents. I should be incapable of drawing a single stroke at the present moment; and yet.</p>
                            </div>
                        </div>
                        <div role="tabpanel" id="tab-adicionales" class="tab-pane">
                            <div class="panel-body">
                                <strong>Donec quam felis</strong>

                                <p>Thousand unknown plants are noticed by me: when I hear the buzz of the little world among the stalks, and grow familiar with the countless indescribable forms of the insects
                                    and flies, then I feel the presence of the Almighty, who formed us in his own image, and the breath </p>

                                <p>I am alone, and feel the charm of existence in this spot, which was created for the bliss of souls like mine. I am so happy, my dear friend, so absorbed in the exquisite
                                    sense of mere tranquil existence, that I neglect my talents. I should be incapable of drawing a single stroke at the present moment; and yet.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="m-t-md col-lg-8">
                <i class="fa fa-exclamation-circle"></i> <small>Los campos marcados con asterisco (*) son obligatorios.</small>
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

            $('#fecha_nacimiento .input-group.date').datepicker({
                todayBtn: "linked",
                keyboardNavigation: false,
                forceParse: false,
                calendarWeeks: true,
                autoclose: true
            });

        })
    </script>
@endpush
