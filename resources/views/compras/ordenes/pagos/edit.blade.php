@extends('layout') @section('content')

@section('compras-active', 'active')
@section('orden-compra-active', 'active')

<div class="row wrapper border-bottom white-bg page-heading">

    <div class="col-lg-12">
        <h2 style="text-transform:uppercase;"><b>MODIFICAR PAGO DE LA ORDEN DE COMPRA #{{$orden->id}}</b></h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{route('home')}}">Panel de Control</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{route('compras.orden.index')}}">Ordenes de Compra</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{route('compras.pago.index', $orden->id)}}">Pagos</a>
            </li>
            <li class="breadcrumb-item active">
                <strong>Modificar</strong>
            </li>

        </ol>
    </div>



</div>


<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox ">
                <div class="ibox-content">
                    <form action="{{route('compras.pago.update',$pago[0]->id_pago)}}" id="enviar_pago" method="POST" enctype="multipart/form-data">
                            @csrf @method('PUT')
                            <div class="row">
                                <div class="col-sm-8 b-r">
                                    <div class="row">
                                        
                                        <div class="col-md-6">
                                            <h4 class="">Entidad Financiera</h4>
                                            <p>Seleccionar entidad financiera:</p>
                                        </div>
                                        <div class="col-md-3 text-right b-r">
                                            <h4 class="">Monto Restante</h4>
                                            <p class="text-navy"><b>
                                                @foreach ($monedas as $moneda)
                                                    @if ($moneda->descripcion == $orden->moneda)
                                                        {{$moneda->simbolo}}
                                                    @endif
                                                @endforeach
                                            <span id="monto_restante"></span></b></p>
                                        </div>
                                        <div class="col-md-3 text-right">
                                            <h4 class="">Monto de la Orden</h4>
                                            <p class="text-navy"><b>
                                                @foreach ($monedas as $moneda)
                                                    @if ($moneda->descripcion == $orden->moneda)
                                                        {{$moneda->simbolo}}
                                                    @endif
                                                @endforeach
                                            {{$monto}}</b></p>
                                        </div>

                                    </div>

                                    <div class="form-group">
                                        <div class="table-responsive">
                                            <table class="table dataTables-bancos table-striped table-bordered table-hover"
                                                style="text-transform:uppercase;" id="table-bancos">
                                                <thead>
                                                    <tr>
                                                        <th></th>
                                                        <th class="text-center">DESCRIPCION</th>
                                                        <th class="text-center">MONEDA</th>
                                                        <th class="text-center">CUENTA</th>
                                                        <th class="text-center">CCI</th>

                                                    </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                            </table>
                                        </div>

                                    </div>

                                    <hr>
                                    <h4>Datos Seleccionados</h4>
                                    <p>Datos seleccionados de la entidad financiera:</p>
                                    <div class="form-group row">
                                       
                                            <input type="hidden" name="id_orden" id="id_orden" value="{{$orden->id}}">
                                            <input type="hidden" name="id_entidad" id="id_entidad" value="{{old('id_entidad' , $pago[0]->banco_id)}}">
                                            <div class="col-md-3">

                                                <label class="col-form-label">Descripción</label>
                                                <input type="text" id="descripcion" class="form-control" name="descripcion" value="{{old('descripcion' , $pago[0]->descripcion)}}" disabled>
                                                
                                            </div>

                                            <div class="col-md-3">

                                                <label class="col-form-label">Moneda</label>
                                                <input type="text" id="moneda" class="form-control" name="moneda" value="{{old('moneda' , $pago[0]->moneda)}}" disabled>

                                            </div>

                                            <div class="col-md-3">

                                                <label class="col-form-label">N° Cuenta</label>
                                                <input type="text" id="cuenta" class="form-control" name="num_cuenta" value="{{old('num_cuenta' , $pago[0]->num_cuenta)}}" disabled>

                                            </div>
                                            
                                            <div class="col-md-3">

                                                <label class="col-form-label">N° CCI</label>
                                                <input type="text" id="cci" class="form-control"  name="cci" value="{{old('cci' , $pago[0]->cci)}}" disabled>
                                            </div>
                                        
                                        
                                    </div>

                                </div>
                                <div class="col-sm-4">
                                    <h4>Proveedor</h4>
                                    <p>Datos del proveedor a pagar:</p>
                                    <div style="text-transform:uppercase">

                                        @if ($orden->proveedor->ruc)
                                            <div class="form-group" >

                                            <label class="col-form-label">RUC</label>
                                            <input type="text" class="form-control" value="{{$orden->proveedor->ruc}}" style="text-transform:uppercase" disabled>

                                            </div>

                                        @else
                                            <div class="form-group" >

                                            <label class="col-form-label">DNI</label>
                                            <input type="text" class="form-control" value="{{$orden->proveedor->dni}}" style="text-transform:uppercase" disabled>

                                            </div>
                                        @endif


                                        <div class="form-group" >

                                            <label class="col-form-label">Descripcion</label>
                                            <input type="text" class="form-control" value="{{$orden->proveedor->descripcion}}" style="text-transform:uppercase" disabled>

                                        </div>

                                        <div class="form-group">

                                            <label class="col-form-label">Direccion</label>
                                            <textarea type="text" style="text-transform:uppercase" class="form-control" disabled>{{$orden->proveedor->direccion}}</textarea>

                                        </div>

                                        <div class="form-group">

                                            <label class="col-form-label">Telefono</label>
                                            <input type="text" class="form-control"  style="text-transform:uppercase" value="{{$orden->proveedor->telefono}}" disabled>

                                        </div>

                                        <div class="form-group">

                                            <label class="col-form-label">Correo</label>
                                            <input type="text"  style="text-transform:uppercase" class="form-control" value="{{$orden->proveedor->correo}}"disabled>

                                        </div>
                                    </div>

                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-6 b-r">
                                    <h4>Pago</h4>
                                    <p>Registrar datos del nuevo pago:</p>
                                    <!-- Monto restante global -->
                                    <input type="hidden" id="suma_monto_restante" value="{{$monto_restante + $pago[0]->monto}}">
                                    <div class="form-group row">

                                        <div class="col-lg-6 col-xs-12" id="fecha_pago">
                                            <label class="required">Fecha de Pago</label>
                                            <div class="input-group date">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                </span>
                                                <input type="text" id="fecha_pago_campo" name="fecha_pago"
                                                    class="form-control {{ $errors->has('fecha_pago') ? ' is-invalid' : '' }}"
                                                    value="{{old('fecha_pago',getFechaFormato($pago[0]->fecha_pago, 'd/m/Y'))}}"
                                                    autocomplete="off" required readonly>

                                                    @if ($errors->has('fecha_pago'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('fecha_pago') }}</strong>
                                                    </span>
                                                    @endif
                                            </div>
                                        </div>

                                        <div class="col-lg-6 col-xs-12" id="">
                                            <label class="required">Moneda</label>
                                            <select
                                                class="select2_form form-control {{ $errors->has('moneda') ? ' is-invalid' : '' }}"
                                                style="text-transform: uppercase; width:100%" value="{{old('moneda',$pago[0]->moneda)}}"
                                                name="moneda" id="moneda" disabled>
                                                <option></option>
                                                @foreach ($monedas as $moneda)
                                                <option value="{{$moneda->descripcion}}" @if(old('moneda',$pago[0]->moneda)==$moneda->
                                                    descripcion ) {{'selected'}} @endif
                                                    >{{$moneda->simbolo.' - '.$moneda->descripcion}}</option>
                                                @endforeach
                                                @if ($errors->has('moneda'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('moneda') }}</strong>
                                                </span>
                                                @endif
                                            </select>
                                            <input type="hidden" name="moneda" value="{{$orden->moneda}}">


                                        </div>



                                    </div>
                                    <div class="form-group row">

                                        <div class="col-lg-4 col-xs-12" id="">
                                                <label class="required">Monto
                                                @foreach ($monedas as $moneda)
                                                    @if ($moneda->descripcion == $orden->moneda)
                                                        {{$moneda->simbolo}}
                                                    @endif
                                                @endforeach
                                                :</label>
                                                <input type="text" id="monto" name="monto" class="form-control {{ $errors->has('monto') ? ' is-invalid' : '' }}" value="{{old('monto',$pago[0]->monto)}}"  placeholder="0.00" required>
                                                @if ($errors->has('monto'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('monto') }}</strong>
                                                </span>
                                                @endif

                                        </div>

                                        <input type="hidden" id="monto_2" value="{{$pago[0]->monto}}">

                                        @if($orden->moneda != 'SOLES')
                                        <div class="col-lg-4 col-xs-12" id="">
                                                <label class="required">Tipo de Cambio:</label>
                                                <input type="text" id="tipo_cambio" name="tipo_cambio" class="form-control {{ $errors->has('tipo_cambio') ? ' is-invalid' : '' }}" value="{{old('tipo_cambio',$pago[0]->tipo_cambio)}}"  placeholder="0.00" required>
                                                @if ($errors->has('tipo_cambio'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('tipo_cambio') }}</strong>
                                                </span>
                                                @endif

                                        </div>
                                        <div class="col-lg-4 col-xs-12" id="requerido_cambio">
                                                <label class="required" id="requerido_tipo_cambio_label">Cambio (S/.):</label>
                                                <input type="text" id="cambio" name="cambio" class="form-control {{ $errors->has('tipo_cambio') ? ' is-invalid' : '' }}" value="{{old('cambio')}}"  placeholder="0.00"required disabled>
                                                @if ($errors->has('cambio'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('cambio') }}</strong>
                                                </span>
                                                @endif

                                        </div>
                                        @endif




                                    </div>

                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <label class="required">Archivo:</label>

                                            <div class="custom-file">
                                                <input id="archivo" type="file" name="archivo" id="archivo"
                                                    class="custom-file-input {{ $errors->has('archivo') ? ' is-invalid' : '' }}"
                                                    accept="pdf , image/*" value="{{old('archivo', $pago[0]->ruta_archivo)}}">

                                                <label for="archivo" id="archivo_txt"
                                                    class="custom-file-label selected {{ $errors->has('ruta') ? ' is-invalid' : '' }}">{{$pago[0]->nombre_archivo}}</label>

                                                @if ($errors->has('archivo'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('archivo') }}</strong>
                                                </span>
                                                @endif

                                            </div>

                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label>Observación:</label>
                                        <textarea type="text" placeholder=""
                                            class="form-control {{ $errors->has('observacion') ? ' is-invalid' : '' }}"
                                            name="observacion" id="observacion" style="text-transform:uppercase;"
                                            value="{{old('observacion',$pago[0]->observacion)}}">{{old('observacion',$pago[0]->observacion)}}</textarea>
                                        @if ($errors->has('observacion'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('observacion') }}</strong>
                                        </span>
                                        @endif


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
                                    <a href="{{route('compras.pago.index',$orden->id)}}" id="btn_cancelar"
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
@stop
@push('styles')
<link href="{{asset('Inspinia/css/plugins/select2/select2.min.css')}}" rel="stylesheet">
<!-- DataTable -->
<link href="{{asset('Inspinia/css/plugins/dataTables/datatables.min.css')}}" rel="stylesheet">
<link href="{{ asset('Inspinia/css/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css') }}"
    rel="stylesheet">
<link href="{{ asset('Inspinia/css/plugins/datapicker/datepicker3.css') }}" rel="stylesheet">
<link href="{{ asset('Inspinia/css/plugins/daterangepicker/daterangepicker-bs3.css') }}" rel="stylesheet">
<link href="{{ asset('Inspinia/css/plugins/select2/select2.min.css') }}" rel="stylesheet">
<style>
div.dataTables_wrapper div.dataTables_paginate ul.pagination {
    margin-left: 2px;
}

#table-bancos tr[data-href] {
    cursor: pointer;
}

#table-bancos tbody .fila_entidad.selected {
    /* color: #151515 !important;*/
    font-weight: 400;
    color: white !important;
    background-color: #1ab394 !important;
    /* background-color: #CFCFCF !important; */
}
</style>
@endpush
@push('scripts')
<!-- Select2 -->
<script src="{{asset('Inspinia/js/plugins/select2/select2.full.min.js')}}"></script>
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

<script>
//Select2
$(".select2_form").select2({
    placeholder: "SELECCIONAR",
    allowClear: true,
    width: '100%',
});

$('#fecha_pago .input-group.date').datepicker({
    todayBtn: "linked",
    keyboardNavigation: false,
    forceParse: false,
    autoclose: true,
    language: 'es',
    format: "dd/mm/yyyy",
    startDate: "today"
})

$('#tipo_cambio').keyup(function() {
    var val = $(this).val();
    if (isNaN(val)) {
        val = val.replace(/[^0-9\.]/g, '');
        if (val.split('.').length > 2)
            val = val.replace(/\.+$/, "");
    }
    $(this).val(val);
});

$('#cambio').keyup(function() {
    var val = $(this).val();
    if (isNaN(val)) {
        val = val.replace(/[^0-9\.]/g, '');
        if (val.split('.').length > 2)
            val = val.replace(/\.+$/, "");
    }
    $(this).val(val);
});


$('#monto').keyup(function() {
    var val = $(this).val();
    if (isNaN(val)) {
        val = val.replace(/[^0-9\.]/g, '');
        if (val.split('.').length > 2)
            val = val.replace(/\.+$/, "");
    }
    $(this).val(val);
});

// Error entidad bancaria
@if ($errors->has('id_entidad'))
    toastr.error("{{ $errors->first('id_entidad') }}", 'Error');    
@endif

$(document).ready(function() {

    // DataTables
    $('.dataTables-bancos').DataTable({
        "dom": 'Tftp',
        "bPaginate": true,
        "bFilter": true,
        "bInfo": true,
        "bAutoWidth": false,
        "language": {
            "url": "{{asset('Spanish.json')}}"
        },

        "columnDefs": [{
                "targets": [0],
                visible: false
            },
            {
                "targets": [1],
                className: "text-center",
            },
            {
                "targets": [2],
                className: "text-center",
            },
            {
                "targets": [3],
                className: "text-center",
            },
            {
                "targets": [4],
                className: "text-center",
            },

        ],
        createdRow: function(row, data, dataIndex, cells) {
            $(row).addClass('fila_entidad');
            $(row).attr('data-href', "");
        },

    });
    obtenerTabla()
    buscarBanco()

    var table = $('.dataTables-bancos').DataTable();
    $('.dataTables-bancos tbody').on('click', 'tr', function() {
        if ($(this).hasClass('selected')) {
            $(this).removeClass('selected');
        } else {
            table.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
    });

    $('.dataTables-bancos').on('click', 'tbody td' , function() {
        var data = table.row(this).data();
        $('#id_entidad').val(data[0])
        $('#descripcion').val(data[1])
        $('#moneda').val(data[2])
        $('#cuenta').val(data[3])
        $('#cci').val(data[4])

    });

    //Cambio
    var monto = $('#monto').val()
    var tipo_cambio = $('#tipo_cambio').val()
    var cambio = monto*tipo_cambio
    $('#cambio').val(cambio.toFixed(2))

    //Monto Restante
    $('#monto_restante').text("{{$monto_restante}}")

    // Escuchamos el evento keyup de nuestro input
    
    $('#monto').keyup(function() {
 
        // Valor restante
        var monto_restante = $('#suma_monto_restante').val()
        //Rangos
        const min = 0;    
 

        // // Obtenemos el objeto
        // var self = $(this);
        // // Obtenemos el valor actual
        // var value = self.val();
        

        
        // Si el valor obtenido es menor a nuestro valor mínimo
        // o nuestro valor valor obtenido es mayor a nuestro valor máximo
        // Le decimos al usuario que no está dentro del rango 
        // y limpiamos nuestro campo
     
        if($(this).val() > Number(monto_restante) || $(this).val() < min   ){
            toastr.error("El monto ingresado no está en el rango permitido del monto restante.", 'Error');
            $('#monto').val('');
        }else{
            const max = (Number(monto_restante) - Number($(this).val())).toFixed(2); 
            // Nuevo monto restante
            $('#monto_restante').text(max)
        }
        
    })


})


function obtenerTabla() {
    var t = $('.dataTables-bancos').DataTable();
    @foreach($bancos_proveedor as $ban)
        @if($ban['estado'] == 'ACTIVO' )
            t.row.add([
                "{{$ban['id']}}",
                "{{$ban['descripcion']}}",
                "{{$ban['tipo_moneda']}}",
                "{{$ban['num_cuenta']}}",
                "{{$ban['cci']}}",
            ]).draw(false);
        @endif
    @endforeach
}

$('.custom-file-input').on('change', function() {
    var fileInput = document.getElementById('archivo');
    var filePath = fileInput.value;
    var allowedExtensions = /(.jpg|.jpeg|.png|.pdf)$/i;

    if (allowedExtensions.exec(filePath)) {
        var userFile = document.getElementById('archivo');
        userFile.src = URL.createObjectURL(event.target.files[0]);
        let fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').addClass("selected").html(fileName);
    } else {
        toastr.error('Extensión inválida, formatos admitidos (.pdf .jpg . jpeg . png)', 'Error');
    }
});

function validar() {
    
    var enviar = false;

    if ($('#id_entidad').val()==''){
        enviar = true
        toastr.error("Seleccionar entidad bancaria.", 'Error');
    }



    return enviar

}

$('#enviar_pago').submit(function(e) {
    e.preventDefault();
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

            var enviar = validar()
            if (enviar ==  false) {
                $('#cambio').attr('disabled',false)
                this.submit();               
            }


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
})

function buscarBanco() {
    var id = $('#id_entidad').val()
    var descripcion = $('#descripcion').val()
    var moneda = $('#moneda').val()
    var cuenta  =  $('#cuenta').val()
    var cci = $('#cci').val()

    var t = $('.dataTables-bancos').DataTable();
    var indice = ''
    var row2 = ''
    t.rows().data().each(function(row,el, index) {
        if (row[0] == id && 
            row[1] == descripcion &&
            row[2] == moneda &&
            row[3] == cuenta &&
            row[4] == cci 
        ) {
            console.log(descripcion)
            $(".dataTables-bancos tr:eq(" + (el+1) + ")").addClass('selected');
        }
    });





}

//Calcular cambio soles

$('#tipo_cambio').keyup(function() {
    var monto = $('#monto').val()
    var tipo_cambio = $('#tipo_cambio').val()
    var cambio = monto*tipo_cambio
    $('#cambio').val(cambio.toFixed(2))
})

$('#monto').keyup(function() {
    var monto = $('#monto').val()
    var tipo_cambio = $('#tipo_cambio').val()
    var cambio = monto*tipo_cambio
    $('#cambio').val(cambio.toFixed(2))
})




</script>



@endpush