@extends('layout') @section('content')
@include('almacenes.almacen.create')
@include('almacenes.almacen.edit')
@section('almacenes-active', 'active')
@section('almacen-active', 'active')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10 col-md-10">
       <h2  style="text-transform:uppercase"><b>Listado de Almacenes</b></h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('home') }}">Panel de Control</a>
            </li>
            <li class="breadcrumb-item active">
                <strong>Almacen</strong>
            </li>

        </ol>
    </div>
    <div class="col-lg-2 col-md-2">
        <a data-toggle="modal" data-target="#modal_crear_almacen" class="btn btn-block btn-w-m btn-primary m-t-md" href="#">
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
                    <table class="table dataTables-almacenes table-striped table-bordered table-hover"  style="text-transform:uppercase">
                    <thead>
                        <tr>
                            <th class="text-center"></th>
                            <th class="text-center">DESCRIPCION</th>
                            <th class="text-center">UBICACION</th>
                            <th class="text-center">CREADO</th>
                            <th class="text-center">ACTUALIZADO</th>
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

<style>
    .my-swal {
        z-index: 3000 !important;
    }
</style>
@endpush 

@push('scripts')
<!-- DataTable -->
<script src="{{asset('Inspinia/js/plugins/dataTables/datatables.min.js')}}"></script>
<script src="{{asset('Inspinia/js/plugins/dataTables/dataTables.bootstrap4.min.js')}}"></script>

<script>

    $(document).ready(function() {


        $('.dataTables-almacenes').DataTable({
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
            "bAutoWidth": false,
            "processing":true,
            "serverSide":true,
            "ajax": '{{ route("getRepository")}}',
            "columns": [
                //Tabla General
                {data: 'id', className:"text-center", "visible":false, name:'almacenes.descripcion'},
                {data: 'descripcion', className:"text-center", name:'almacenes.descripcion'},
                {data: 'ubicacion', className:"text-center", name:'almacenes.ubicacion'},
                {data: 'creado', className:"text-center", name: 'almacenes.created_at'},
                {data: 'actualizado', className:"text-center", name: 'almacenes.updated_at'},
                {
                    data: null,
                    className:"text-center",
                    name: 'almacenes.descripcion',
                    render: function (data) {
                        
                        return "<div class='btn-group'><button class='btn btn-warning btn-sm modificarDetalle' onclick='obtenerData("+data.id+")' type='button' title='Modificar'><i class='fa fa-edit'></i></button><a class='btn btn-danger btn-sm' href='#' onclick='eliminar("+data.id+")' title='Eliminar'><i class='fa fa-trash'></i></a></div>"
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

    function obtenerData($id) {
        var table = $('.dataTables-almacenes').DataTable();
        var data = table.rows().data();
        limpiarError()
        data.each(function (value, index) {
            if (value.id == $id) {
                $('#tabla_id_editar').val(value.id);
                $('#descripcion_editar').val(value.descripcion);
                $('#ubicacion_editar').val(value.ubicacion);
                $('#simbolo_editar').val(value.simbolo);
            }  
        });

        $('#modal_editar_almacen').modal('show');

        
    }

    //Old Modal Editar
    @if ($errors->has('ubicacion')  ||  $errors->has('descripcion') )
        $('#modal_editar_almacen').modal({ show: true });
    @endif

    function limpiarError() {
        $('#descripcion_editar').removeClass( "is-invalid" )
        $('#error-descripcion').text('')

        $('#ubicacion_editar').removeClass( "is-invalid" )
        $('#error-ubicacion').text('')
    }

    $('#modal_editar_almacen').on('hidden.bs.modal', function(e) { 
        limpiarError() 
    });

    //Old Modal Crear
    @if ($errors->has('ubicacion_guardar')  ||  $errors->has('descripcion_guardar') )
        $('#modal_crear_almacen').modal({ show: true });
    @endif

    function guardarError() {
        $('#descripcion_guardar').removeClass( "is-invalid" )
        $('#error-descripcion-guardar').text('')
        $('#ubicacion_guardar').removeClass( "is-invalid" )
        $('#error-ubicacion-guardar').text('')
    }

    $('#modal_crear_almacen').on('hidden.bs.modal', function(e) { 
        guardarError()
        $('#descripcion_guardar').val('')
        $('#ubicacion_guardar').val('')

    });

    function eliminar(id) {
        const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger',
                    },
                    buttonsStyling: false
                })
        Swal.fire({
            title: 'Opción Eliminar',
            text: "¿Seguro que desea eliminar registro?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: "#1ab394",
            confirmButtonText: 'Si, Confirmar',
            cancelButtonText: "No, Cancelar",
            }).then((result) => {
            if (result.isConfirmed) {
                //Ruta Eliminar
                var url_eliminar = '{{ route("almacenes.almacen.destroy", ":id")}}';
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