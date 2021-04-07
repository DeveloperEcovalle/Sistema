@extends('layout') @section('content')

@section('compras-active', 'active')
@section('orden-compra-active', 'active')

<div class="row wrapper border-bottom white-bg page-heading">

    <div class="col-lg-12">
       <h2  style="text-transform:uppercase"><b>MODIFICAR ORDEN DE COMPRA # {{$orden->id}}</b></h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{route('home')}}">Panel de Control</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{route('compras.orden.index')}}">Ordenes de Compra</a>
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

                    <form action="{{route('compras.orden.update', $orden->id)}}" method="POST" id="enviar_orden">
                        @csrf @method('PUT')

                        <div class="row">
                            <div class="col-sm-6 b-r">
                                <h4 class=""><b>Orden de compra</b></h4>
                                <div class="row">
                                    <div class="col-md-12">
                                        <p>Modificar datos de la orden de compra:</p>
                                    </div>
                                </div>

                                <div class="form-group row">

                                    <div class="col-lg-6 col-xs-12" id="fecha_documento">
                                        <label class="required">Fecha de Emisión</label>
                                        <div class="input-group date">
                                            <span class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </span>
                                            <input type="text" id="fecha_documento" name="fecha_emision"
                                                class="form-control {{ $errors->has('fecha_emision') ? ' is-invalid' : '' }}"
                                                value="{{old('fecha_emision', getFechaFormato($orden->fecha_emision, 'd/m/Y'))}}"
                                                autocomplete="off" readonly required>
                                            @if ($errors->has('fecha_emision'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('fecha_emision') }}</strong>
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
                                            <input type="text" id="fecha_entrega" name="fecha_entrega"
                                                class="form-control {{ $errors->has('fecha_entrega') ? ' is-invalid' : '' }}"
                                                value="{{old('fecha_entrega', getFechaFormato($orden->fecha_entrega, 'd/m/Y'))}}"
                                                autocomplete="off" readonly required>
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
                                        name="empresa_id" id="empresa_id" disabled>
                                        <option></option>
                                        @foreach ($empresas as $empresa)
                                            <option value="{{$empresa->id}}" @if($empresa->id == '1' )
                                            {{'selected'}} @endif >{{$empresa->razon_social}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <hr>
                                <div class="row">
                                    <div class="col-md-12">
                                        <p>Registrar Proveedor:</p>
                                    </div>
                                </div>


                                <div class="form-group">
                                    <label class="required">Ruc / Dni: </label>
                                    <select
                                        class="select2_form form-control {{ $errors->has('proveedor_id') ? ' is-invalid' : '' }}"
                                        style="text-transform: uppercase; width:100%" value="{{old('proveedor_id')}}"
                                        name="proveedor_id" id="proveedor_id" required>
                                        <option></option>
                                        @foreach ($proveedores as $proveedor)
                                        @if($proveedor->ruc)
                                        <option value="{{$proveedor->id}}" @if(old('proveedor_id',$orden->proveedor_id)
                                            == $proveedor->id ) {{'selected'}} @endif
                                            >{{$proveedor->ruc}}</option>
                                        @else
                                        @if($proveedor->dni)
                                        <option value="{{$proveedor->id}}" @if(old('proveedor_id',$orden->proveedor_id)
                                            == $proveedor->id ) {{'selected'}} @endif
                                            >{{$proveedor->dni}}</option>
                                        @endif
                                        @endif
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label class="required">Razon Social: </label>
                                    <select
                                        class="select2_form form-control {{ $errors->has('proveedor_razon') ? ' is-invalid' : '' }}"
                                        style="text-transform: uppercase; width:100%" value="{{old('proveedor_razon')}}"
                                        name="proveedor_razon" id="proveedor_razon" required>
                                        <option></option>
                                        @foreach ($proveedores as $proveedor)
                                            @if($proveedor->ruc)
                                            <option value="{{$proveedor->id}}" @if(old('proveedor_id',$orden->proveedor_id)==$proveedor->id )
                                                {{'selected'}} @endif >{{$proveedor->descripcion}}
                                            </option>
                                            @else
                                            @if($proveedor->dni)
                                            <option value="{{$proveedor->id}}" @if(old('proveedor_id',$orden->proveedor_id)==$proveedor->id )
                                                {{'selected'}} @endif >{{$proveedor->descripcion}}
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
                                        style="text-transform: uppercase; width:100%"
                                        value="{{old('modo_compra',$orden->modo_compra)}}" name="modo_compra"
                                        id="modo_compra" required>
                                        <option></option>
                                        @foreach ($modos as $modo)
                                        <option value="{{$modo->descripcion}}" @if(old('modo_compra',$orden->
                                            modo_compra)==$modo->
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
                                    <div class="col-md-5">
                                        <label class="required">Moneda: </label>
                                        <select
                                            class="select2_form form-control {{ $errors->has('moneda') ? ' is-invalid' : '' }}"
                                            style="text-transform: uppercase; width:100%"
                                            value="{{old('moneda',$orden->moneda)}}" name="moneda" id="moneda" required>
                                            <option></option>
                                            @foreach ($monedas as $moneda)
                                            <option value="{{$moneda->descripcion}}" @if(old('moneda',$orden->
                                                moneda)==$moneda->
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
                                    <div class="col-md-4">
                                        <label class=""  id="campo_tipo_cambio">Tipo de Cambio (S/.):</label>
                                        <input type="text" id="tipo_cambio" name="tipo_cambio" class="form-control {{ $errors->has('tipo_cambio') ? ' is-invalid' : '' }}" value="{{old('tipo_cambio',$orden->tipo_cambio)}}" disabled>
                                        @if ($errors->has('tipo_cambio'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('tipo_cambio') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                    <div class="col-md-3">
                                        <label id="igv_requerido">IGV (%):</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-addon">
                                                    <input type="checkbox" id="igv_check" name="igv_check">
                                                </span>
                                            </div>
                                            <input type="text" value="{{old('igv',$orden->igv)}}" maxlength="3"
                                                class="form-control {{ $errors->has('igv') ? ' is-invalid' : '' }}"
                                                name="igv" id="igv"  onkeyup="return mayus(this)" required>
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
                                        name="observacion" id="observacion"  onkeyup="return mayus(this)"
                                        value="{{old('observacion', $orden->observacion)}}">{{old('observacion',$orden->observacion)}}</textarea>
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
                                                        style='color:white;'>
                                                        <i class="fa fa-plus"></i> AGREGAR</a>
                                                </div>

                                            </div>
                                        </div>


                                        <hr>

                                        <div class="table-responsive">
                                            <table
                                                class="table dataTables-orden-detalle table-striped table-bordered table-hover"
                                                style="text-transform:uppercase">
                                                <thead>
                                                    <tr>
                                                        <th></th>
                                                        <th class="text-center">ACCIONES</th>
                                                        <th class="text-center">CANTIDAD</th>
                                                        <th class="text-center">PRESENTACION</th>
                                                        <th class="text-center">PRODUCTO</th>
                                                        <th class="text-center">PRECIO</th>
                                                        <th class="text-center">TOTAL</th>

                                                    </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                                <tfoot style="text-transform:uppercase">
                                                    <tr>
                                                        <th colspan="6" style="text-align:right">Sub Total:</th>
                                                        <th><span id="subtotal"></span></th>

                                                    </tr>
                                                    <tr>
                                                        <th colspan="6" class="text-center">IGV <span
                                                                id="igv_int"></span>:</th>
                                                        <th class="text-center"><span id="igv_monto"></span></th>

                                                    </tr>
                                                    <tr>
                                                        <th colspan="6" class="text-center">TOTAL:</th>
                                                        <th class="text-center"><span id="total"></span></th>

                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>






                                    </div>
                                </div>
                            </div>

                        </div>



                        <!-- MONTOS -->
                        <input type="hidden" name="monto_sub_total" id="monto_sub_total" value="{{ old('monto_sub_total',$orden->sub_total) }}">
                        <input type="hidden" name="monto_total_igv" id="monto_total_igv" value="{{ old('monto_total_igv',$orden->total_igv) }}">
                        <input type="hidden" name="monto_total" id="monto_total" value="{{ old('monto_total',$orden->total) }}">


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
<link href="{{ asset('Inspinia/css/plugins/steps/jquery.steps.css') }}" rel="stylesheet">
<!-- DataTable -->
<link href="{{asset('Inspinia/css/plugins/dataTables/datatables.min.css')}}" rel="stylesheet">
<style>
.select2-container--open {
    z-index: 9999999
}
</style>
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
<!-- Chosen -->
<script src="{{asset('Inspinia/js/plugins/chosen/chosen.jquery.js')}}"></script>
<script>
//IGV
$(document).ready(function() {
    if ($("#igv_check").prop('checked')) {
        $('#igv').attr('disabled', false)
        $('#igv_requerido').addClass("required")
    } else {
        $('#igv').attr('disabled', true)
        $('#igv_requerido').removeClass("required")
    }
    //TIPO DE CAMBIO

    if ("{{old('moneda',$orden->moneda)}}" == "SOLES") {
        $('#tipo_cambio').attr('disabled',true)
        $("#tipo_cambio").attr("required", false);
        $("#campo_tipo_cambio").removeClass("required")
    }else{
        $('#tipo_cambio').attr('disabled',false)
        $("#tipo_cambio").attr("required", true);
        $("#campo_tipo_cambio").addClass("required")
    }

})

$("#igv_check").click(function() {
    if ($("#igv_check").is(':checked')) {
        $('#igv').attr('disabled', false)
        $('#igv_requerido').addClass("required")
        $('#igv').prop('required', true)
        $('#igv').val('18')
        var igv = ($('#igv').val()) + ' %'
        $('#igv_int').text(igv)
        sumaTotal()

    } else {
        $('#igv').attr('disabled', true)
        $('#igv_requerido').removeClass("required")
        $('#igv').prop('required', false)
        $('#igv').val('')
        $('#igv_int').text('')
    }
});

$("#igv").on("change", function() {
    if ($("#igv_check").is(':checked')) {
        $('#igv').attr('disabled', false)
        $('#igv_requerido').addClass("required")
        $('#igv').prop('required', true)
        var igv = ($('#igv').val()) + ' %'
        $('#igv_int').text(igv)
        sumaTotal()

    } else {
        $('#igv').attr('disabled', true)
        $('#igv_requerido').removeClass("required")
        $('#igv').prop('required', false)
        $('#igv').val('')
        $('#igv_int').text('')
        sumaTotal()
    }
});





//Select2
$(".select2_form").select2({
    placeholder: "SELECCIONAR",
    allowClear: true,
    height: '200px',
    width: '100%',
});

$('#fecha_documento .input-group.date').datepicker({
    todayBtn: "linked",
    keyboardNavigation: false,
    forceParse: false,
    autoclose: true,
    language: 'es',
    format: "dd/mm/yyyy"
});

$('#fecha_entrega .input-group.date').datepicker({
    todayBtn: "linked",
    keyboardNavigation: false,
    forceParse: false,
    autoclose: true,
    language: 'es',
    format: "dd/mm/yyyy"
});

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

$('#cantidad_editar').on('input', function() {
    this.value = this.value.replace(/[^0-9]/g, '');
});

$('#precio_editar').keyup(function() {
    var val = $(this).val();
    if (isNaN(val)) {
        val = val.replace(/[^0-9\.]/g, '');
        if (val.split('.').length > 2)
            val = val.replace(/\.+$/, "");
    }
    $(this).val(val);
});

$('#tipo_cambio').keyup(function() {
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

$('#igv').on('input', function() {
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
                cargarArticulos()
                    //CARGAR DATOS TOTAL
                $('#monto_sub_total').val($('#subtotal').text())
                $('#monto_total_igv').val($('#igv_monto').text())
                $('#monto_total').val($('#total').text())
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

$("#igv_check").click(function() {
    if ($("#igv_check").is(':checked')) {
        $('#igv').attr('disabled', false)
        $('#igv_requerido').addClass("required")
        $('#igv').prop('required', true)
        $('#igv').val('18')
        var igv = ($('#igv').val()) + ' %'
        $('#igv_int').text(igv)
        sumaTotal()

    } else {
        $('#igv').attr('disabled', true)
        $('#igv_requerido').removeClass("required")
        $('#igv').prop('required', false)
        $('#igv').val('')
        $('#igv_int').text('')
        sumaTotal()
    }
});


$(document).ready(function() {

    // DataTables
    $('.dataTables-orden-detalle').DataTable({
        "dom": 'lTfgitp',
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
                className: "text-center",
            },
            {
                "targets": [3],
                className: "text-center",
            },
            {
                "targets": [4],
                className: "text-left",
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

    @if(old('igv_check', $orden->igv_check))
        $("#igv_check").attr('checked', true);
        $('#igv').attr('disabled', false)
        $('#igv_requerido').addClass("required")
        $('#igv').prop('required', true)        
        var igv = ($('#igv').val()) + ' %'
        $('#igv_int').text(igv)


    @else
        $("#igv_check").attr('checked', false);
        $('#igv').attr('disabled', true)
        $('#igv_requerido').removeClass("required")
        $('#igv').prop('required', false)

    @endif

    obtenerTabla()
    sumaTotal()
    //Controlar Error
    $.fn.DataTable.ext.errMode = 'throw';
    
})



function obtenerTabla() {
    var t = $('.dataTables-orden-detalle').DataTable();
    @foreach($detalles as $detalle)
    var presentacion = obtenerPresentacion("{{$detalle->articulo->presentacion}}")
    t.row.add([
        "{{$detalle->articulo_id}}",
        '',
        "{{$detalle->cantidad}}",
        presentacion,
        "{{$detalle->articulo->descripcion}}",
        "{{$detalle->precio}}",
        ("{{$detalle->precio}}" * "{{$detalle->cantidad}}").toFixed(2)
    ]).draw(false);
    @endforeach
}
//Editar Registro
$(document).on('click', '#editar_articulo', function(event) {
    var table = $('.dataTables-orden-detalle').DataTable();
    var data = table.row($(this).parents('tr')).data();

    $('#indice').val(table.row($(this).parents('tr')).index());
    $('#articulo_id_editar').val(data[0]).trigger('change');
    $('#presentacion_editar').val(articuloPresentacion(data[0]));
    $('#precio_editar').val(data[5]);
    $('#cantidad_editar').val(data[2]);
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
        toastr.error('Seleccione Producto.', 'Error');
        enviar = true;
        $('#articulo_id').addClass("is-invalid")
        $('#error-articulo').text('El campo Producto es obligatorio.')
    } else {
        var existe = buscarArticulo($('#articulo_id').val())
        if (existe == true) {
            toastr.error('Producto ya se encuentra ingresado.', 'Error');
            enviar = true;
        }
    }
    if ($('#precio').val() == '') {

        toastr.error('Ingrese el precio del Producto.', 'Error');
        enviar = true;

        $("#precio").addClass("is-invalid");
        $('#error-precio').text('El campo Precio es obligatorio.')
    }

    if ($('#cantidad').val() == '') {
        toastr.error('Ingrese cantidad del Producto.', 'Error');
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
            text: "¿Seguro que desea agregar Producto?",
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

function obtenerArticulo($id) {
    var articulo = ""
    @foreach($articulos as $articulo)
    if ("{{$articulo->id}}" == $id) {
        articulo = "{{$articulo->descripcion}}"
    }
    @endforeach
    return articulo;
}

function cargarPresentacion(articulo) {
    var id = articulo.value
    var presentacion = ""
    @foreach($articulos as $articulo)
    if ("{{$articulo->id}}" == id) {
        presentacion = "{{$articulo->presentacion}}"
        precio = "{{$articulo->precio_compra}}"
    }
    @endforeach
    //Añadir a input presentacion
    $('#presentacion').val(presentacion)
    $('#precio').val(precio)
}

$("#moneda").on("change", function() {
    var val = $(this).val();
    if (val == "SOLES") {
        $('#tipo_cambio').attr('disabled',true)
        $('#tipo_cambio').val('')
        $("#tipo_cambio").attr("required", false);
        $("#campo_tipo_cambio").removeClass("required")

    }else{
        $('#tipo_cambio').attr('disabled',false)
        $('#tipo_cambio').val('')
        $("#tipo_cambio").attr("required", true);
        $("#campo_tipo_cambio").addClass("required")
    }
});

function articuloPresentacion(articulo) {
    var presentacion = ""
    @foreach($articulos as $articulo)
    if ("{{$articulo->id}}" == articulo) {
        presentacion = "{{$articulo->presentacion}}"
    }
    @endforeach
    return presentacion
}


function agregarTabla($detalle) {

    var t = $('.dataTables-orden-detalle').DataTable();
    t.row.add([
        $detalle.articulo_id,
        '',
        $detalle.cantidad,
        $detalle.presentacion,
        $detalle.descripcion,
        $detalle.precio,
        ($detalle.cantidad * $detalle.precio).toFixed(2),
    ]).draw(false);
    cargarArticulos()

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
            precio: value[5],
            cantidad: value[2],
        };

        articulos.push(fila);

    });

    $('#articulos_tabla').val(JSON.stringify(articulos));
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

function registrosArticulos() {
    var table = $('.dataTables-orden-detalle').DataTable();
    var registros = table.rows().data().length;
    return registros
}


function sumaTotal() {
    var t = $('.dataTables-orden-detalle').DataTable();
    var subtotal = 0;
    t.rows().data().each(function(el, index) {
        subtotal = Number(el[6]) + subtotal
    });

    var igv = $('#igv').val()
    if (!igv) {
        sinIgv(subtotal)   
    }else{
        conIgv(subtotal)
    }
}

function sinIgv(subtotal) {
    // calular igv (calcular la base)
    var igv =  subtotal * 0.18
    var total = subtotal + igv
    $('#igv_int').text('18%')
    $('#subtotal').text(subtotal.toFixed(2))
    $('#igv_monto').text(igv.toFixed(2))
    $('#total').text(total.toFixed(2))

}

function conIgv(subtotal) {
    // calular igv (calcular la base)
    var igv = $('#igv').val()
    ///////////////////////////////

    if (igv) {
        var calcularIgv = igv/100
        var base = subtotal / (1 + calcularIgv)
        var nuevo_igv = subtotal - base;
        $('#igv_int').text(igv+'%')
        $('#subtotal').text(base.toFixed(2))
        $('#igv_monto').text(nuevo_igv.toFixed(2))
        $('#total').text(subtotal.toFixed(2))

    }else{
        toastr.error('Ingrese Igv.', 'Error');
    }

}



@if(!empty($aviso))

        Swal.fire({
            title: 'Recuerde',
            text: "Al modificar eliminará el documento generado por la orden, luego tendra que generar otro documento de compra",
            icon: 'question',
            showCancelButton: false,
        })

@endif

$(document).on("change", "#proveedor_razon", function () {
   id = $(this).val();
   if($("#proveedor_id").val() != id){
      $("#proveedor_id").select2('val',id);
   }
});

$(document).on("change", "#proveedor_id", function () {
   id = $(this).val();
   if($("#proveedor_razon").val() != id){
       $("#proveedor_razon").select2('val',id);
   }

 });
</script>









@endpush