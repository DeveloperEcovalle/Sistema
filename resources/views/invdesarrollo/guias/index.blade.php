@extends('layout') @section('content')

@section('invdesarrollo-active', 'active')
@section('guia-active', 'active')

<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10 col-md-10">
       <h2  style="text-transform:uppercase"><b>Listado de Guias Internas</b></h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{route('home')}}">Panel de Control</a>
            </li>
            <li class="breadcrumb-item active">
                <strong>Guias Internas</strong>
            </li>
        </ol>
    </div>
    <div class="col-lg-2 col-md-2">
        <a class="btn btn-block btn-w-m btn-primary m-t-md" href="{{route('invdesarrollo.guia.create')}}">
            <i class="fa fa-plus-square"></i> Añadir nuevo
        </a>
    </div>
</div>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox ">
                <div class="ibox-content">
                    <div class="table-responsive">
                        <table class="table dataTables-guia table-striped table-bordered table-hover"
                        style="text-transform:uppercase">
                            <thead>
                                <tr>
                                    
                                    <th class="text-center">Producto</th>
                                    <th class="text-center">Unidades A Producir</th>
                                    <th class="text-center">Area Responsable1</th>
                                    <th class="text-center">Area Responsable2</th>
                                    <th class="text-center">Fecha Emisión</th>
                                    <th class="text-center">Observacion</th>
                                    <th class="text-center">Estado</th>
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
    $('.dataTables-guia').DataTable({
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
        "ajax": "{{ route('getGuia')}}",
        "columns": [
            //GUIA INTERNA
            //{ data: 'id',className: "text-center"},
            { data: 'producto',className: "text-center"},
            { data: 'unidades_a_producir',className: "text-center"},
            { data: 'area_responsable1',className: "text-center"},
            { data: 'area_responsable2',className: "text-center"},
            { data: 'fecha',className: "text-center"},
            { data: 'observacion',className: "text-center"},
            { data: 'estado',className: "text-center"},

            {
                data: null,
                className: "text-center",
                render: function(data) {
                    //Ruta Detalle
                    var url_detalle = '{{ route("invdesarrollo.guia.show", ":id")}}';
                    url_detalle = url_detalle.replace(':id', data.id);

                    //Ruta Modificar
                    var url_editar = '{{ route("invdesarrollo.guia.edit", ":id")}}';
                    url_editar = url_editar.replace(':id', data.id);

                    return "<div class='btn-group' style='text-transform:capitalize;'><button data-toggle='dropdown' class='btn btn-primary btn-sm  dropdown-toggle'><i class='fa fa-bars'></i></button><ul class='dropdown-menu'>" +

                        "<li><a class='dropdown-item' href='" + url_editar +
                        "' title='Modificar' ><b><i class='fa fa-edit'></i> Modificar</a></b></li>" +
                        "<li><a class='dropdown-item' href='" + url_detalle +
                        "' title='Detalle'><b><i class='fa fa-eye'></i> Detalle</a></b></li>" +
                        "<li><a class='dropdown-item' onclick='eliminar(" + data.id +
                        ")' title='Eliminar'><b><i class='fa fa-trash'></i> Eliminar</a></b></li>" +
                        "<li class='dropdown-divider'></li>" +
                    "</ul></div>"
                }
            }

        ],
        "language": {
            "url": "{{asset('Spanish.json')}}"
        },
        "order": [
            [0, "desc"]
        ],
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


function eliminar(id) {
    Swal.fire({
        title: 'Opción Eliminar',
        text: "¿Seguro que desea guardar cambios?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: "#1ab394",
        confirmButtonText: 'Si, Confirmar',
        cancelButtonText: "No, Cancelar",
    }).then((result) => {
        if (result.isConfirmed) {
            //Ruta Eliminar
            var url_eliminar = '{{ route("invdesarrollo.guia.destroy", ":id")}}';
            url_eliminar = url_eliminar.replace(':id', id);
            $(location).attr('href', url_eliminar);

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