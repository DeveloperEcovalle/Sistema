@extends('layout') @section('content')

@section('ventas-active', 'active')
@section('documentos-active', 'active')

<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10 col-md-10">
       <h2  style="text-transform:uppercase"><b>Listado de Documentos de Venta</b></h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{route('home')}}">Panel de Control</a>
            </li>
            <li class="breadcrumb-item active">
                <strong>Documentos de Ventas</strong>
            </li>
        </ol>
    </div>
    <div class="col-lg-2 col-md-2">
        <a class="btn btn-block btn-w-m btn-primary m-t-md" href="{{route('ventas.documento.create')}}">
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
                        <table class="table dataTables-orden table-striped table-bordered table-hover"
                        style="text-transform:uppercase">
                            <thead>
                                <tr>
                                    
                                    <th colspan="3" class="text-center"></th>
                                    <th colspan="4" class="text-center">DOCUMENTO DE VENTA</th>
                                    <th colspan="2" class="text-center">FORMAS DE PAGO</th>
                                    <th colspan="3" class="text-center"></th>

                                </tr>
                                <tr>
                                    <th style="display:none;"></th>
                                    <th style="display:none;"></th>
                                    <th class="text-center">C.O</th>
                                    <th class="text-center">FEC.DOCUMENTO</th>
                                    <th class="text-center">TIPO</th>
                                    <th class="text-center">CLIENTE</th>
                                    <th class="text-center">MONTO</th>
                                    <th class="text-center">TRANSFERENCIA</th>
                                    <th class="text-center">OTROS</th>
                                    <th class="text-center">SALDO</th>
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
    $('.dataTables-orden').DataTable({
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
        "ajax": "{{ route('ventas.getDocument')}}",
        "columns": [
            //DOCUMENTO DE VENTA
            {
                data: 'id',
                className: "text-center",
                visible: false
            },
            {
                data: 'cotizacion_venta',
                className: "text-center",
                visible: false
            },

            {
                data: null,
                className: "text-center",
                render: function(data) {
                    if (data.cotizacion_venta) {
                        return "<input type='checkbox' disabled checked>"
                    }else{
                        return "<input type='checkbox' disabled>"
                    }
                }
                
            },
            {
                data: 'fecha_documento',
                className: "text-center"
            },
            {
                data: 'tipo_venta',
                className: "text-center",
            },
            {
                data: 'cliente',
                className: "text-left"
            },



            {
                data: 'total',
                className: "text-center"
            },
            {
                data: 'transferencia',
                className: "text-center"
            },

            {
                data: 'otros',
                className: "text-center"
            },
            {
                data: 'saldo',
                className: "text-center"
            },


            {
                data: null,
                className: "text-center",
                render: function(data) {
                    switch (data.estado) {
                        case "PENDIENTE":
                            return "<span class='badge badge-warning' d-block>" + data.estado +
                                "</span>";
                            break;
                        case "PAGADA":
                            return "<span class='badge badge-danger' d-block>" + data.estado +
                                "</span>";
                            break;
                        case "ADELANTO":
                            return "<span class='badge badge-success' d-block>" + data.estado +
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
                    //Ruta Detalle
                    var url_detalle = '{{ route("ventas.documento.show", ":id")}}';
                    url_detalle = url_detalle.replace(':id', data.id);

                    //Ruta Modificar
                    var url_editar = '{{ route("ventas.documento.edit", ":id")}}';
                    url_editar = url_editar.replace(':id', data.id);

                    return "<div class='btn-group' style='text-transform:capitalize;'><button data-toggle='dropdown' class='btn btn-primary btn-sm  dropdown-toggle'><i class='fa fa-bars'></i></button><ul class='dropdown-menu'>" +

                        "<li><a class='dropdown-item' href='" + url_editar +
                        "' title='Modificar' ><b><i class='fa fa-edit'></i> Modificar</a></b></li>" +
                        "<li><a class='dropdown-item' href='" + url_detalle +
                        "' title='Detalle'><b><i class='fa fa-eye'></i> Detalle</a></b></li>" +
                        "<li><a class='dropdown-item' onclick='eliminar(" + data.id +
                        ")' title='Eliminar'><b><i class='fa fa-trash'></i> Eliminar</a></b></li>" +
                        "<li class='dropdown-divider'></li>" +
                        "<li><a class='dropdown-item' onclick='pagar(" +data.orden_compra+","+data.id+","+data.tipo_pago+  ")'  title='Pagar'><b><i class='fa fa-money'></i> Pagar</a></b></li>"
                        
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
            var url_eliminar = '{{ route("compras.documento.destroy", ":id")}}';
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

function pagar(orden,id,tipo) {
    // var tipo = this.tipo;
    console.log(tipo)

    if (orden) {
        toastr.error('El tipo de pago de este documento de compra fue realizada (transferencia).', 'Error');
    }else{

        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger',
            },
            buttonsStyling: false
        })

        Swal.fire({
            title: 'Opción Formas de Pago',
            text: "Elija la forma de pago",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: "#1ab394",
            confirmButtonText: 'Transferencia',
            cancelButtonText: "Otros",
        }).then((result) => {
            if (result.isConfirmed) {


                if (tipo == null || tipo == 1  ) {
                    
                    var url = '{{ route("compras.documentos.transferencia.pago.index", ":id")}}';
                    url = url.replace(':id', id);
                    $(location).attr('href', url);

                }else{
                    Swal.fire({
                            customClass: {
                                container: 'my-swal'
                            },
                            title: 'Error',
                            text: "Existe un tipo de pago, desea anular y crear un tipo de pago nuevo",
                            icon: 'error',
                            showCancelButton: true,
                            confirmButtonColor: "#1ab394",
                            confirmButtonText: 'Si, Confirmar',
                            cancelButtonText: "No, Cancelar",
                            }).then((result) => {
                            if (result.isConfirmed) {
                                var url = '{{ route("compras.documento.tipo_pago.existente", ":id")}}';
                                url = url.replace(':id', id);
                                $(location).attr('href', url);
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
            
            
            } else if (
                /* Read more about handling dismissals below */
                result.dismiss === Swal.DismissReason.cancel
            ) {


                if (tipo  == null || tipo == 0  ) {
                    
                    var url = '{{ route("compras.documentos.pago.index", ":id")}}';
                    url = url.replace(':id', id);
                    $(location).attr('href', url);

                }else{
                    
                    Swal.fire({
                            customClass: {
                                container: 'my-swal'
                            },
                            title: 'Error',
                            text: "Existe un tipo de pago, desea anular y crear un tipo de pago nuevo",
                            icon: 'error',
                            showCancelButton: true,
                            confirmButtonColor: "#1ab394",
                            confirmButtonText: 'Si, Confirmar',
                            cancelButtonText: "No, Cancelar",
                            }).then((result) => {
                            if (result.isConfirmed) {
                                var url = '{{ route("compras.documento.tipo_pago.existente", ":id")}}';
                                url = url.replace(':id', id);
                                $(location).attr('href', url);
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
            
            }
        })


    }

}




</script>
@endpush