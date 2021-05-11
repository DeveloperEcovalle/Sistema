@extends('layout') @section('content')

@section('almacenes-active', 'active')
@section('nota_ingreso-active', 'active')

<div class="row wrapper border-bottom white-bg page-heading">

    <div class="col-lg-12">
       <h2  style="text-transform:uppercase"><b>ACTUALIZAR NOTA DE INGRESO</b></h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{route('home')}}">Panel de Control</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{route('almacenes.nota_ingreso.index')}}">NOTA DE INGRESOS</a>
            </li>
            <li class="breadcrumb-item active">
                <strong>ACTUALIZAR</strong>
            </li>

        </ol>
    </div>



</div>


<div class="wrapper wrapper-content animated fadeInRight">

    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">

                <div class="ibox-content">

                    <form action="{{route('almacenes.nota_ingreso.update',$notaingreso->id)}}" method="POST" id="enviar_ingresos">
                        {{method_field('PUT')}}
                        {{csrf_field()}}
                            <div class="col-sm-12">
                                <h4 class=""><b>Nota de Ingresos</b></h4>
                                <div class="row">
                                    <div class="col-md-12">
                                        <p>Actualizar Nota de Ingresos :</p>
                                    </div>
                                </div>
                            	<div class="form-group row">
                                    
                                    
                                    <div class="col-sm-4"  id="fecha">
                                        <label>Fecha</label>
                                        <div class="input-group date">
                                            <span class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </span>
                                            <input type="text" id="fecha" name="fecha"
                                                class="form-control {{ $errors->has('fecha') ? ' is-invalid' : '' }}"
                                                value="{{old('fecha',getFechaFormato($notaingreso->fecha, 'Y-m-d'))}}"
                                                autocomplete="off" readonly required>
                                            @if ($errors->has('fecha'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('fecha') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="required">Origen</label>
                                        <select name="origen" id="origen" class="form-control {{ $errors->has('origen') ? ' is-invalid' : '' }}">
                                            <option value="">Seleccionar Origen</option>
                                            @foreach ($origenes as  $tabla)
                                                <option {{ $notaingreso->origen== $tabla->descripcion ? 'selected' : '' }}  value="{{$tabla->id}}">{{$tabla->descripcion}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="required">Destino</label>
                                        <select name="destino" id="destino" class="form-control {{ $errors->has('destino') ? ' is-invalid' : '' }}">
                                            <option value="">Seleccionar Destino</option>
                                            @foreach ($destinos as $tabla)
                                                <option {{ $notaingreso->destino == $tabla->descripcion ? 'selected' : '' }} value="{{$tabla->id}}">{{$tabla->descripcion}}</option>
                                            @endforeach
                                        </select>
                                    </div>
  

                                </div>

                            </div>

                   
                            <input type="hidden" id="notadetalle_tabla" name="notadetalle_tabla[]">
                            <input type="hidden" id="notadetalle" name="notadetalle" value="{{$detalle}}">
                       
                        <hr>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="panel panel-primary">
                                    <div class="panel-heading">
                                        <h4 class=""><b>Detalle de la Nota de Ingreso</b></h4>
                                    </div>
                                    <div class="panel-body">
                                        <div class="row">
                                        	<div class="col-md-2">
                                                <label class="col-form-label">Cantidad </label>
                                                <input type="number" id="cantidad" class="form-control">
                                                <div class="invalid-feedback"><b><span id="error-cantidad"></span></b>
                                                </div>
                                            </div> 
                                             <div class="col-md-5">
                                                <label class="col-form-label">Producto</label>
                                                <select name="producto" id="producto" class="form-control select2_form">
                                                    <option value=""></option>
                                                    @foreach ($productos as $producto)
                                                        <option  value="{{$producto->id}}" id="{{$producto->id}}">{{$producto->nombre}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="col-form-label">lote</label>
                                                <input type="text" name="lote" id="lote" class="form-control">
                                            </div>
                                          
                                            <div class="col-md-3">
                                                <label class="col-form-label">Fecha Vencimiento</label>
                                                <div class="input-group date">
                                                    <span class="input-group-addon">
                                                        <i class="fa fa-calendar"></i>
                                                    </span>
                                                    <input type="text" id="fechavencimiento" name="fechavencimiento"
                                                        class="form-control"
                                                         >
                                                </div>
                                            </div>
                                            
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="col-form-label" for="amount">&nbsp;</label>
                                                <a class="btn btn-block btn-warning enviar_detalle"
                                                    style='color:white;'> <i class="fa fa-plus"></i> AGREGAR</a>
                                            </div>
                                        </div>
                                        <hr>

                                        <div class="table-responsive">
                                            <table
                                                class="table dataTables-ingreso table-striped table-bordered table-hover"
                                                 onkeyup="return mayus(this)">
                                                <thead>
                                                    <tr>
                                                        <th></th>
                                                        <th class="text-center">ACCIONES</th>
                                                        <th class="text-center">Cantidad</th>
                    									<th class="text-center">Lote</th>
                    									<th class="text-center">Producto</th>
                                                        <th class="text-center">Fecha Vencimiento</th>
                    									
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

                        <div class="hr-line-dashed"></div>
                        <div class="form-group row">
                            <div class="col-md-6 text-left" style="color:#fcbc6c">
                                <i class="fa fa-exclamation-circle"></i> <small>Los campos marcados con asterisco
                                    (<label class="required"></label>) son obligatorios.</small>
                            </div>
                            <div class="col-md-6 text-right">
                                <a href="{{route('almacenes.ingreso_mercaderia.index')}}" id="btn_cancelar"
                                    class="btn btn-w-m btn-default">
                                    <i class="fa fa-arrow-left"></i> Regresar
                                </a>
                                <button type="submit" id="btn_grabar" class="btn btn-w-m btn-primary">
                                    <i class="fa fa-save"></i> Grabar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>

</div>
@include('almacenes.nota_ingresos.modal')
@stop

@push('styles')
<link href="{{ asset('Inspinia/css/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css') }}"
    rel="stylesheet">
<link href="{{ asset('Inspinia/css/plugins/datapicker/datepicker3.css') }}" rel="stylesheet">
<link href="{{ asset('Inspinia/css/plugins/daterangepicker/daterangepicker-bs3.css') }}" rel="stylesheet">
<link href="{{ asset('Inspinia/css/plugins/select2/select2.min.css') }}" rel="stylesheet">


<!-- DataTable -->
<link href="{{asset('Inspinia/css/plugins/dataTables/datatables.min.css')}}" rel="stylesheet">

@endpush

@push('scripts')
<!-- Data picker -->
<script src="{{ asset('Inspinia/js/plugins/datapicker/bootstrap-datepicker.js') }}"></script>
<!-- Date range use moment.js same as full calendar plugin -->
<script src="{{ asset('Inspinia/js/plugins/fullcalendar/moment.min.js') }}"></script>
<!-- Date range picker -->
<script src="{{ asset('Inspinia/js/plugins/daterangepicker/daterangepicker.js') }}"></script>
<!-- Select2 -->
<script src="{{ asset('Inspinia/js/plugins/select2/select2.full.min.js') }}"></script>

<!-- DataTable -->
<script src="{{asset('Inspinia/js/plugins/dataTables/datatables.min.js')}}"></script>
<script src="{{asset('Inspinia/js/plugins/dataTables/dataTables.bootstrap4.min.js')}}"></script>




<script>
//Select2

$(".select2_form").select2({
    placeholder: "SELECCIONAR",
    allowClear: true,
    width: '100%',
});

$('#fecha .input-group.date').datepicker({
    todayBtn: "linked",
    keyboardNavigation: false,
    forceParse: false,
    autoclose: true,
    language: 'es',
    format: "dd/mm/yyyy",
});
$('.input-group.date #fechavencimiento').datepicker({
    todayBtn: "linked",
    keyboardNavigation: false,
    forceParse: false,
    autoclose: true,
    language: 'es',
    format: "yyyy-mm-dd",
});
$('.modal_editar_detalle #fechavencimiento').datepicker({
    todayBtn: "linked",
    keyboardNavigation: false,
    forceParse: false,
    autoclose: true,
    language: 'es',
    format: "yyyy-mm-dd",
});




$('#enviar_ingreso_mercaderia').submit(function(e) {
    e.preventDefault();
    var correcto = validarFecha()

    if (correcto == false) {
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger',
            },
            buttonsStyling: false
        })

        Swal.fire({
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

})


$(document).ready(function() {

    // DataTables
    $('.dataTables-ingreso').DataTable({
        "dom": '<"html5buttons"B>lTfgitp',
        "buttons": [
            // {
            //     extend: 'excelHtml5',
            //     text: '<i class="fa fa-file-excel-o"></i> Excel',
            //     titleAttr: 'Excel',
            //     title: 'Detalle de Ingresos Mercaderia'
            // },
            // {
            //     titleAttr: 'Imprimir',
            //     extend: 'print',
            //     text: '<i class="fa fa-print"></i> Imprimir',
            //     customize: function(win) {
            //         $(win.document.body).addClass('white-bg');
            //         $(win.document.body).css('font-size', '10px');
            //         $(win.document.body).find('table')
            //             .addClass('compact')
            //             .css('font-size', 'inherit');
            //     }
            // }
        ],
        "bPaginate": true,
        "bLengthChange": true,
        "bFilter": true,
        "bInfo": true,
        "bAutoWidth": false,
        "language": {
            "url": "{{asset('Spanish.json')}}"
        },

        "columnDefs": [{
                "targets": [0],
                "visible": false,
                "searchable": false
            },
            {

                "targets": [1],
                className: "text-center",
                render: function(data, type, row) {
                    return "<div class='btn-group'>" +
                        "<a class='btn btn-warning btn-sm modificarDetalle btn-edit'  style='color:white;' title='Modificar'><i class='fa fa-edit'></i></a>" +
                        "<a class='btn btn-danger btn-sm' id='borrar_detalle' style='color:white;' title='Eliminar'><i class='fa fa-trash'></i></a>" +
                        "</div>";
                }
            },
            {
                "targets": [2],
            },
            {
                "targets": [3],
                className: "text-center",
            },
            {
                "targets": [4],
                className: "text-center",
            },
            {
                "targets": [5],
                className: "text-center",
            }

        ],

    });
    var detalle=JSON.parse($("#notadetalle").val());
    console.log(detalle);
            var t = $('.dataTables-ingreso').DataTable();
               for (var i = 0; i < detalle.length; i++) {
                t.row.add([
                        detalle[i].producto_id,'',
                        detalle[i].cantidad,
                        detalle[i].lote,
                        detalle[i].producto,
                        detalle[i].fechavencimiento
                    ]).draw(false);
                }

})

//Borrar registro de articulos
$(document).on('click', '#borrar_detalle', function(event) {

    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger',
        },
        buttonsStyling: false
    })

    Swal.fire({
        title: 'Opción Eliminar',
        text: "¿Seguro que desea eliminar Artículo?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: "#1ab394",
        confirmButtonText: 'Si, Confirmar',
        cancelButtonText: "No, Cancelar",
    }).then((result) => {
        if (result.isConfirmed) {
            var table = $('.dataTables-ingreso').DataTable();
            table.row($(this).parents('tr')).remove().draw();
            console.log("f");

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
});
$(".enviar_detalle").click(function() {

    var enviar = false;
    var cantidad= $('#cantidad').val();
                    var lote= $('#lote').val();
                    var producto= $('#producto').val();
                    var fechavencimiento= $('#fechavencimiento').val();
                    
    if(cantidad.length==0|| lote.length==0 || producto.length==0|| fechavencimiento.length==0)
    {
        toastr.error('Ingrese datos', 'Error');
        enviar=true;
    }
   
    
    if (enviar != true) {
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger',
            },
            buttonsStyling: false
        })

        Swal.fire({
            title: 'Opción Agregar',
            text: "¿Seguro que desea agregar Artículo?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: "#1ab394",
            confirmButtonText: 'Si, Confirmar',
            cancelButtonText: "No, Cancelar",
        }).then((result) => {
            if (result.isConfirmed) {

                var detalle = {
                	cantidad: $('#cantidad').val(),
                    lote:$('#lote').val(),
                    producto:$( "#producto option:selected" ).text(),
                    fechavencimiento: $('#fechavencimiento').val(),
                    producto_id:$( "#producto" ).val()
                   
                }
                agregarTabla(detalle);

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
})

$(document).on('click', '.btn-edit', function(event) {
            var table = $('.dataTables-ingreso').DataTable();
            var data = table.row($(this).parents('tr')).data();
            $('#modal_editar_detalle #indice').val(table.row($(this).parents('tr')).index());
            $('#modal_editar_detalle #lote').val(data[3]);
            $('#modal_editar_detalle #cantidad').val(data[2]);
            $('#modal_editar_detalle #prod_id').val(data[0]);
            $('#modal_editar_detalle #fechavencimiento').val(data[5]);
            $('#modal_editar_detalle').modal('show');         
            $("#modal_editar_detalle #producto").val(data[0]).trigger('change');
        
            });




function agregarTabla($detalle) {
    var t = $('.dataTables-ingreso').DataTable();
    t.row.add([
        $detalle.producto_id,'',
    	$detalle.cantidad,
    	$detalle.lote,
    	$detalle.producto,
        $detalle.fechavencimiento
    ]).draw(false);
    cargarDetalle()
}



function cargarDetalle() {
    var notadetalle = [];
    var table = $('.dataTables-ingreso').DataTable();
    var data = table.rows().data();
    data.each(function(value, index) {
        let fila = {
            cantidad: value[2],
            lote: value[3],
            producto_id: value[0],
            fechavencimiento: value[5]
        };

        notadetalle.push(fila);

    });
    $('#notadetalle_tabla').val(JSON.stringify(notadetalle))
}



</script>
@endpush