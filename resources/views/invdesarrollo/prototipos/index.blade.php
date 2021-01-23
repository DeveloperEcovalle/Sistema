@extends('layout') @section('content')
@include('invdesarrollo.prototipos.create')
@include('invdesarrollo.prototipos.edit')
@section('invdesarrollo-active', 'active')
@section('prototipo-active', 'active')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10 col-md-10">
       <h2  style="text-transform:uppercase"><b>Listado de Prototipos</b></h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('home') }}">Panel de Control</a>
            </li>
            <li class="breadcrumb-item active">
                <strong>Prototipos</strong>
            </li>

        </ol>
    </div>
    <div class="col-lg-2 col-md-2">
        <a data-toggle="modal" data-target="#modal_crear_prototipo" class="btn btn-block btn-w-m btn-primary m-t-md" href="#">
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
                    <table class="table dataTables-prototipos table-striped table-bordered table-hover"  style="text-transform:uppercase">
                    <thead>
                        <tr>
                            
							<th class="text-center">Id</th>
							<th class="text-center">Producto</th>
							<th class="text-center">Fecha Registro</th>
							<th class="text-center">Fecha Inicio</th>
							<th class="text-center">Fecha Fin</th>
							<th class="text-center">Linea Caja Texto Registrar</th>
							<th class="text-center">Imagen</th>
							<th class="text-center">Archivo Word</th>
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

        $('.dataTables-prototipos').DataTable({
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
            "ajax": '{{ route("getPrototipo") }}',
            "columns": [
                //Tabla General
                //{data: 'producto_id', className:"text-center", "visible":false, name:'prototipos.producto_id'},
				{data: 'id', className:"text-center", name:'prototipos.id'},
				{data: 'producto', className:"text-center", name:'prototipos.producto'},
				{data: 'fecha_registro', className:"text-center", name:'prototipos.fecha_registro'},
				{data: 'fecha_inicio', className:"text-center", name:'prototipos.fecha_inicio'},
				{data: 'fecha_fin', className:"text-center", name:'prototipos.fecha_fin'},
				{data: 'linea_caja_texto_registrar', className:"text-center", name:'prototipos.linea_caja_texto_registrar'},
                {data: 'imagen', className:"text-center", name:'prototipos.imagen'},
                // {
                //     data: null,
                //     className:"text-center",
                //     name: 'prototipos.id',
                //     render: function (data) {
                        
                //         return "<div class='btn-group'><img class='imagen' id='ruta_imagen' src='{{Storage::url('public/prototipos/ce6UQd8pQVDyrA86lLP2ZuKtsLmjU4kgC6FMZM9d.jpg')}}' height='100px' width='100px'</div>"
                //     }
                // },
                {data: 'archivo_word', className:"text-center", name:'prototipos.archivo_word'},
                {data: 'estado', className:"text-center", name:'prototipos.estado'},

                {
                    data: null,
                    className:"text-center",
                    name: 'prototipos.id',
                    render: function (data) {
                        
                        return "<div class='btn-group'><button class='btn btn-warning btn-sm modificarDetalle' onclick='obtenerData("+data.id+")' type='button' title='Modificar'><i class='fa fa-edit'></i></button><a class='btn btn-danger btn-sm' href='#' onclick='eliminar("+data.id+")' title='Eliminar'><i class='fa fa-trash'></i></a></div>"
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

    function obtenerData($id) {
        console.log($id);
        var table = $('.dataTables-prototipos').DataTable();
        console.log(table);
        var data = table.rows().data();
        console.log(data);
        limpiarError();

        data.each(function (value, index) {
            if (value.id == $id) {
				$('#id_editar').val(value.id);
				$('#producto_editar').val(value.producto);
				$('#fecha_registro_editar').val(value.fecha_registro);
				$('#fecha_inicio_editar').val(value.fecha_inicio);
				$('#fecha_fin_editar').val(value.fecha_fin);
				$('#linea_caja_texto_registrar_editar').val(value.linea_caja_texto_registrar);
                console.log(value.ruta_imagen);
                //document.getElementById("ruta_imagen_editar").src=value.ruta_imagen;
                //$('#imagen_editar').val(value.imagen);
                
                //$('#ruta_imagen_editar').attr("src",Storage::url(value.ruta_imagen));
                //$('#ruta_imagen_editar').val(value.ruta_imagen);
                //$('#archivo_word_editar').val(value.archivo_word);
            }  
        });
        $('#modal_editar_prototipo').modal('show');

    }

    //Old Modal Editar
    @if ($errors->has('producto')  ||  $errors->has('fecha_inicio') )
        $('#modal_editar_prototipo').modal({ show: true });
    @endif

    function limpiarError() {
        $('#descripcion').removeClass( "is-invalid" )
        $('#error-descripcion').text('')

        $('#ubicacion').removeClass( "is-invalid" )
        $('#error-ubicacion').text('')
    }

    $('#modal_editar_prototipo').on('hidden.bs.modal', function(e) { 
        limpiarError() 
    });

    //Old Modal Crear
    @if ($errors->has('ubicacion')  ||  $errors->has('descripcion') )
        $('#modal_crear_prototipo').modal({ show: true });
    @endif

    function guardarError() {
        $('#descripcion').removeClass( "is-invalid" )
        $('#error-descripcion-guardar').text('')
        $('#ubicacion').removeClass( "is-invalid" )
        $('#error-ubicacion-guardar').text('')
    }

    $('#modal_crear_prototipo').on('hidden.bs.modal', function(e) { 
        guardarError()
        $('#descripcion').val('')
        $('#ubicacion').val('')

    });

    function eliminar(id) {
        
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
                var url_eliminar = '{{ route("invdesarrollo.prototipo.destroy", ":id")}}';
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

    $('#editar_prototipo').submit(function(e){
        e.preventDefault();
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                container: 'my-swal',
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger',
            },
            buttonsStyling: false
            
        })

        Swal.fire({
            customClass: {
                container: 'my-swal'
            },
            title: 'Opción Modificar',
            text: "¿Seguro que desea modificar los cambios?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: "#1ab394",
            confirmButtonText: 'Si, Confirmar',
            cancelButtonText: "No, Cancelar",
            }).then((result) => {
            if (result.isConfirmed) {
                    this.submit();
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
    })

    $('#crear_prototipo').submit(function(e){
        e.preventDefault();
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                container: 'my-swal',
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger',
            },
            buttonsStyling: false
        })

        Swal.fire({
            customClass: {
                container: 'my-swal'
            },
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
    })



</script>
@endpush