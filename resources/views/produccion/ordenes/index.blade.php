@extends('layout') @section('content')
@section('produccion-active', 'active')
@section('ordenes_produccion-active', 'active')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10 col-md-10">
       <h2  style="text-transform:uppercase"><b>Listado de Ordenes de Produccion</b></h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{route('home')}}">Panel de Control</a>
            </li>
            <li class="breadcrumb-item active">
                <strong>Ordenes de Produccion</strong>
            </li>
        </ol>
    </div>
</div>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox ">
                <div class="ibox-content">
                    <div class="table-responsive">
                        <table class="table dataTables-ordenes table-striped table-bordered table-hover"
                        style="text-transform:uppercase">
                            <thead>
                                <tr>
                                    <th class="text-center">ID</th>
                                    <th class="text-center">Productos</th>
                                    <!-- <th class="text-center" style="display:none">Fecha Creacion</th> -->
                                    <th class="text-center">Programacion de Produccion</th>
                                    <th class="text-center">Cantidad Produccida</th>
                                    <th class="text-center">Fecha de Producción</th>
                                    <th class="text-center">Observacion</th>
                                    <th class="text-center">Estado</th>
                                    <th class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('produccion.ordenes.destroy')
@stop

@push('styles')
<!-- DataTable -->
<link href="{{asset('Inspinia/css/plugins/dataTables/datatables.min.css')}}" rel="stylesheet">
@endpush

@push('scripts')
<!-- DataTable -->
<script src="{{asset('Inspinia/js/plugins/dataTables/datatables.min.js')}}"></script>
<script src="{{asset('Inspinia/js/plugins/dataTables/dataTables.bootstrap4.min.js')}}"></script>

<script>
$(document).ready(function() {

    // DataTables
    $('.dataTables-ordenes').DataTable({
        "dom": '<"html5buttons"B>lTfgitp',
        "buttons": [{
                extend: 'excelHtml5',
                text: '<i class="fa fa-file-excel-o"></i> Excel',
                titleAttr: 'Excel',
                title: 'Tablas Generales'
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
        "processing": true,
        "ajax": "{{ route('produccion.aprobado.getOrdenes')}}",
        "columns": [
            //programacion_produccion INTERNA
            { data: 'id',className: "text-center", sWidth: '5%',},
            { data: 'producto',className: "text-left", sWidth: '30%'},
            { data: 'programacion_id',className: "text-center"},
            { data: 'cantidad_programada',className: "text-center"},
            { data: 'fecha_orden',className: "text-center"},
            { data: 'observacion',className: "text-center"},
            {
                data: null,
                className: "text-center",
                render: function(data) {
                    switch (data.estado) {
                        case "ANULADO":
                            return "<span class='badge badge-danger' d-block>" + data.estado +
                                "</span>";
                            break;
                        default:
                            return "<span class='badge badge-primary' d-block>" + data.estado +
                                "</span>";
                    }
                },
            },
            {
                data: null,
                className: "text-center",
                render: function(data) {
                    //Ruta Detalle
                    var url_detalle = '{{ route("produccion.orden.show", ":id")}}';
                    url_detalle = url_detalle.replace(':id', data.id);

                    if (data.estado == 'ANULADO') {
                        return "<div class='btn-group' style='text-transform:capitalize;'><button data-toggle='dropdown' class='btn btn-primary btn-sm  dropdown-toggle'><i class='fa fa-bars'></i></button><ul class='dropdown-menu'>" +
                            "<li><a class='dropdown-item' href='" + url_detalle +
                            "' title='Detalle'><b><i class='fa fa-eye'></i> Detalle</a></b></li>" +
                        "</ul></div>"
                    }else{
                        return "<div class='btn-group' style='text-transform:capitalize;'><button data-toggle='dropdown' class='btn btn-primary btn-sm  dropdown-toggle'><i class='fa fa-bars'></i></button><ul class='dropdown-menu'>" +
                            "<li><a class='dropdown-item' href='" + url_detalle +
                            "' title='Detalle'><b><i class='fa fa-eye'></i> Detalle</a></b></li>" +
                            "<li><a class='dropdown-item' onclick='eliminar(" + data.id +
                            ")' title='Eliminar' ><b><i class='fa fa-trash'></i> Eliminar</a></b></li>" +
                        "</ul></div>"

                    }


                }
            }
        ],
        "language": {
            "url": "{{asset('Spanish.json')}}"
        },
        "order": [],
    });

});


function eliminar(id) {
    $('#orden_id').val(id)
    $('#modal_observacion_anular').modal('show');
}

</script>
@endpush