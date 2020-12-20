@extends('layout') @section('content')

@section('ventas-active', 'active')
@section('cotizaciones-active', 'active')

<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-12">
        <h2 style="text-transform:uppercase;"><b>REGISTRAR NUEVA COTIZACIÓN</b></h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('home') }}">Panel de Control</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('ventas.cotizacion.index') }}">Cotizaciones</a>
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
                    <form action="{{ route('ventas.cotizacion.store') }}" method="POST" id="form_registrar_cotizacion">
                        @csrf
                        <h4><b>Datos Generales</b></h4>
                        <div class="row">
                            <div class="form-group col-lg-6 col-xs-12">
                                <label class="required">Empresa</label>
                                <select id="empresa" name="empresa" class="select2_form form-control {{ $errors->has('empresa') ? ' is-invalid' : '' }}">
                                    <option></option>
                                    @foreach($empresas as $empresa)
                                        <option value="{{ $empresa->id }}" {{ (old('empresa') == $empresa->razon_social ? "selected" : "") }} >{{ $empresa->razon_social }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('empresa'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('empresa') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group col-lg-6 col-xs-12">
                                <label class="required">Cliente</label>
                                <select id="cliente" name="cliente" class="select2_form form-control {{ $errors->has('cliente') ? ' is-invalid' : '' }}">
                                    <option></option>
                                    @foreach($clientes as $cliente)
                                        <option value="{{ $cliente->id }}" {{ (old('cliente') == $cliente->id ? "selected" : "") }} >{{ $cliente->getDocumento() }} - {{ $cliente->nombre }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('cliente'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('cliente') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-lg-3 col-xs-12" id="fecha_documento">
                                <label class="required">Fecha de documento</label>
                                <div class="input-group date">
                                    <span class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </span>
                                    <input type="text" id="fecha_documento" name="fecha_documento" class="form-control {{ $errors->has('fecha_documento') ? ' is-invalid' : '' }}" value="{{old('fecha_documento')}}" readonly >
                                </div>
                            </div>
                            <div class="form-group col-lg-3 col-xs-12" id="fecha_atencion">
                                <label class="required">Fecha de atención</label>
                                <div class="input-group date">
                                    <span class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </span>
                                    <input type="text" id="fecha_atencion" name="fecha_atencion" class="form-control {{ $errors->has('fecha_atencion') ? ' is-invalid' : '' }}" value="{{old('fecha_atencion')}}" readonly >
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-lg-4 col-xs-12">
                                <label class="required">Total</label>
                                <input type="text" id="total" name="total" class="form-control {{ $errors->has('total') ? ' is-invalid' : '' }}" value="{{old('total')}}" maxlength="15" onkeypress="return filterFloat(event,this);" required>
                            </div>
                            <div class="form-group col-lg-4 col-xs-12">
                                <label class="required">Sub total</label>
                                <input type="text" id="sub_total" name="sub_total" class="form-control {{ $errors->has('sub_total') ? ' is-invalid' : '' }}" value="{{old('sub_total')}}" onkeypress="return filterFloat(event,this);" maxlength="15" required>
                            </div>
                            <div class="form-group col-lg-4 col-xs-12">
                                <label class="required">IGV</label>
                                <input type="text" id="igv" name="igv" class="form-control {{ $errors->has('igv') ? ' is-invalid' : '' }}" value="{{old('igv')}}" onkeypress="return filterFloat(event,this);" maxlength="15" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-lg-4 col-xs-12">
                                <label class="required">Monto</label>
                                <input type="text" id="monto" name="monto" class="form-control {{ $errors->has('monto') ? ' is-invalid' : '' }}" value="{{old('monto')}}" maxlength="15" onkeypress="return filterFloat(event,this);" required>
                            </div>
                            <div class="form-group col-lg-4 col-xs-12">
                                <label class="required">Monto exento</label>
                                <input type="text" id="monto_exento" name="monto_exento" class="form-control {{ $errors->has('monto_exento') ? ' is-invalid' : '' }}" value="{{old('monto_exento')}}" onkeypress="return filterFloat(event,this);" maxlength="15" required>
                            </div>
                        </div>
                        <hr>
                        <h4><b>Detalles de Cotización</b></h4>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@stop

@push('styles')
    <link href="{{ asset('Inspinia/css/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css') }}" rel="stylesheet">
    <link href="{{ asset('Inspinia/css/plugins/datapicker/datepicker3.css') }}" rel="stylesheet">
    <link href="{{ asset('Inspinia/css/plugins/daterangepicker/daterangepicker-bs3.css') }}" rel="stylesheet">
    <link href="{{asset('Inspinia/css/plugins/select2/select2.min.css')}}" rel="stylesheet">
@endpush

@push('scripts')
    <script src="{{ asset('Inspinia/js/plugins/iCheck/icheck.min.js') }}"></script>
    <script src="{{ asset('Inspinia/js/plugins/select2/select2.full.min.js') }}"></script>
    <!-- Data picker -->
    <script src="{{ asset('Inspinia/js/plugins/datapicker/bootstrap-datepicker.js') }}"></script>
    <!-- Date range use moment.js same as full calendar plugin -->
    <script src="{{ asset('Inspinia/js/plugins/fullcalendar/moment.min.js') }}"></script>
    <!-- Date range picker -->
    <script src="{{ asset('Inspinia/js/plugins/daterangepicker/daterangepicker.js') }}"></script>
    <script>
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

            $('.input-group.date').datepicker({
                todayBtn: "linked",
                keyboardNavigation: false,
                forceParse: false,
                autoclose: true,
                language: 'es',
                format: "dd/mm/yyyy"
            });
        });
    </script>
@endpush
