@extends('layout') @section('content')

@section('ventas-active', 'active')
@section('clientes-active', 'active')

<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-12">
       <h2  style="text-transform:uppercase"><b>MODIFICAR CLIENTE </b></h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('home') }}">Panel de Control</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('ventas.cliente.index') }}">Clientes</a>
            </li>
            <li class="breadcrumb-item active">
                <strong>Modificar</strong>
            </li>
        </ol>
    </div>
</div>

@include('ventas.clientes._form')

@stop

@push('scripts')
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

            $("#tipo_documento").on("change", cambiarTipoDocumento);

            // $("#documento").on('change', consultarDocumento);

            $("#departamento").on("change", cargarProvincias);

            $('#provincia').on("change", cargarDistritos);

            $("input[type='email']").on('change', function() {
                if (!emailIsValid($(this).val())) {
                    toastr.error('El formato del email es incorrecto','Error');
                    $(this).focus();
                }
            });

            cambiarTipoDocumento()
       

        });

        function consultarDocumento2() {
            
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
                    'id': '{{ $cliente->id }}'
                }
            }).done(function (result){

                if (result.existe) {
                    if (!result.igual_persona) {
                        toastr.error('El '+ tipo_documento +' ingresado ya se encuentra registrado para un cliente','Error');
                        clearDatosPersona(true);
                    }else{
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


    </script>
@endpush

