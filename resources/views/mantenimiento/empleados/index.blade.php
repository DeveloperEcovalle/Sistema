@extends('layout') @section('content')

@section('mantenimiento-active', 'active')
@section('empleados-active', 'active')

<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10 col-md-10">
        <h2 style="text-transform:uppercase;"><b>Gestión de Empleados</b></h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="#">Mantenimiento</a>
            </li>
            <li class="breadcrumb-item active">
                <strong>Empleados</strong>
            </li>
        </ol>
    </div>
    <div class="col-lg-2 col-md-2">
        <button id="btn_añadir_empleado" class="btn btn-block btn-w-m btn-primary m-t-md">
            <i class="fa fa-plus-square"></i> Añadir nuevo
        </button>
    </div>
</div>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox ">
                <div class="ibox-content">
                    <div class="table-responsive">
                        <table class="table dataTables-empleado table-striped table-bordered table-hover" style="text-transform:uppercase;">
                            <thead>
                            <tr>
                                <th class="text-center">ID</th>
                                <th class="text-center">DOCUMENTO</th>
                                <th class="text-center">APELLIDOS Y NOMBRES</th>
                                <th class="text-center">CARGO</th>
                                <th class="text-center">ESTADO</th>
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
        $('.dataTables-empleado').DataTable({
            "dom": '<"html5buttons"B>lTfgitp',
            "buttons": [
                {
                    extend:    'copyHtml5',
                    text:      '<i class="fa fa-files-o"></i> Copiar',
                    titleAttr: 'Copiar'
                },
                {
                    extend:    'csvHtml5',
                    text:      '<i class="fa fa-file-text-o"></i> Csv',
                    titleAttr: 'CSV'
                },
                {
                    extend:    'excelHtml5',
                    text:      '<i class="fa fa-file-excel-o"></i> Excel',
                    titleAttr: 'Excel',
                    title: 'Tablas Generales'
                },
                {
                    titleAttr: 'Imprimir',
                    extend: 'print',
                    text:      '<i class="fa fa-print"></i> Imprimir',
                    customize: function (win){
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
            "processing":true,
            "ajax": "{{ route('mantenimiento.empleado.getTable')}}",
            "columns": [
                {data: 'id', className:"text-center"},
                {data: 'documento', className:"text-center"},
                {data: 'apellidos_nombres', className:"text-left"},
                {data: 'cargo', className:"text-center"},
                {
                    data: 'estado',
                    className:"text-center",
                    render: function(data) {
                        return (data.estado === 1) ? "<div class='badge badge-success'>Activo</div>" : "<div class='badge badge-danger'>Inactivo</div>";
                    }
                },
                {
                    data: null,
                    className:"text-center",
                    render: function(data) {
                        var url_editar = '{{ route("mantenimiento.empleado.edit", ":id")}}';
                        url_editar = url_editar.replace(':id',data.id);

                        return "<div class='btn-group'>" +
                                    "<button type='button' class='btn btn-warning btn-sm mr-1' onclick='editarEmpleado("+url_editar+")'  title='Modificar'>" +
                                        "<i class='fa fa-edit'></i>" +
                                    "</button>" +
                                    (data.estado === 1) ?
                                    "<button type='button' class='btn btn-warning btn-sm mr-1 btn-desactivar-empleado' data-toggle='modal' data-target='#deactive'  title='Desactivar'>" +
                                        "<i class='fa fa-trash'></i>" +
                                    "</button>"
                                    :
                                    "<button type='button' class='btn btn-warning btn-sm mr-1 btn-desactivar-empleado' data-toggle='modal' data-target='#activate'  title='Desactivar'>" +
                                        "<i class='fa fa-check'></i>" +
                                    "</button>" +
                                "</div>"
                    }
                }

            ],
            "language": {
                "url": "{{asset('Spanish.json')}}"
            },
            "order": [[ 0, "desc" ]],
        });

        // Eventos
        $('#btn_añadir_empleado').on('click', añadirEmpleado);
    });

    //Controlar Error
    $.fn.DataTable.ext.errMode = 'throw';

    // Funciones de Eventos
    function añadirEmpleado() {
        window.location = "{{ route('mantenimiento.empleado.create')  }}";
    }

    function editarEmpleado(url) {
        window.location = url;
    }

</script>
@endpush
