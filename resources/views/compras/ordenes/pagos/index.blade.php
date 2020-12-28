@extends('layout') @section('content')

@section('compras-active', 'active')
@section('orden-compra-active', 'active')

<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10 col-md-10">
        <h2 style="text-transform:uppercase;"><b>Listado de Pagos de la Orden #{{$orden->id}}</b></h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{route('home')}}">Panel de Control</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{route('compras.orden.index')}}">Ordenes de Compra</a>
            </li>
            <li class="breadcrumb-item active">
                <strong>Pagos</strong>
            </li>
        </ol>
    </div>
    <div class="col-lg-2 col-md-2">
        <a class="btn btn-block btn-w-m btn-primary m-t-md" href="{{route('compras.pago.create',$orden->id)}}">
            <i class="fa fa-plus-square"></i> Añadir nuevo
        </a>
    </div>
</div>

<input type="hidden" id="id_orden" value="{{$orden->id}}" >
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox ">
                <div class="ibox-content">
                    <div class="table-responsive">
                        <table class="table dataTables-orden table-striped table-bordered table-hover"
                            style="text-transform:uppercase;">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th class="text-center">FECHA PAGO</th>
                                    <th class="text-center">ENTIDAD</th>
                                    <th class="text-center">MONTO</th>
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
    //Ruta Detalle
    var id = $('#id_orden').val()
    var url = '{{ route("getPay", ":id")}}';
    url = url.replace(':id',id);
    // DataTables
    $('.dataTables-orden').DataTable({
        "dom": '<"html5buttons"B>lTfgitp',
        "buttons": [{
                extend: 'excelHtml5',
                text: '<i class="fa fa-file-excel-o"></i> Excel',
                titleAttr: 'Excel',
                title: 'Pagos'
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
        "ajax": url,
        "columns": [
            //Pago
            {
                data: 'id',
                className: "text-center",
                visible: false
            },
            {
                data: 'fecha_pago',
                className: "text-center"
            },
            {
                data: 'entidad',
                className: "text-center"
            },
            {
                data: 'monto',
                className: "text-center"
            },
            {
                    data: null,
                    className:"text-center",
                    render: function (data) {
                        //Ruta Detalle
                        var url_detalle = "{{ route('compras.pago.show', [ 'pago' =>'id', 'orden'=>  $orden->id ]) }}".replace('id', data.id);

                        //Ruta Modificar
                        var url_edit = "{{ route('compras.pago.edit', [ 'pago' =>'id', 'orden'=>  $orden->id ]) }}".replace('id', data.id);

                        return "<div class='btn-group'><a class='btn btn-success btn-sm' href='"+url_detalle+"' title='Detalle'><i class='fa fa-eye'></i></a><a class='btn btn-warning btn-sm modificarDetalle' href='"+url_edit+"' title='Modificar'><i class='fa fa-edit'></i></a><a class='btn btn-danger btn-sm' href='#' onclick='eliminar("+data.id+")' title='Eliminar'><i class='fa fa-trash'></i></a></div>"
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

    tablaDatos = $('.dataTables-enviados').DataTable();

});

//Controlar Error
$.fn.DataTable.ext.errMode = 'throw';

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
            var url = "{{ route('compras.pago.destroy', [ 'pago' =>'id', 'orden'=>  $orden->id ]) }}".replace('id', id);
            $(location).attr('href', url);

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

function concretada(id) {
    Swal.fire({
        title: 'Opción Concretada',
        text: "¿Seguro que desea concretar orden de compra?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: "#1ab394",
        confirmButtonText: 'Si, Confirmar',
        cancelButtonText: "No, Cancelar",
    }).then((result) => {
        if (result.isConfirmed) {
            //Ruta Concretar
            var url_concretar = '{{ route("compras.orden.concretada", ":id")}}';
            url_concretar = url_concretar.replace(':id', id);
            $(location).attr('href', url_concretar);

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

function enviado(id) {

    $("#modal_listar_enviados").on("shown.bs.modal", function () { 
        $.get('/compras/ordenes/consultaEnvios/' + id, function(data) {
        if (data.length > 0) {
            enviado_usuario.style.display = "";
            no_enviado_usuario.style.display = "none";
            $('#correo_enviado').text(data[0].correo);
            $('#usuario_enviado').text(data[0].usuario);
        } else {
            enviado_usuario.style.display = "none";
            no_enviado_usuario.style.display = "";
        }
    });
    }).modal('show');

}
</script>
@endpush