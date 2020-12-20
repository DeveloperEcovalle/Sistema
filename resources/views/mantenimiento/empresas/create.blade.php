@extends('layout') @section('content')

@section('mantenimiento-active', 'active')
@section('empresas-active', 'active')

<div class="row wrapper border-bottom white-bg page-heading">

    <div class="col-lg-12">
        <h2 style="text-transform:uppercase;"><b>REGISTRAR NUEVA EMPRESA</b></h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{route('home')}}">Panel de Control</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{route('mantenimiento.empresas.index')}}">Empresas</a>
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

                    <form action="{{route('mantenimiento.empresas.store')}}" method="POST" enctype="multipart/form-data"
                        id="enviar_empresa">
                        {{csrf_field()}}

                        <div class="row">
                            <div class="col-sm-6 b-r">
                                <h4 class=""><b>Empresa</b></h4>
                                <div class="row">
                                    <div class="col-md-12">
                                        <p>Registrar datos de la nueva empresa:</p>
                                    </div>
                                </div>

                                <div class="form-group row">

                                    <div class="col-md-6">
                                        <label class="required">Ruc: </label>
                                        <input type="text"
                                            class="form-control {{ $errors->has('ruc') ? ' is-invalid' : '' }}"
                                            name="ruc" id="ruc" maxlength="11" value="{{old('ruc')}}"
                                            onchange="evaluarRuc(this.value)" required>

                                        @if ($errors->has('ruc'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('ruc') }}</strong>
                                        </span>
                                        @endif

                                    </div>


                                    <div class="col-md-6">
                                        <label>Estado: </label>
                                        <input type="text" id="estado"
                                            class="form-control {{ $errors->has('estado') ? ' is-invalid' : '' }}"
                                            name="estado" value="{{old('estado',"Inactivo")}}"
                                            style="text-transform:uppercase;" disabled>
                                        @if ($errors->has('estado'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('estado') }}</strong>
                                        </span>
                                        @endif
                                    </div>


                                </div>

                                <div class="form-group">
                                    <label class="required">Razón social: </label>

                                    <input type="text"
                                        class="form-control {{ $errors->has('razon_social') ? ' is-invalid' : '' }}"
                                        name="razon_social" value="{{ old('razon_social')}}" id="razon_social"
                                        style="text-transform:uppercase;">

                                    @if ($errors->has('razon_social'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('razon_social') }}</strong>
                                    </span>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label>Razón Social Abreviada: </label>
                                    <input type="text" id="razon_social_abreviada"
                                        class="form-control {{ $errors->has('razon_social_abreviada') ? ' is-invalid' : '' }}"
                                        name="razon_social_abreviada" value="{{ old('razon_social_abreviada')}}"
                                        style="text-transform:uppercase;">

                                    @if ($errors->has('razon_social_abreviada'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('razon_social_abreviada') }}</strong>
                                    </span>
                                    @endif
                                </div>


                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <label class="required">Dirección Fiscal:</label>
                                        <textarea type="text" id="direccion_fiscal" name="direccion_fiscal" required
                                            class="form-control {{ $errors->has('direccion_fiscal') ? ' is-invalid' : '' }}"
                                            value="{{old('direccion_fiscal')}}"
                                            style="text-transform:uppercase;">{{old('direccion_fiscal')}}</textarea>
                                        @if ($errors->has('direccion_fiscal'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('direccion_fiscal') }}</strong>
                                        </span>
                                        @endif
                                    </div>

                                </div>

                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <label class="required">Dirección de Llegada:</label>
                                        <textarea type="text" id="direccion_llegada" name="direccion_llegada"
                                            class="form-control {{ $errors->has('direccion_llegada') ? ' is-invalid' : '' }}"
                                            value="{{old('direccion_llegada')}}" style="text-transform:uppercase;"
                                            required>{{old('direccion_llegada')}}</textarea>
                                        @if ($errors->has('direccion_llegada'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('direccion_llegada') }}</strong>
                                        </span>
                                        @endif
                                    </div>

                                </div>




                            </div>

                            <div class="col-sm-6">


                                <div class="form-group">
                                    <label class="">Correo: </label>

                                    <input type="email"
                                        class="form-control {{ $errors->has('correo') ? ' is-invalid' : '' }}"
                                        name="correo" value="{{ old('correo')}}" id="correo"
                                        style="text-transform:uppercase;">

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
                                            name="telefono" id="telefono" style="text-transform:uppercase;"
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
                                            name="celular" id="celular" style="text-transform:uppercase;"
                                            value="{{old('celular')}}">
                                        @if ($errors->has('celular'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('celular') }}</strong>
                                        </span>
                                        @endif
                                    </div>

                                </div>

                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <label>Logo:</label>

                                        <div class="custom-file">
                                            <input id="logo" type="file" name="logo" id="logo"
                                                class="custom-file-input {{ $errors->has('logo') ? ' is-invalid' : '' }}"
                                                accept="image/*">

                                            <label for="logo" id="logo_txt"
                                                class="custom-file-label selected {{ $errors->has('ruta') ? ' is-invalid' : '' }}">Seleccionar</label>

                                            @if ($errors->has('logo'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('logo') }}</strong>
                                            </span>
                                            @endif

                                        </div>

                                    </div>
                                </div>

                                <div class="form-group row justify-content-center">
                                    <div class="col-6 align-content-center">
                                        <div class="row justify-content-end">
                                            <a href="javascript:void(0);" id="limpiar_logo">
                                                <span class="badge badge-danger">x</span>
                                            </a>
                                        </div>
                                        <div class="row justify-content-center">
                                            <p>
                                                <img class="logo" src="{{asset('storage/empresas/logos/default.png')}}"
                                                    alt="">
                                                <input id="url_logo" name="url_logo" type="hidden" value="">
                                            </p>
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-sm-6 b-r">
                                <h4><b>Datos del Representante</b></h4>
                                <p>Registrar datos del representante:</p>

                                <div class="form-group row">
                                    <div class="col-md-4">
                                        <label class="required">Dni:</label>
                                        <input type="text" name="dni_representante"
                                            class="form-control {{ $errors->has('dni_representante') ? ' is-invalid' : '' }}"
                                            id="dni_representante" value="{{old('dni_representante')}}" maxlength="8"
                                            style="text-transform:uppercase;" onchange="evaluarDni(this.value)"
                                            required>

                                        @if ($errors->has('dni_representante'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('dni_representante') }}</strong>
                                        </span>
                                        @endif
                                    </div>

                                    <div class="col-md-8">
                                        <label class="required">Nombre Completo:</label>
                                        <input type="text"
                                            class="form-control {{ $errors->has('nombre_representante') ? ' is-invalid' : '' }}"
                                            name="nombre_representante" id="nombre_representante"
                                            style="text-transform:uppercase;" value="{{old('nombre_representante')}}"
                                            required>

                                        @if ($errors->has('nombre_representante'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('nombre_representante') }}</strong>
                                        </span>
                                        @endif
                                    </div>


                                </div>

                            </div>
                            <div class="col-sm-6">

                                <h4><b>Datos del R.R.P.P</b></h4>
                                <p>Registrar datos del R.R.P.P:</p>

                                <div class="form-group row">

                                    <div class="col-md-6">
                                        <label class="required">N° de Asiento:</label>

                                        <input type="text"
                                            class="form-control {{ $errors->has('num_asiento') ? ' is-invalid' : '' }}"
                                            name="num_asiento" id="num_asiento" style="text-transform:uppercase;"
                                            value="{{old('num_asiento')}}" required>

                                        @if ($errors->has('num_asiento'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('num_asiento') }}</strong>
                                        </span>
                                        @endif

                                    </div>

                                    <div class="col-md-6">

                                        <label class="required">N° de Partida:</label>
                                        <input type="text"
                                            class="form-control {{ $errors->has('num_partida') ? ' is-invalid' : '' }}"
                                            name="num_partida" id="num_partida" style="text-transform:uppercase;"
                                            value="{{old('num_partida')}}" required>
                                        @if ($errors->has('num_partida'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('num_partida') }}</strong>
                                        </span>
                                        @endif


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
                                <a href="{{route('mantenimiento.empresas.index')}}" id="btn_cancelar"
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


<style>
.logo {
    width: 200px;
    height: 200px;
    border-radius: 10%;
}
</style>
@endpush

@push('scripts')


<script>
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


/* Limpiar imagen */
$('#limpiar_logo').click(function() {
    $('.logo').attr("src", "{{asset('storage/empresas/logos/default.png')}}")
    var fileName = "Seleccionar"
    $('.custom-file-label').addClass("selected").html(fileName);
    $('#logo').val('')
})

$('.custom-file-input').on('change', function() {
    var fileInput = document.getElementById('logo');
    var filePath = fileInput.value;
    var allowedExtensions = /(.jpg|.jpeg|.png)$/i;
    $imagenPrevisualizacion = document.querySelector(".logo");

    if (allowedExtensions.exec(filePath)) {
        var userFile = document.getElementById('logo');
        userFile.src = URL.createObjectURL(event.target.files[0]);
        var data = userFile.src;
        $imagenPrevisualizacion.src = data
        let fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').addClass("selected").html(fileName);
    } else {
        toastr.error('Extensión inválida, formatos admitidos (.jpg . jpeg . png)', 'Error');
        $('.logo').attr("src", "{{asset('storage/empresas/logos/default.png')}}")
    }
});

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
            if ($('#estado').val() == "ACTIVO") {
                $("#estado").prop('disabled', false)
                this.submit();
            } else {
                toastr.error('Ingrese una empresa activa', 'Error');
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
                var url = '{{ route("getApiruc", ":ruc")}}';
                url = url.replace(':ruc', ruc);
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
            $('#ruc').removeClass('is-invalid')
            camposRuc(result)
            consultaExitosa()
        })
    } else {
        toastr.error('El campo Ruc debe de contar con 11 dígitos', 'Error');
    }


}

function camposRuc(objeto) {
    var razonsocial = objeto.value.razonSocial;
    var nombrecorto = objeto.value.nombreComercial;
    var direccion = objeto.value.direccion;
    var departamento = objeto.value.departamento;
    var provincia = objeto.value.provincia;
    var distrito = objeto.value.distrito;
    var estado = objeto.value.estado;

    if (razonsocial != '-' && razonsocial != "NULL") {
        $('#razon_social').val(razonsocial)
    }

    if (nombrecorto != '-' && nombrecorto != "NULL") {
        $('#razon_social_abreviada').val(nombrecorto)
    } else {
        $('#razon_social_abreviada').val(razonsocial)
    }
    if (estado == "ACTIVO") {
        $('#estado').val(estado)
    } else {
        toastr.error('Empresa no se encuentra "Activa"', 'Error');
    }

    if (direccion != '-' && direccion != "NULL") {
        $('#direccion_fiscal').val(direccion +
            " - " + departamento +
            " - " + provincia +
            " - " + distrito)
    }

}

// Consulta Dni
$("#dni_representante").keypress(function() {
    if (event.which == 13) {
        event.preventDefault();
        var dni = $("#dni_representante").val()
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

    $('#nombre_representante').val(nombre_completo.join(' '))

}
</script>
@endpush