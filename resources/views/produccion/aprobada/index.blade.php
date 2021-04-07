@extends('layout') @section('content')
@section('produccion-active', 'active')
@section('produccion_aprobada-active', 'active')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10 col-md-10">
       <h2  style="text-transform:uppercase"><b>Listado de Producciones Aprobadas</b></h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{route('home')}}">Panel de Control</a>
            </li>
            <li class="breadcrumb-item active">
                <strong>Producciones Aprobadas</strong>
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
                        <table class="table dataTables-aprobadas table-striped table-bordered table-hover"
                        style="text-transform:uppercase">
                            <thead>
                                <tr>
                                    <th class="text-center">ID</th>
                                    <th class="text-center">Productos</th>
                                    <!-- <th class="text-center" style="display:none">Fecha Creacion</th> -->
                                    <th class="text-center">Cantidad Producir</th>
                                    <th class="text-center">Fecha Producción</th>
                                    <th class="text-center">Fecha Termino</th>
                                    <!-- <th class="text-center">Cantidad Programada</th> -->
                                    <th class="text-center">Observacion</th>
                                    <!-- <th class="text-center" style="display:none">Usuario Id</th> -->
                                    <th class="text-center">Estado</th>
                                    <th class="text-center">produccion</th>
                                    <th class="text-center">ACCIONES</th>
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
    $('.dataTables-aprobadas').DataTable({
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
        "ajax": "{{ route('produccion.programacion_produccion.getApproved')}}",
        "columns": [
            //programacion_produccion INTERNA
            { data: 'id',className: "text-center", sWidth: '5%',},
            { data: 'producto',className: "text-left", sWidth: '30%'},
            { data: 'cantidad_programada',className: "text-center"},
            { data: 'fecha_programada',className: "text-center"},
            { data: 'fecha_termino',className: "text-center"},
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
                        case "PRODUCCION":
                            return "<span class='badge badge-primary' d-block>" + data.estado +
                                "</span>";
                            break;
                        default:
                            return "<span class='badge badge-success' d-block>" + data.estado +
                                "</span>";
                    }
                },
            },

            {
                data: null,
                className: "text-center",
                render: function(data) {
                    switch (data.produccion) {
                        case "0":
                            return "<span class='badge badge-warning' d-block> PENDIENTE </span>";
                            break;
                        case "1":
                            return "<span class='badge badge-success' d-block> ATENDIDA </span>";
                            break;
                        default:
                            return "<span class='badge badge-danger' d-block> ANULADO </span>";
                    }
                },
            },
            {
                data: null,
                className: "text-center",
                render: function(data) {
                    //Ruta Detalle
                    var url_detalle = '{{ route("produccion.programacion_produccion.show", ":id")}}';
                    url_detalle = url_detalle.replace(':id', data.id);

                    //Ruta Modificar
                    var url_editar = '{{ route("produccion.programacion_produccion.edit", ":id")}}';
                    url_editar = url_editar.replace(':id', data.id);

                    if (data.produccion == '0') {
                        return "<div class='btn-group' style='text-transform:capitalize;'><button data-toggle='dropdown' class='btn btn-primary btn-sm  dropdown-toggle'><i class='fa fa-bars'></i></button><ul class='dropdown-menu'>" +                        
                        "<li><a class='dropdown-item' onclick='orden(" + data.id +
                        ")' title='Generar una orden de produccion' ><b><i class='fa fa-line-chart'></i> Orden de produccion</a></b></li>" +
                        "<li><a class='dropdown-item' href='" + url_detalle +
                        "' title='Detalle'><b><i class='fa fa-eye'></i> Detalle</a></b></li>" +
                        "</ul></div>"
                    }else{
                        return "<div class='btn-group' style='text-transform:capitalize;'><button data-toggle='dropdown' class='btn btn-primary btn-sm  dropdown-toggle'><i class='fa fa-bars'></i></button><ul class='dropdown-menu'>" +
                        "<li><a class='dropdown-item' href='" + url_detalle +
                        "' title='Detalle'><b><i class='fa fa-eye'></i> Detalle</a></b></li>" +
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

//Controlar Error
$.fn.DataTable.ext.errMode = 'throw';

const swalWithBootstrapButtons = Swal.mixin({
    customClass: {
        confirmButton: 'btn btn-success',
        cancelButton: 'btn btn-danger',
    },
    buttonsStyling: false
})


function orden(id){
    Swal.fire({
        customClass: {
            container: 'my-swal'
        },
        title: 'Opción Orden de Producción',
        text: "¿Seguro que desea generar una orden de producción?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: "#1ab394",
        confirmButtonText: 'Si, Confirmar',
        cancelButtonText: "No, Cancelar",
    }).then((result) => {
        if (result.isConfirmed) {
            //Ruta a orden
            var url = '{{ route("produccion.orden.create", ":id")}}';
            url = url.replace(':id', id);
            $(location).attr('href',url);
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
</script>
@endpush