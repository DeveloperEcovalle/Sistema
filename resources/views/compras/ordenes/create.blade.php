@extends('layout') @section('content')

@section('compras-active', 'active')
@section('orden-compra-active', 'active')

<div class="row wrapper border-bottom white-bg page-heading">

    <div class="col-lg-12">
        <h2 style="text-transform:uppercase;"><b>REGISTRAR NUEVA ORDEN DE COMPRA</b></h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{route('home')}}">Panel de Control</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{route('compras.orden.index')}}">Ordenes de Compra</a>
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

                    <form action="{{route('compras.orden.store')}}" method="POST" id="enviar_orden">
                        {{csrf_field()}}

                        <div class="row">
                            <div class="col-sm-6 b-r">
                                <h4 class=""><b>Orden de compra</b></h4>
                                <div class="row">
                                    <div class="col-md-12">
                                        <p>Registrar datos de la orden de compra:</p>
                                    </div>
                                </div>

                                <div class="form-group row">

                                    <div class="col-lg-6 col-xs-12" id="fecha_documento">
                                        <label class="required">Fecha de Documento</label>
                                        <div class="input-group date">
                                            <span class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </span>
                                            <input type="text" id="fecha_documento_campo" name="fecha_documento"
                                                class="form-control {{ $errors->has('fecha_documento') ? ' is-invalid' : '' }}"
                                                value="{{old('fecha_documento',getFechaFormato($fecha_hoy, 'd/m/Y'))}}"
                                                autocomplete="off" required readonly>
                                            @if ($errors->has('fecha_documento'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('fecha_documento') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-xs-12" id="fecha_entrega">
                                        <label class="required">Fecha de Entrega</label>
                                        <div class="input-group date">
                                            <span class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </span>
                                            <input type="text" id="fecha_entrega_campo" name="fecha_entrega"
                                                class="form-control {{ $errors->has('fecha_entrega') ? ' is-invalid' : '' }}"
                                                value="{{old('fecha_entrega',getFechaFormato($fecha_hoy, 'd/m/Y'))}}"
                                                autocomplete="off" required readonly>
                                            @if ($errors->has('fecha_entrega'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('fecha_entrega') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="required">Empresa: </label>
                                    <select
                                        class="select2_form form-control {{ $errors->has('empresa_id') ? ' is-invalid' : '' }}"
                                        style="text-transform: uppercase; width:100%" value="{{old('empresa_id')}}"
                                        name="empresa_id" id="empresa_id" required>
                                        <option></option>
                                        @foreach ($empresas as $empresa)
                                        <option value="{{$empresa->id}}" @if(old('empresa_id')==$empresa->id )
                                            {{'selected'}} @endif >{{$empresa->razon_social}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label class="required">Proveedor: </label>
                                    <select
                                        class="select2_form form-control {{ $errors->has('proveedor_id') ? ' is-invalid' : '' }}"
                                        style="text-transform: uppercase; width:100%" value="{{old('proveedor_id')}}"
                                        name="proveedor_id" id="proveedor_id" required>
                                        <option></option>
                                        @foreach ($proveedores as $proveedor)
                                        @if($proveedor->ruc)
                                        <option value="{{$proveedor->id}}" @if(old('proveedor_id')==$proveedor->id )
                                            {{'selected'}} @endif >{{$proveedor->ruc.' - '.$proveedor->descripcion}}
                                        </option>
                                        @else
                                        @if($proveedor->dni)
                                        <option value="{{$proveedor->id}}" @if(old('proveedor_id')==$proveedor->id )
                                            {{'selected'}} @endif >{{$proveedor->dni.' - '.$proveedor->descripcion}}
                                        </option>
                                        @endif
                                        @endif
                                        @endforeach
                                    </select>
                                </div>





                            </div>

                            <div class="col-sm-6">

                                <div class="form-group">
                                    <label class="required">Modo de Compra: </label>
                                    <select
                                        class="select2_form form-control {{ $errors->has('modo_compra') ? ' is-invalid' : '' }}"
                                        style="text-transform: uppercase; width:100%" value="{{old('modo_compra')}}"
                                        name="modo_compra" id="modo_compra" required>
                                        <option></option>
                                        @foreach ($modos as $modo)
                                        <option value="{{$modo->descripcion}}" @if(old('modo_compra')==$modo->
                                            descripcion ) {{'selected'}} @endif
                                            >{{$modo->simbolo.' - '.$modo->descripcion}}</option>
                                        @endforeach
                                        @if ($errors->has('modo_compra'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('modo_compra') }}</strong>
                                        </span>
                                        @endif
                                    </select>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label class="required">Moneda: </label>
                                        <select
                                            class="select2_form form-control {{ $errors->has('moneda') ? ' is-invalid' : '' }}"
                                            style="text-transform: uppercase; width:100%" value="{{old('moneda')}}"
                                            name="moneda" id="moneda" required>
                                            <option></option>
                                            @foreach ($monedas as $moneda)
                                            <option value="{{$moneda->descripcion}}" @if(old('moneda')==$moneda->
                                                descripcion ) {{'selected'}} @endif
                                                >{{$moneda->simbolo.' - '.$moneda->descripcion}}</option>
                                            @endforeach
                                            @if ($errors->has('moneda'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('moneda') }}</strong>
                                            </span>
                                            @endif
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label id="igv_requerido">IGV (%):</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-addon">
                                                    <input type="checkbox" id="igv_check" name="igv_check">
                                                </span>
                                            </div>
                                            <input type="text" value="{{old('igv')}}"
                                                class="form-control {{ $errors->has('igv') ? ' is-invalid' : '' }}"
                                                name="igv" id="igv" maxlength="3" style="text-transform:uppercase;"
                                                required>
                                            @if ($errors->has('igv'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('igv') }}</strong>
                                            </span>
                                            @endif

                                        </div>

                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Observación:</label>
                                    <textarea type="text" placeholder=""
                                        class="form-control {{ $errors->has('observacion') ? ' is-invalid' : '' }}"
                                        name="observacion" id="observacion" style="text-transform:uppercase;"
                                        value="{{old('observacion')}}">{{old('observacion')}}</textarea>
                                    @if ($errors->has('observacion'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('observacion') }}</strong>
                                    </span>
                                    @endif


                                </div>


                                <input type="hidden" id="articulos_tabla" name="articulos_tabla[]">

                            </div>

                        </div>

                        <hr>

                        <div class="row">

                            <div class="col-lg-12">
                                <div class="panel panel-primary">
                                    <div class="panel-heading">
                                        <h4 class=""><b>Detalle de la Orden de
                                                Compra</b></h4>
                                    </div>
                                    <div class="panel-body">


                                        <div class="row">

                                            <div class="col-md-6">
                                                <label class="required">Artículo</label>
                                                <select class="select2_form form-control"
                                                    style="text-transform: uppercase; width:100%" name="articulo_id"
                                                    id="articulo_id" onchange="cargarPresentacion(this)">
                                                    <option></option>
                                                    @foreach ($articulos as $articulo)
                                                    <option value="{{$articulo->id}}">{{$articulo->descripcion}}
                                                    </option>
                                                    @endforeach
                                                </select>
                                                <div class="invalid-feedback"><b><span id="error-articulo"></span></b>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="">Presentación</label>
                                                <input type="text" id="presentacion" name="presentacion"
                                                    class="form-control" disabled>
                                                <div class="invalid-feedback"><b><span
                                                            id="error-presentacion"></span></b></div>
                                            </div>


                                        </div>

                                        <div class="row">
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label class="col-form-label required" for="amount">Precio</label>
                                                    <input type="text" id="precio" class="form-control">
                                                    <div class="invalid-feedback"><b><span id="error-precio"></span></b>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">

                                                <label class="col-form-label required">Cantidad</label>
                                                <input type="text" id="cantidad" class="form-control">
                                                <div class="invalid-feedback"><b><span id="error-cantidad"></span></b>
                                                </div>


                                            </div>
                                            <div class="col-sm-6">

                                                <div class="form-group">
                                                    <label class="col-form-label" for="amount">&nbsp;</label>
                                                    <a class="btn btn-block btn-warning enviar_articulo"
                                                        style='color:white;'> <i class="fa fa-plus"></i> AGREGAR</a>
                                                </div>

                                            </div>
                                        </div>


                                        <hr>

                                        <div class="table-responsive">
                                            <table
                                                class="table dataTables-orden-detalle table-striped table-bordered table-hover"
                                                style="text-transform:uppercase;">
                                                <thead>
                                                    <tr>
                                                        <th></th>
                                                        <th class="text-center">ACCIONES</th>
                                                        <th class="text-center">ARTICULO</th>
                                                        <th class="text-center">PRESENTACION</th>
                                                        <th class="text-center">PRECIO</th>
                                                        <th class="text-center">CANTIDAD</th>
                                                        <th class="text-center">IMPORTE</th>

                                                    </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th colspan="6" style="text-align:right">Sub Total:</th>
                                                        <th><span id="subtotal">0.0</span></th>

                                                    </tr>
                                                    <tr>
                                                        <th colspan="6" class="text-center">IGV <span
                                                                id="igv_int"></span>:</th>
                                                        <th class="text-center"><span id="igv_monto">0.0</span></th>

                                                    </tr>
                                                    <tr>
                                                        <th colspan="6" class="text-center">TOTAL:</th>
                                                        <th class="text-center"><span id="total">0.0</span></th>

                                                    </tr>
                                                </tfoot>
                                            </table>
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
                                <a href="{{route('compras.orden.index')}}" id="btn_cancelar"
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
@include('compras.ordenes.modal')
@stop

@push('styles')
<link href="{{ asset('Inspinia/css/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css') }}"
    rel="stylesheet">
<link href="{{ asset('Inspinia/css/plugins/datapicker/datepicker3.css') }}" rel="stylesheet">
<link href="{{ asset('Inspinia/css/plugins/daterangepicker/daterangepicker-bs3.css') }}" rel="stylesheet">
<link href="{{ asset('Inspinia/css/plugins/select2/select2.min.css') }}" rel="stylesheet">


<!-- DataTable -->
<link href="{{asset('Inspinia/css/plugins/dataTables/datatables.min.css')}}" rel="stylesheet">

@endpush

@push('scripts')
<!-- Data picker -->
<script src="{{ asset('Inspinia/js/plugins/datapicker/bootstrap-datepicker.js') }}"></script>
<!-- Date range use moment.js same as full calendar plugin -->
<script src="{{ asset('Inspinia/js/plugins/fullcalendar/moment.min.js') }}"></script>
<!-- Date range picker -->
<script src="{{ asset('Inspinia/js/plugins/daterangepicker/daterangepicker.js') }}"></script>
<!-- Select2 -->
<script src="{{ asset('Inspinia/js/plugins/select2/select2.full.min.js') }}"></script>

<!-- DataTable -->
<script src="{{asset('Inspinia/js/plugins/dataTables/datatables.min.js')}}"></script>
<script src="{{asset('Inspinia/js/plugins/dataTables/dataTables.bootstrap4.min.js')}}"></script>




<script>
//Select2
$(".select2_form").select2({
    placeholder: "SELECCIONAR",
    allowClear: true,
    width: '100%',
});

$('#fecha_documento .input-group.date').datepicker({
    todayBtn: "linked",
    keyboardNavigation: false,
    forceParse: false,
    autoclose: true,
    language: 'es',
    format: "dd/mm/yyyy",
    startDate: "today"
})

$('#fecha_entrega .input-group.date').datepicker({
    todayBtn: "linked",
    keyboardNavigation: false,
    forceParse: false,
    autoclose: true,
    language: 'es',
    format: "dd/mm/yyyy",
    startDate: "today"
})



$(document).ready(function() {
    if ($("#igv_check").prop('checked')) {
        $('#igv').attr('disabled', false)
        $('#igv_requerido').addClass("required")
    } else {
        $('#igv').attr('disabled', true)
        $('#igv_requerido').removeClass("required")
    }
});

$("#igv_check").click(function() {
    if ($("#igv_check").is(':checked')) {
        $('#igv').attr('disabled', false)
        $('#igv_requerido').addClass("required")
        $('#igv').prop('required', true)
        $('#igv').val('18')
        var igv = ($('#igv').val()) + ' %'
        $('#igv_int').text(igv)
        calcularIgv($('#igv').val())

    } else {
        $('#igv').attr('disabled', true)
        $('#igv_requerido').removeClass("required")
        $('#igv').prop('required', false)
        $('#igv').val('')
        $('#igv_int').text('')
        calcularIgv($('#igv').val())
    }
});

$("#igv").on("change", function() {
    if ($("#igv_check").is(':checked')) {
        $('#igv').attr('disabled', false)
        $('#igv_requerido').addClass("required")
        $('#igv').prop('required', true)
        var igv = ($('#igv').val()) + ' %'
        $('#igv_int').text(igv)
        calcularIgv($('#igv').val())

    } else {
        $('#igv').attr('disabled', true)
        $('#igv_requerido').removeClass("required")
        $('#igv').prop('required', false)
        $('#igv').val('')
        $('#igv_int').text('')
    }
});




function sumaTotal() {
    var t = $('.dataTables-orden-detalle').DataTable();
    var subtotal = 0;
    t.rows().data().each(function(el, index) {
        subtotal = Number(el[6]) + subtotal
    });

    $('#subtotal').text(subtotal.toFixed(2))
}

function calcularIgv(igv) {
    var t = $('.dataTables-orden-detalle').DataTable();
    var subtotal = 0;
    t.rows().data().each(function(el, index) {
        subtotal = Number(el[6]) + subtotal
    });
    var monto_igv = igv / 100
    igv = subtotal * monto_igv
    var total = igv + subtotal
    $('#igv_monto').text(igv.toFixed(2))
    $('#total').text(total.toFixed(2))
}




// Solo campos numericos
$('#precio').keyup(function() {
    var val = $(this).val();
    if (isNaN(val)) {
        val = val.replace(/[^0-9\.]/g, '');
        if (val.split('.').length > 2)
            val = val.replace(/\.+$/, "");
    }
    $(this).val(val);
});

$('#cantidad').on('input', function() {
    this.value = this.value.replace(/[^0-9]/g, '');
});

function validarFecha() {
    var enviar = false
    var articulos = registrosArticulos()

    if ($('#fecha_documento_campo').val() == '') {
        toastr.error('Ingrese Fecha de Documento de la Orden.', 'Error');
        $("#fecha_documento_campo").focus();
        enviar = true;
    }

    if ($('#fecha_entrega_campo').val() == '') {
        toastr.error('Ingrese Fecha de Entrega de la Orden.', 'Error');
        $("#fecha_entrega_campo").focus();
        enviar = true;
    }
    if (articulos == 0) {
        toastr.error('Ingrese al menos 1  Artículo.', 'Error');
        enviar = true;
    }
    return enviar
}

$('#enviar_orden').submit(function(e) {
    e.preventDefault();
    var correcto = validarFecha()

    if (correcto == false) {
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
    }

})


$(document).ready(function() {

    // DataTables
    $('.dataTables-orden-detalle').DataTable({
        "dom": '<"html5buttons"B>lTfgitp',
        "buttons": [{
                extend: 'excelHtml5',
                text: '<i class="fa fa-file-excel-o"></i> Excel',
                titleAttr: 'Excel',
                title: 'Detalle de Orden de Compra'
            },
            {
                titleAttr: 'Imprimir',
                extend: 'print',
                text: '<i class="fa fa-print"></i> Imprimir',
                customize: function(win) {
                    $(win.document.body).addClass('white-bg');
                    $(win.document.body).css('font-size', '10px');
                    $(win.document.body).find('table')
                        .addClass('compact')
                        .css('font-size', 'inherit');
                }
            }
        ],
        "bPaginate": true,
        "bLengthChange": true,
        "bFilter": true,
        "bInfo": true,
        "bAutoWidth": false,
        "language": {
            "url": "{{asset('Spanish.json')}}"
        },

        "columnDefs": [{
                "targets": [0],
                "visible": false,
                "searchable": false
            },
            {

                "targets": [1],
                className: "text-center",
                render: function(data, type, row) {
                    return "<div class='btn-group'>" +
                        "<a class='btn btn-warning btn-sm modificarDetalle' id='editar_articulo' style='color:white;' title='Modificar'><i class='fa fa-edit'></i></a>" +
                        "<a class='btn btn-danger btn-sm' id='borrar_articulo' style='color:white;' title='Eliminar'><i class='fa fa-trash'></i></a>" +
                        "</div>";
                }
            },
            {
                "targets": [2],
            },
            {
                "targets": [3],
                className: "text-center",
            },
            {
                "targets": [4],
                className: "text-center",
            },
            {
                "targets": [5],
                className: "text-center",
            },
            {
                "targets": [6],
                className: "text-center",
            },

        ],

    });

})

//Editar Registro
$(document).on('click', '#editar_articulo', function(event) {
    var table = $('.dataTables-orden-detalle').DataTable();
    var data = table.row($(this).parents('tr')).data();

    $('#indice').val(table.row($(this).parents('tr')).index());
    $('#articulo_id_editar').val(data[0]).trigger('change');
    $('#presentacion_editar').val(articuloPresentacion(data[0]));
    $('#precio_editar').val(data[4]);
    $('#cantidad_editar').val(data[5]);
    $('#modal_editar_orden').modal('show');
})

//Borrar registro de articulos
$(document).on('click', '#borrar_articulo', function(event) {

    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger',
        },
        buttonsStyling: false
    })

    Swal.fire({
        title: 'Opción Eliminar',
        text: "¿Seguro que desea eliminar Artículo?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: "#1ab394",
        confirmButtonText: 'Si, Confirmar',
        cancelButtonText: "No, Cancelar",
    }).then((result) => {
        if (result.isConfirmed) {
            var table = $('.dataTables-orden-detalle').DataTable();
            table.row($(this).parents('tr')).remove().draw();
            sumaTotal()
            calcularIgv($('#igv').val())

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



});


//Validacion al ingresar tablas
$(".enviar_articulo").click(function() {
    limpiarErrores()
    var enviar = false;
    if ($('#articulo_id').val() == '') {
        toastr.error('Seleccione artículo.', 'Error');
        enviar = true;
        $('#articulo_id').addClass("is-invalid")
        $('#error-articulo').text('El campo Artículo es obligatorio.')
    } else {
        var existe = buscarArticulo($('#articulo_id').val())
        if (existe == true) {
            toastr.error('Artículo ya se encuentra ingresado.', 'Error');
            enviar = true;
        }
    }

    if ($('#precio').val() == '') {

        toastr.error('Ingrese el precio del artículo.', 'Error');
        enviar = true;

        $("#precio").addClass("is-invalid");
        $('#error-precio').text('El campo Precio es obligatorio.')
    }

    if ($('#cantidad').val() == '') {
        toastr.error('Ingrese cantidad del artículo.', 'Error');
        enviar = true;

        $("#cantidad").addClass("is-invalid");
        $('#error-cantidad').text('El campo Cantidad es obligatorio.')
    }
    if (enviar != true) {
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger',
            },
            buttonsStyling: false
        })

        Swal.fire({
            title: 'Opción Agregar',
            text: "¿Seguro que desea agregar Artículo?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: "#1ab394",
            confirmButtonText: 'Si, Confirmar',
            cancelButtonText: "No, Cancelar",
        }).then((result) => {
            if (result.isConfirmed) {
                var descripcion_articulo = obtenerArticulo($('#articulo_id').val())
                var presentacion_articulo = obtenerPresentacion($('#presentacion').val())
                var detalle = {
                    articulo_id: $('#articulo_id').val(),
                    descripcion: descripcion_articulo,
                    presentacion: presentacion_articulo,
                    precio: $('#precio').val(),
                    cantidad: $('#cantidad').val(),
                }
                limpiarDetalle()
                agregarTabla(detalle);
                sumaTotal()
                calcularIgv($('#igv').val())

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

    }
})

function limpiarDetalle() {
    $('#presentacion').val('')
    $('#precio').val('')
    $('#cantidad').val('')
    $('#articulo_id').val($('#articulo_id option:first-child').val()).trigger('change');

}

function limpiarErrores() {
    $('#cantidad').removeClass("is-invalid")
    $('#error-cantidad').text('')

    $('#precio').removeClass("is-invalid")
    $('#error-precio').text('')

    $('#articulo_id').removeClass("is-invalid")
    $('#error-articulo').text('')
}

function agregarTabla($detalle) {

    var t = $('.dataTables-orden-detalle').DataTable();
    t.row.add([
        $detalle.articulo_id,
        '',
        $detalle.descripcion,
        $detalle.presentacion,
        $detalle.precio,
        $detalle.cantidad,
        ($detalle.cantidad * $detalle.precio).toFixed(2),
    ]).draw(false);

    cargarArticulos()

}



function obtenerArticulo($id) {
    var articulo = ""
    @foreach($articulos as $articulo)
    if ("{{$articulo->id}}" == $id) {
        articulo = "{{$articulo->descripcion}}"
    }
    @endforeach
    return articulo;
}

function obtenerPresentacion($descripcion) {
    var presentacion = ""
    @foreach($presentaciones as $presentacion)
    if ("{{$presentacion->descripcion}}" == $descripcion) {
        presentacion = "{{$presentacion->simbolo}}"
    }
    @endforeach
    return presentacion;
}

function cargarPresentacion(articulo) {
    var id = articulo.value
    var presentacion = ""
    @foreach($articulos as $articulo)
    if ("{{$articulo->id}}" == id) {
        presentacion = "{{$articulo->presentacion}}"
    }
    @endforeach
    //Añadir a input presentacion
    $('#presentacion').val(presentacion)
}

function buscarArticulo(id) {
    var existe = false;
    var t = $('.dataTables-orden-detalle').DataTable();
    t.rows().data().each(function(el, index) {
        if (el[0] == id) {
            existe = true
        }
    });
    return existe
}


function cargarArticulos() {

    var articulos = [];
    var table = $('.dataTables-orden-detalle').DataTable();
    var data = table.rows().data();
    data.each(function(value, index) {
        let fila = {
            articulo_id: value[0],
            presentacion: value[3],
            precio: value[4],
            cantidad: value[5],
        };

        articulos.push(fila);

    });

    $('#articulos_tabla').val(JSON.stringify(articulos));
}




function registrosArticulos() {
    var table = $('.dataTables-orden-detalle').DataTable();
    var registros = table.rows().data().length;
    return registros
}

function articuloPresentacion(articulo) {
    var presentacion = ""
    @foreach($articulos as $articulo)
    if ("{{$articulo->id}}" == articulo) {
        presentacion = "{{$articulo->presentacion}}"
    }
    @endforeach
    return presentacion
}

function sumaTotal() {
    var t = $('.dataTables-orden-detalle').DataTable();
    var subtotal = 0;
    t.rows().data().each(function(el, index) {
        subtotal = Number(el[6]) + subtotal
    });

    $('#subtotal').text(subtotal.toFixed(2))
}
</script>
@endpush