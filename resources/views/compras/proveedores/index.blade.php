@extends('layout') @section('content')

@section('compras-active', 'active')
@section('proveedor-active', 'active')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10 col-md-10">
       <h2  style="text-transform:uppercase"><b>LISTADO DE PROVEEDORES</b></h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{route('home')}}">Panel de Control</a>
            </li>
            <li class="breadcrumb-item active">
                <strong>Proveedores</strong>
            </li>

        </ol>
    </div>
    <div class="col-lg-2 col-md-2">
        <a class="btn btn-block btn-w-m btn-primary m-t-md" href="{{route('compras.proveedor.create')}}">
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
                    <table class="table dataTables-proveedores table-striped table-bordered table-hover"  style="text-transform:uppercase">
                    <thead>
                        <tr>
                            <th class="text-center"></th>
                            <th class="text-center">RUC</th>
                            <th class="text-center">TIPO</th>
                            <th class="text-center">DNI</th>
                            <th class="text-center">PROVEEDORES</th>
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
        $('buttons-html5').removeClass('.btn-default');
        $('#table_empresas_wrapper').removeClass('');
        $('.dataTables-proveedores').DataTable({
            "dom": '<"html5buttons"B>lTfgitp',
            "buttons": [
   
                {
                    extend:    'excelHtml5',
                    text:      '<i class="fa fa-file-excel-o"></i> Excel',
                    titleAttr: 'Excel',
                    title: 'Proveedores'
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
            "serverSide":true,
            "ajax": '{{ route("getProvider")}}',
            "columns": [
                //Proveedor
                {data: 'id', className:"text-center", visible:false},
                {data: 'ruc', className:"text-center"},
                {data: 'tipo', className:"text-center"},
                {data: 'dni', className:"text-center"},
                {data: 'descripcion'},
                {
                    data: null,
                    className:"text-center",
                    render: function (data) {
                        //Ruta Detalle
                        var url_detalle = '{{ route("compras.proveedor.show", ":id")}}';
                        url_detalle = url_detalle.replace(':id',data.id);

                        //Ruta Modificar
                        var url_edit = '{{ route("compras.proveedor.edit", ":id")}}';
                        url_edit = url_edit.replace(':id',data.id);

                        return "<div class='btn-group'><a class='btn btn-success btn-sm' href='"+url_detalle+"' title='Detalle'><i class='fa fa-eye'></i></a><a class='btn btn-warning btn-sm modificarDetalle' href='"+url_edit+"' title='Modificar'><i class='fa fa-edit'></i></a><a class='btn btn-danger btn-sm' href='#' onclick='eliminar("+data.id+")' title='Eliminar'><i class='fa fa-trash'></i></a></div>"
                    }
                }

            ],
            "language": {
                        "url": "{{asset('Spanish.json')}}"
            },
            "order": [[ 0, "desc" ]],

        });

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
                var url_eliminar = '{{ route("compras.proveedor.destroy", ":id")}}';
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