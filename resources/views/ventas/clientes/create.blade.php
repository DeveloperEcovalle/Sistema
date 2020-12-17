@extends('layout') @section('content')

@section('ventas-active', 'active')
@section('clientes-active', 'active')

<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-12">
        <h2 style="text-transform:uppercase;"><b>REGISTRAR NUEVO CLIENTE</b></h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('home') }}">Panel de Control</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('ventas.cliente.index') }}">Clientes</a>
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
                    <form action="{{ route('ventas.cliente.store') }}" method="POST" id="form_registrar_cliente">
                        @csrf
                        <h4><b>Datos Generales</b></h4>
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
                            <div class="form-group col-lg-4 col-xs-12">
                                <label class="required">Nro. Documento</label>
                                <input type="text" id="documento" name="documento" class="form-control {{ $errors->has('documento') ? ' is-invalid' : '' }}" value="{{old('documento')}}" maxlength="8" onkeypress="return isNumber(event)" required>
                                @if ($errors->has('documento'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('documento') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <input type="hidden" id="codigo_verificacion" name="codigo_verificacion">
                            <div class="form-group col-lg-4 col-xs-12">
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
                        <hr>
                        <div id="section_datos_contacto">
                            <h4><b>Datos del Contacto</b></h4>
                            <div class="row">
                                <div class="form-group col-lg-4 col-xs-12">
                                    <label>Nombre completo</label>
                                    <input type="text" id="nombre_contacto" name="nombre_contacto" class="form-control {{ $errors->has('nombre_contacto') ? ' is-invalid' : '' }}" value="{{old('nombre_contacto')}}" maxlength="191" onkeyup="return mayus(this)">
                                    @if ($errors->has('nombre_contacto'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('nombre_contacto') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group col-lg-4 col-xs-12">
                                    <label>Teléfono móvil</label>
                                    <input type="text" id="telefono_contacto" name="telefono_contacto" class="form-control {{ $errors->has('telefono_contacto') ? ' is-invalid' : '' }}" value="{{old('telefono_contacto')}}" onkeypress="return isNumber(event)" maxlength="9">
                                    @if ($errors->has('telefono_contacto'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('telefono_contacto') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group col-lg-4 col-xs-12">
                                    <label>Correo electrónico</label>
                                    <input type="email" id="correo_electronico_contacto" name="correo_electronico_contacto" class="form-control {{ $errors->has('correo_electronico_contacto') ? ' is-invalid' : '' }}" value="{{old('correo_electronico_contacto')}}" maxlength="100" onkeyup="return mayus(this)">
                                    @if ($errors->has('correo_electronico_contacto'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('correo_electronico_contacto') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <hr>
                        </div>
                        <h4><b>Datos Adicionales</b></h4>
                        <div class="row">
                            <div class="form-group col-lg-4 col-xs-12">
                                <label>Moneda límite crédito</label>
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
                            <div class="form-group col-lg-4 col-xs-12">
                                <label>Límite de crédito</label>
                                <input type="text" id="limite_credito" name="limite_credito" class="form-control {{ $errors->has('limite_credito') ? ' is-invalid' : '' }}" value="{{old('limite_credito')}}" onkeypress="return filterFloat(event,this);" maxlength="15">
                                @if ($errors->has('limite_credito'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('limite_credito') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="row">
                            <div class="form-group text-left col-lg-6 col-xs-12">
                                <i class="fa fa-exclamation-circle leyenda-required"></i>
                                <small class="leyenda-required">Los campos marcados con asterisco (<label class="required"></label>) son obligatorios.</small>
                            </div>
                            <div class="form-group text-right col-lg-6 col-xs-12">
                                <a  href="{{route('ventas.cliente.index')}}"  id="btn_cancelar" class="btn btn-w-m btn-default">
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
    <link href="{{ asset('Inspinia/css/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css') }}" rel="stylesheet">
@endpush

@push('scripts')
    <script src="{{ asset('Inspinia/js/plugins/iCheck/icheck.min.js') }}"></script>
    <script src="{{ asset('Inspinia/js/plugins/select2/select2.full.min.js') }}"></script>
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

            $("#section_datos_contacto").hide();

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

        });

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

    </script>
@endpush

