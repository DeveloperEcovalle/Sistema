@extends('layout') @section('content')

@section('mantenimiento-active', 'active')
@section('empleados-active', 'active')

<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10 col-md-10">
       <h2  style="text-transform:uppercase"><b>Mantenimiento de Colaboradores</b></h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{route('home')}}">Panel de Control</a>
            </li>
            <li class="breadcrumb-item active">
                <strong>Colaboradores</strong>
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
                        <table class="table dataTables-empleado table-striped table-bordered table-hover"  style="text-transform:uppercase">
                            <thead>
                            <tr>
                                <th class="text-center">DOCUMENTO</th>
                                <th class="text-center">APELLIDOS Y NOMBRES</th>
                                <th class="text-center">T. MÓVIL</th>
                                <th class="text-center">ÁREA</th>
                                <th class="text-center">CARGO</th>
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
            "serverSide":true,
            "bAutoWidth": false,
            "processing":true,
            "ajax": "{{ route('mantenimiento.colaborador.getTable')}}",
            "columns": [
                {data: 'documento', className:"text-center"},
                {data: 'apellidos_nombres', className:"text-left"},
                {data: 'telefono_movil', className:"text-center"},
                {data: 'area', className:"text-center"},
                {data: 'cargo', className:"text-center"},
                {
                    data: null,
                    className:"text-center",
                    render: function(data) {
                        //Ruta Detalle
                        var url_detalle = '{{ route("mantenimiento.colaborador.show", ":id")}}';
                        url_detalle = url_detalle.replace(':id',data.id);

                        //Ruta Modificar
                        var url_editar = '{{ route("mantenimiento.colaborador.edit", ":id")}}';
                        url_editar = url_editar.replace(':id',data.id);

                        return "<div class='btn-group'>" +
                                    "<a class='btn btn-success btn-sm' href='"+url_detalle+"' title='Detalle'><i class='fa fa-eye'></i></a>" +
                                    "<a class='btn btn-warning btn-sm modificarDetalle' href='"+url_editar+"' title='Modificar'><i class='fa fa-edit'></i></a>" +
                                    "<a class='btn btn-danger btn-sm' href='#' onclick='eliminar("+data.id+")' title='Eliminar'><i class='fa fa-trash'></i></a>" +
                                "</div>";
                    }
                }

            ],
            "language": {
                "url": "{{asset('Spanish.json')}}"
            },
            "order": [],
        });

        // Eventos
        $('#btn_añadir_empleado').on('click', añadirEmpleado);
    });

    //Controlar Error
    $.fn.DataTable.ext.errMode = 'throw';

    //Modal Eliminar
    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger',
        },
        buttonsStyling: false
    })

    // Funciones de Eventos
    function añadirEmpleado() {
        window.location = "{{ route('mantenimiento.colaborador.create')  }}";
    }

    function editarEmpleado(url) {
        window.location = url;
    }

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
                var url_eliminar = '{{ route("mantenimiento.colaborador.destroy", ":id")}}';
                url_eliminar = url_eliminar.replace(':id',id);
                $(location).attr('href',url_eliminar);

            }else if (
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
