@extends('layout') @section('content')

@section('compras-active', 'active')
@section('orden-compra-active', 'active')

<div class="row wrapper border-bottom white-bg page-heading">

    <div class="col-lg-12">
        <h2 style="text-transform:uppercase;"><b>MODIFICAR PAGO DE LA ORDEN DE COMPRA #{{$orden->id}}</b></h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{route('home')}}">Panel de Control</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{route('compras.orden.index')}}">Ordenes de Compra</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{route('compras.pago.index', $orden->id)}}">Pagos</a>
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
            <div class="ibox ">
                <div class="ibox-content">
                    <form action="{{route('compras.pago.update',$pago->id)}}" id="enviar_pago" method="POST" enctype="multipart/form-data">
                            @csrf @method('PUT')
                            <div class="row">
                                <div class="col-sm-8 b-r">
                                    <h4 class="">Entidad Financiera</h4>
                                    <p>Seleccionar entidad financiera:</p>

                                    <div class="form-group">
                                        <div class="table-responsive">
                                            <table class="table dataTables-bancos table-striped table-bordered table-hover"
                                                style="text-transform:uppercase;" id="table-bancos">
                                                <thead>
                                                    <tr>
                                                        <th></th>
                                                        <th class="text-center">DESCRIPCION</th>
                                                        <th class="text-center">MONEDA</th>
                                                        <th class="text-center">CUENTA</th>
                                                        <th class="text-center">CCI</th>

                                                    </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                            </table>
                                        </div>

                                    </div>

                                </div>
                                <div class="col-sm-4">
                                    <h4>Datos Seleccionados</h4>
                                    <p>Datos seleccionados de la entidad financiera:</p>
                                    <input type="hidden" name="id_entidad" id="id_entidad" value="{{old('id_entidad', $pago->banco_id)}}">
                                    <input type="hidden" name="id_orden" id="id_orden" value="{{$orden->id}}">
                                    <div class="form-group">

                                        <label class="col-form-label">Descripción</label>
                                        <input type="text" id="descripcion" class="form-control"value="{{old('id_entidad', $pago->banco->descripcion)}}" disabled>
                                        
                                    </div>

                                    <div class="form-group">

                                        <label class="col-form-label">Moneda</label>
                                        <input type="text" id="moneda" name="moneda" class="form-control" value="{{old('moneda', $pago->banco->tipo_moneda)}}" disabled>

                                    </div>
                                    <div class="form-group">

                                        <label class="col-form-label">N° Cuenta</label>
                                        <input type="text" id="cuenta"  name="cuenta" class="form-control" value="{{old('cuenta', $pago->banco->num_cuenta)}}" disabled>

                                    </div>
                                    <div class="form-group">

                                        <label class="col-form-label">N° CCI</label>
                                        <input type="text" id="cci" name="cci" value="{{old('cci', $pago->banco->cci)}}" class="form-control" disabled>

                                    </div>

                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-6 b-r">
                                    <h4>Pago</h4>
                                    <p>Registrar datos del nuevo pago:</p>
                                    <div class="form-group row">

                                        <div class="col-lg-6 col-xs-12" id="fecha_pago">
                                            <label class="required">Fecha de Pago</label>
                                            <div class="input-group date">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                </span>
                                                <input type="text" id="fecha_pago_campo" name="fecha_pago"
                                                    class="form-control {{ $errors->has('fecha_pago') ? ' is-invalid' : '' }}"
                                                    value="{{old('fecha_pago',getFechaFormato($pago->fecha_pago, 'd/m/Y'))}}"
                                                    autocomplete="off" required readonly>

                                                    @if ($errors->has('fecha_pago'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('fecha_pago') }}</strong>
                                                    </span>
                                                    @endif
                                            </div>
                                        </div>

                                        <div class="col-lg-6 col-xs-12" id="">
                                                <label class="required">Monto:</label>
                                                <input type="text" id="monto" name="monto" class="form-control {{ $errors->has('monto') ? ' is-invalid' : '' }}" value="{{old('monto',$pago->monto)}}" required>
                                                @if ($errors->has('monto'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('monto') }}</strong>
                                                </span>
                                                @endif

                                        </div>

                                    </div>
                                    <div class="form-group row">

                                        <div class="col-lg-6 col-xs-12" id="">
                                            <label class="required">Moneda</label>
                                            <select
                                                class="select2_form form-control {{ $errors->has('moneda') ? ' is-invalid' : '' }}"
                                                style="text-transform: uppercase; width:100%" value="{{old('moneda')}}"
                                                name="moneda" id="moneda" required>
                                                <option></option>
                                                @foreach ($monedas as $moneda)
                                                <option value="{{$moneda->descripcion}}" @if(old('moneda',$pago->moneda)==$moneda->
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

                                        <div class="col-lg-6 col-xs-12" id="">
                                                <label class="required">Tipo de Cambio:</label>
                                                <input type="text" id="tipo_cambio" name="tipo_cambio" class="form-control {{ $errors->has('tipo_cambio') ? ' is-invalid' : '' }}" value="{{old('tipo_cambio',$pago->tipo_cambio)}}" required>
                                                @if ($errors->has('tipo_cambio'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('tipo_cambio') }}</strong>
                                                </span>
                                                @endif

                                        </div>

                                    </div>

                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <label class="required">Archivo:</label>

                                            <div class="custom-file">
                                                <input id="archivo" type="file" name="archivo" id="archivo"
                                                    class="custom-file-input {{ $errors->has('archivo') ? ' is-invalid' : '' }}"
                                                    accept="pdf , image/*" value="{{old('archivo', $pago->ruta_archivo)}}">

                                                <label for="archivo" id="archivo_txt"
                                                    class="custom-file-label selected {{ $errors->has('ruta') ? ' is-invalid' : '' }}">{{$pago->nombre_archivo}}</label>

                                                @if ($errors->has('archivo'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('archivo') }}</strong>
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
                                            value="{{old('observacion',$pago->observacion)}}">{{old('observacion',$pago->observacion)}}</textarea>
                                        @if ($errors->has('observacion'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('observacion') }}</strong>
                                        </span>
                                        @endif


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
                                    <a href="{{route('compras.pago.index',$orden->id)}}" id="btn_cancelar"
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
<link href="{{asset('Inspinia/css/plugins/select2/select2.min.css')}}" rel="stylesheet">
<!-- DataTable -->
<link href="{{asset('Inspinia/css/plugins/dataTables/datatables.min.css')}}" rel="stylesheet">
<link href="{{ asset('Inspinia/css/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css') }}"
    rel="stylesheet">
<link href="{{ asset('Inspinia/css/plugins/datapicker/datepicker3.css') }}" rel="stylesheet">
<link href="{{ asset('Inspinia/css/plugins/daterangepicker/daterangepicker-bs3.css') }}" rel="stylesheet">
<link href="{{ asset('Inspinia/css/plugins/select2/select2.min.css') }}" rel="stylesheet">
<style>
div.dataTables_wrapper div.dataTables_paginate ul.pagination {
    margin-left: 2px;
}

#table-bancos tr[data-href] {
    cursor: pointer;
}

#table-bancos tbody .fila_entidad.selected {
    /* color: #151515 !important;*/
    font-weight: 400;
    color: white !important;
    background-color: #1ab394 !important;
    /* background-color: #CFCFCF !important; */
}
</style>
@endpush
@push('scripts')
<!-- Select2 -->
<script src="{{asset('Inspinia/js/plugins/select2/select2.full.min.js')}}"></script>
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

<script>
//Select2
$(".select2_form").select2({
    placeholder: "SELECCIONAR",
    allowClear: true,
    width: '100%',
});

$('#fecha_pago .input-group.date').datepicker({
    todayBtn: "linked",
    keyboardNavigation: false,
    forceParse: false,
    autoclose: true,
    language: 'es',
    format: "dd/mm/yyyy",
    startDate: "today"
})

$('#tipo_cambio').keyup(function() {
    var val = $(this).val();
    if (isNaN(val)) {
        val = val.replace(/[^0-9\.]/g, '');
        if (val.split('.').length > 2)
            val = val.replace(/\.+$/, "");
    }
    $(this).val(val);
});

$('#monto').keyup(function() {
    var val = $(this).val();
    if (isNaN(val)) {
        val = val.replace(/[^0-9\.]/g, '');
        if (val.split('.').length > 2)
            val = val.replace(/\.+$/, "");
    }
    $(this).val(val);
});

// Error entidad bancaria
@if ($errors->has('id_entidad'))
    toastr.error("{{ $errors->first('id_entidad') }}", 'Error');    
@endif

$(document).ready(function() {

    // DataTables
    $('.dataTables-bancos').DataTable({
        "dom": 'Tftp',
        "bPaginate": true,
        "bFilter": true,
        "bInfo": true,
        "bAutoWidth": false,
        "language": {
            "url": "{{asset('Spanish.json')}}"
        },

        "columnDefs": [{
                "targets": [0],
                visible: false
            },
            {
                "targets": [1],
                className: "text-center",
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
                className: "text-center",
            },

        ],
        createdRow: function(row, data, dataIndex, cells) {
            $(row).addClass('fila_entidad');
            $(row).attr('data-href', "");
        },

    });
    obtenerTabla()
    buscarBanco()

    var table = $('.dataTables-bancos').DataTable();
    $('.dataTables-bancos tbody').on('click', 'tr', function() {
        if ($(this).hasClass('selected')) {
            $(this).removeClass('selected');
        } else {
            table.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
    });

    $('.dataTables-bancos').on('click', 'tbody td' , function() {
        var data = table.row(this).data();
        $('#id_entidad').val(data[0])
        $('#descripcion').val(data[1])
        $('#moneda').val(data[2])
        $('#cuenta').val(data[3])
        $('#cci').val(data[4])

    });

})


function obtenerTabla() {
    var t = $('.dataTables-bancos').DataTable();
    @foreach($orden->proveedor->bancos as $ban)
        @if($ban->estado == 'ACTIVO' )
            t.row.add([
                "{{$ban->id}}",
                "{{$ban->descripcion}}",
                "{{$ban->tipo_moneda}}",
                "{{$ban->num_cuenta}}",
                "{{$ban->cci}}",
            ]).draw(false);
        @endif
    @endforeach
}

$('.custom-file-input').on('change', function() {
    var fileInput = document.getElementById('archivo');
    var filePath = fileInput.value;
    var allowedExtensions = /(.jpg|.jpeg|.png|.pdf)$/i;

    if (allowedExtensions.exec(filePath)) {
        var userFile = document.getElementById('archivo');
        userFile.src = URL.createObjectURL(event.target.files[0]);
        let fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').addClass("selected").html(fileName);
    } else {
        toastr.error('Extensión inválida, formatos admitidos (.pdf .jpg . jpeg . png)', 'Error');
    }
});

$('#enviar_pago').submit(function(e) {
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
})

function buscarBanco() {
    var id = $('#id_entidad').val()
    var descripcion = $('#descripcion').val()
    var moneda = $('#moneda').val()
    var cuenta  =  $('#cuenta').val()
    var cci = $('#cci').val()

    var t = $('.dataTables-bancos').DataTable();
    var indice = ''
    var row2 = ''
    t.rows().data().each(function(row,el, index) {
        if (row[0] == id && 
            row[1] == descripcion &&
            row[2] == moneda &&
            row[3] == cuenta &&
            row[4] == cci 
        ) {
            console.log(descripcion)
            $(".dataTables-bancos tr:eq(" + (el+1) + ")").addClass('selected');
        }
    });





}

</script>



@endpush