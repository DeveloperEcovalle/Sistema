@extends('layout') @section('content')
@include('ventas.cotizaciones.edit-detalle')
@section('ventas-active', 'active')
@section('cotizaciones-active', 'active')

<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-12">
        <h2 style="text-transform:uppercase;"><b>REGISTRAR NUEVA COTIZACIÓN</b></h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('home') }}">Panel de Control</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('ventas.cotizacion.index') }}">Cotizaciones</a>
            </li>
            <li class="breadcrumb-item active">
                <strong>Registrar</strong>
            </li>
        </ol>
    </div>
</div>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-content">
                    <form action="{{ route('ventas.cotizacion.store') }}" method="POST" id="form_registrar_cotizacion">
                        @csrf
                        <div class="row">
                            <div class="col-lg-12 col-xs-12">
                                <h4><b>Datos Generales</b></h4>
                            </div>
                            <div class="col-lg-6 col-xs-12 b-r">
                                <div class="form-group row">
                                    <div class="col-lg-6 col-xs-12" id="fecha_documento">
                                        <label class="required">Fecha de Documento</label>
                                        <div class="input-group date">
                                            <span class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </span>
                                            <input type="text" id="fecha_documento" name="fecha_documento" class="form-control {{ $errors->has('fecha_documento') ? ' is-invalid' : '' }}" value="{{old('fecha_documento',getFechaFormato($fecha_hoy, 'd/m/Y'))}}" autocomplete="off" required readonly>
                                            @if ($errors->has('fecha_documento'))
                                                <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('fecha_documento') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-xs-12" id="fecha_atencion">
                                        <label class="required">Fecha de Atención</label>
                                        <div class="input-group date">
                                            <span class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </span>
                                            <input type="text" id="fecha_atencion" name="fecha_atencion" class="form-control {{ $errors->has('fecha_atencion') ? ' is-invalid' : '' }}" value="{{old('fecha_atencion',getFechaFormato($fecha_hoy, 'd/m/Y'))}}" autocomplete="off" required readonly>
                                            @if ($errors->has('fecha_atencion'))
                                                <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('fecha_atencion') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-lg-6 col-xs-12">
                                        <label class="required">Moneda</label>
                                        <select id="moneda" name="moneda" class="select2_form form-control {{ $errors->has('moneda') ? ' is-invalid' : '' }}">
                                            <option></option>
                                            @foreach(tipos_moneda() as $moneda)
                                                <option value="{{ $moneda->simbolo }}" {{ (old('moneda') == $moneda->simbolo ? "selected" : "") }}>{{ $moneda->descripcion }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-6 col-xs-12">
                                        <label id="igv_requerido">IGV (%):</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-addon">
                                                    <input type="checkbox" id="igv_check" name="igv_check">
                                                </span>
                                            </div>
                                            <input type="text" value="{{old('igv')}}" class="form-control {{ $errors->has('igv') ? ' is-invalid' : '' }}"  name="igv" id="igv" maxlength="2" required>
                                            @if ($errors->has('igv'))
                                                <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('igv') }}</strong>
                                            </span>
                                            @endif

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-xs-12">
                                <div class="form-group row">
                                    <div class="col-lg-12 col-xs-12">
                                        <label class="required">Empresa</label>
                                        <select id="empresa" name="empresa" class="select2_form form-control {{ $errors->has('empresa') ? ' is-invalid' : '' }}">
                                            <option></option>
                                            @foreach($empresas as $empresa)
                                                <option value="{{ $empresa->id }}" {{ (old('empresa') == $empresa->razon_social ? "selected" : "") }} >{{ $empresa->razon_social }}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('empresa'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('empresa') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-lg-12 col-xs-12">
                                        <label class="required">Cliente</label>
                                        <select id="cliente" name="cliente" class="select2_form form-control {{ $errors->has('cliente') ? ' is-invalid' : '' }}">
                                            <option></option>
                                            @foreach($clientes as $cliente)
                                                <option value="{{ $cliente->id }}" {{ (old('cliente') == $cliente->id ? "selected" : "") }} >{{ $cliente->getDocumento() }} - {{ $cliente->nombre }}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('cliente'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('cliente') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-lg-12 col-xs-12">
                                <div class="panel panel-primary">
                                    <div class="panel-heading">
                                        <h4><b>Detalle de la Cotización</b></h4>
                                    </div>
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-group row">
                                                    <div class="col-lg-6 col-xs-12">
                                                        <label class="required">Producto</label>
                                                        <select id="producto" class="select2_form form-control {{ $errors->has('producto') ? ' is-invalid' : '' }}">
                                                            <option></option>
                                                            @foreach($productos as $producto)
                                                                <option value="{{ $producto->id }}" data-igv="{{ $producto->igv }}" {{ (old('producto') == $producto->id ? "selected" : "") }} >{{ $producto->getDescripcionCompleta() }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <!--<div class="col-lg-1 col-xs-12">
                                                        <label class="required">IGV (%)</label>
                                                        <input type="text" id="igv" class="form-control" value="-" disabled>
                                                    </div>-->
                                                    <div class="col-lg-2 col-xs-12">
                                                        <label class="required">Cantidad</label>
                                                        <input type="text" id="cantidad" class="form-control" maxlength="10" onkeypress="return isNumber(event);">
                                                    </div>
                                                    <div class="col-lg-2 col-xs-12">
                                                        <label class="required">Precio</label>
                                                        <input type="text" id="precio" class="form-control" maxlength="15" onkeypress="return filterFloat(event, this);">
                                                    </div>
                                                    <div class="col-lg-2 col-xs-12">
                                                        <button type="button" id="btn_agregar_detalle" class="btn btn-warning btn-block m-t-lg"><i class="fa fa-plus-circle"></i> Agregar</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row m-t-sm">
                                            <div class="col-lg-12">
                                                <div class="table-responsive">
                                                    <table class="table dataTables-detalle-cotizacion table-striped table-bordered table-hover" style="text-transform:uppercase;">
                                                        <thead>
                                                        <tr>
                                                            <th></th>
                                                            <th class="text-center">ACCIONES</th>
                                                            <th class="text-left">PRODUCTO</th>
                                                            <th class="text-center">CANTIDAD</th>
                                                            <th class="text-center">PRECIO</th>
                                                            <th class="text-left">TOTAL</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>

                                                        </tbody>
                                                        <tfoot>
                                                            <tr>
                                                                <th colspan="5" style="text-align: right !important;">Sub Total:</th>
                                                                <th class="text-center"><span id="sub_total">0.00</span></th>

                                                            </tr>
                                                            <tr>
                                                                <th colspan="5" class="text-right">IGV 18%:</th>
                                                                <th class="text-center"><span id="total_igv">0.00</span></th>
                                                            </tr>
                                                            <tr>
                                                                <th colspan="5" class="text-right">TOTAL:</th>
                                                                <th class="text-center"><span id="total">0.00</span></th>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                            </div>
                                            <input type="hidden" name="detalles" id="detalles" value="{{ old('detalles') }}">
                                            <input type="hidden" name="monto_sub_total" id="monto_sub_total" value="{{ old('monto_sub_total') }}">
                                            <input type="hidden" name="monto_total_igv" id="monto_total_igv" value="{{ old('monto_total_igv') }}">
                                            <input type="hidden" name="monto_total" id="monto_total" value="{{ old('monto_total') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group row">
                                    <div class="col-md-6 text-left">
                                        <i class="fa fa-exclamation-circle leyenda-required"></i> <small class="leyenda-required">Los campos marcados con asterisco
                                            (<label class="required"></label>) son obligatorios.</small>
                                    </div>
                                    <div class="col-md-6 text-right">
                                        <a href="{{route('produccion.producto.index')}}" id="btn_cancelar"
                                           class="btn btn-w-m btn-default">
                                            <i class="fa fa-arrow-left"></i> Regresar
                                        </a>
                                        <button type="submit" id="btn_grabar" class="btn btn-w-m btn-primary">
                                            <i class="fa fa-save"></i> Grabar
                                        </button>
                                    </div>
                                </div>
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
    <link href="{{ asset('Inspinia/css/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css') }}" rel="stylesheet">
    <link href="{{ asset('Inspinia/css/plugins/datapicker/datepicker3.css') }}" rel="stylesheet">
    <link href="{{ asset('Inspinia/css/plugins/daterangepicker/daterangepicker-bs3.css') }}" rel="stylesheet">
    <link href="{{asset('Inspinia/css/plugins/select2/select2.min.css')}}" rel="stylesheet">
    <link href="{{asset('Inspinia/css/plugins/dataTables/datatables.min.css')}}" rel="stylesheet">
@endpush

@push('scripts')
    <script src="{{ asset('Inspinia/js/plugins/iCheck/icheck.min.js') }}"></script>
    <script src="{{ asset('Inspinia/js/plugins/select2/select2.full.min.js') }}"></script>
    <script src="{{asset('Inspinia/js/plugins/dataTables/datatables.min.js')}}"></script>
    <script src="{{asset('Inspinia/js/plugins/dataTables/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{ asset('Inspinia/js/plugins/datapicker/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('Inspinia/js/plugins/fullcalendar/moment.min.js') }}"></script>
    <script src="{{ asset('Inspinia/js/plugins/daterangepicker/daterangepicker.js') }}"></script>
    <script>
        var table;
        var detalles = [];
        var objectRowDelete, objectRowEdit;
        var total = 0, total_igv = 0, sub_total = 0;
        const _igv = 18;

        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger',
            },
            buttonsStyling: false
        })

        $(document).ready(function() {
            $('.i-checks').iCheck({
                checkboxClass: 'icheckbox_square-green',
                radioClass: 'iradio_square-green',
            });

            $(".select2_form").select2({
                placeholder: "SELECCIONAR",
                allowClear: true,
                height: '200px',
                width: '100%',
            });

            $('.input-group.date').datepicker({
                todayBtn: "linked",
                keyboardNavigation: false,
                forceParse: false,
                autoclose: true,
                language: 'es',
                format: "dd/mm/yyyy"
            });

            if ($("#igv_check").prop('checked')) {
                $('#igv').attr('disabled', false)
                $('#igv_requerido').addClass("required")
            } else {
                $('#igv').attr('disabled', true)
                $('#igv_requerido').removeClass("required")
            }

            $("#igv_check").click(function() {
                if ($("#igv_check").is(':checked')) {
                    $('#igv').attr('disabled', false)
                    $('#igv_requerido').addClass("required")
                    $('#igv').prop('required', true)
                    $('#igv').val('18')
                    var igv = ($('#igv').val()) + ' %'
                    $('#igv_int').text(igv)
                    calcularIgv($('#igv').val())

                } else {
                    $('#igv').attr('disabled', true)
                    $('#igv_requerido').removeClass("required")
                    $('#igv').prop('required', false)
                    $('#igv').val('')
                    $('#igv_int').text('')
                    calcularIgv($('#igv').val())
                }
            });

            $("#igv").on("change", function() {
                if ($("#igv_check").is(':checked')) {
                    $('#igv').attr('disabled', false)
                    $('#igv_requerido').addClass("required")
                    $('#igv').prop('required', true)
                    var igv = ($('#igv').val()) + ' %'
                    $('#igv_int').text(igv)
                    calcularIgv($('#igv').val())

                } else {
                    $('#igv').attr('disabled', true)
                    $('#igv_requerido').removeClass("required")
                    $('#igv').prop('required', false)
                    $('#igv').val('')
                    $('#igv_int').text('')
                }
            });

            table = $('.dataTables-detalle-cotizacion').DataTable({
                "dom": '<"html5buttons"B>lTfgitp',
                "buttons": [{
                    extend: 'excelHtml5',
                    text: '<i class="fa fa-file-excel-o"></i> Excel',
                    titleAttr: 'Excel',
                    title: 'Detalle de la Cotización'
                },
                {
                    titleAttr: 'Imprimir',
                    extend: 'print',
                    text: '<i class="fa fa-print"></i> Imprimir',
                    customize: function (win) {
                        $(win.document.body).addClass('white-bg');
                        $(win.document.body).css('font-size', '10px');
                        $(win.document.body).find('table')
                            .addClass('compact')
                            .css('font-size', 'inherit');
                    }
                }],
                "bPaginate": true,
                "bLengthChange": true,
                "responsive": true,
                "bFilter": true,
                "bInfo": false,
                "columnDefs": [
                    {
                        "targets": 0,
                        "visible": false,
                        "searchable": false
                    },
                    {
                        searchable: false,
                        targets: 1,
                        data: null,
                        defaultContent: "<button type='button' class='btn btn-sm btn-warning mr-1 btn-edit'>" +
                            "<i class='fa fa-pencil'></i>" +
                            "</button>" +
                            "<button type='button' class='btn btn-sm btn-danger mr-1 btn-delete'>" +
                            "<i class='fa fa-trash'></i>" +
                            "</button>"
                    }
                ],
                'bAutoWidth': false,
                'aoColumns': [
                    { sWidth: '0%' },
                    { sWidth: '10%', sClass: 'text-left' },
                    { sWidth: '40%', sClass: 'text-center' },
                    { sWidth: '15%', sClass: 'text-center' },
                    { sWidth: '15%', sClass: 'text-center' },
                    { sWidth: '15%', sClass: 'text-center' },
                ],
                'data': getData(),
                "language": {
                    url: "{{asset('Spanish.json')}}"
                },
                "order": [[ 0, "desc" ]],
            });

            //Controlar Error
            $.fn.DataTable.ext.errMode = 'throw';

            calcularTotales();
            calcularIgv($("#igv").val());

            //$("#producto").on("change", mostrarIGVProducto);

            $("#btn_agregar_detalle").on("click", agregarDetalle);

            $("#btn_editar_detalle").on("click", editarDetalle);

            $('.dataTables-detalle-cotizacion tbody').on('click', 'button.btn-edit', cargarDetalle);

            $('.dataTables-detalle-cotizacion tbody').on('click', 'button.btn-delete', eliminarDetalle);

            $('#form_registrar_cotizacion').submit(function(e) {
                e.preventDefault();
                if (detalles !== undefined && detalles.length <= 0) {
                    toastr.error('Debe ingresar los detalles de la cotización');
                    return false;
                }
                $("#detalles").val(JSON.stringify(detalles));

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
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        swalWithBootstrapButtons.fire(
                            'Cancelado',
                            'La Solicitud se ha cancelado.',
                            'error'
                        )
                    }
                })
            });

        });

        function getData() {
            detalles = ($("#detalles").val() === undefined || $("#detalles").val() === "") ? [] : JSON.parse($("#detalles").val());
            var data = [];
            detalles.forEach(obj => {
                data.push([
                    null,
                    String(obj.producto_id),
                    String(obj.producto),
                    parseInt(obj.cantidad),
                    parseFloat(obj.precio),
                    parseInt(obj.cantidad) * parseFloat(obj.precio)
                ]);
            });
            return data;
        }

        /*function mostrarIGVProducto() {
            var aplica_igv = $(this).find(":selected").data("igv");
            if (aplica_igv !== undefined && !Number.isNaN(aplica_igv) && parseInt(aplica_igv) === 1)
                $("#igv").val(18);
            else
                $("#igv").val('-');
        }*/

        function agregarDetalle() {

            var detalle = {
                producto_id: $("#producto").val(),
                producto: $("#producto").select2('data')[0].text,
                //igv: $("#producto").find(":selected").data("igv"),
                cantidad: parseInt($("#cantidad").val()),
                precio: parseFloat($("#precio").val()),
                importe: parseInt($("#cantidad").val()) * parseFloat($("#precio").val())
            };

            if (validarDetalle(detalle)) {
                //var total_item = detalle.cantidad * detalle.precio;
                table.row.add([
                    null,
                    detalle.producto_id,
                    detalle.producto,
                    detalle.cantidad,
                    detalle.precio,
                    detalle.importe
                ]).draw(false);
                detalles.push(detalle);
                calcularTotales();
                calcularIgv($("#igv").val());
                limpiarCamposDetalle();
            }
        }

        function cargarDetalle() {
            //console.log(table.row($(this).parents("tr")).data());
            var dataRow = table.row($(this).parents("tr")).data();
            objectRowEdit = table.row($(this).parents("tr"));
            $("#producto_editar").val(dataRow[1]).trigger("change");
            $("#cantidad_editar").val(dataRow[3]);
            $("#precio_editar").val(dataRow[4]);

            $('#modal_editar_detalle').modal('show');
        }

        function editarDetalle() {
            var detalle = {
                producto_id: $("#producto_editar").val(),
                producto: $("#producto_editar").select2('data')[0].text,
                //igv: $("#producto").find(":selected").data("igv"),
                cantidad: parseInt($("#cantidad_editar").val()),
                precio: parseFloat($("#precio_editar").val()),
                importe: parseInt($("#cantidad_editar").val()) * parseFloat($("#precio_editar").val())
            };

            if (validarDetalle(detalle, true)) {
                var indexRow = objectRowEdit.index();
                detalles.forEach((element, index) => {
                    if(element.producto_id === detalle.producto_id) {
                        detalles[index] = detalle;
                    }
                });
                objectRowEdit.cell(indexRow, 1).data(detalle.producto_id).draw(false);
                objectRowEdit.cell(indexRow, 2).data(detalle.producto).draw(false);
                objectRowEdit.cell(indexRow, 3).data(detalle.cantidad).draw(false);
                objectRowEdit.cell(indexRow, 4).data(detalle.precio).draw(false);
                objectRowEdit.cell(indexRow, 5).data(detalle.importe).draw(false);

                calcularTotales();
                calcularIgv($("#igv").val());

                $('#modal_editar_detalle').modal('hide');
            }
        }

        function eliminarDetalle() {
            //console.log(table.row($(this).parents("tr")).data());
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

                    var dataRow = table.row($(this).parents("tr")).data();
                    objectRowDelete = table.row($(this).parents("tr"));
                    table.row($(this).parents("tr")).remove().draw();
                    var removeIndex = detalles.map(function(item) { return item.producto_id; }).indexOf(dataRow[1]);
                    detalles.splice(removeIndex, 1);

                    calcularTotales();
                    calcularIgv($("#igv").val());

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

        function validarDetalle(detalle, isEditar = false) {

            if (!isEditar) {
                if (detalle.producto_id === undefined || detalle.producto_id === null || detalle.producto_id.length === 0
                    || detalle.producto === undefined || detalle.producto === null || detalle.producto.length === 0) {
                    toastr.error('El campo Producto es obligatorio');
                    return false;
                }
                if (detalles.find(d => d.producto_id === detalle.producto_id) !== undefined) {
                    toastr.error('El producto seleccionado ya existe en el detalle de la cotización');
                    return false;
                }
            }

            if (detalle.cantidad === undefined || detalle.cantidad === null || Number.isNaN(detalle.cantidad)) {
                toastr.error('El campo Cantidad es obligatorio');
                return false;
            }
            if (detalle.cantidad <= 0) {
                toastr.error('La cantidad ingresada debe ser mayor a cero');
                return false;
            }
            if (detalle.precio === undefined || detalle.precio === null || Number.isNaN(detalle.precio)) {
                toastr.error('El campo Precio es obligatorio');
                return false;
            }
            if (detalle.precio <= 0) {
                toastr.error('El precio ingresado debe ser mayor a cero');
                return false;
            }

            return true;
        }

        function calcularTotalItem(detalle) {
            var precio_item = (detalle.igv === 1) ? (detalle.precio / _igv) : (detalle.precio * _igv);
            return (precio_item * detalle.cantidad).toFixed(2);
        }

        function calcularTotales() {
            var sub_total = 0;
            table.rows().data().each(function(el, index) {
                sub_total = Number(el[5]) + sub_total
            });

            $('#sub_total').text(sub_total.toFixed(2))
        }

        function calcularIgv(igv) {
            var subtotal = 0;
            var totalIGV = 0;
            var total = 0;
            table.rows().data().each(function(el, index) {
                subtotal = Number(el[5]) + subtotal
            });
            if (igv == "") {
                totalIGV = subtotal * (_igv/100);
                total = totalIGV + subtotal;

            } else {
                var monto_igv = _igv / 100;
                total = subtotal;
                totalIGV = subtotal * monto_igv;
                subtotal = total - totalIGV;
            }
            $('#total_igv').text(totalIGV.toFixed(2));
            $('#total').text(total.toFixed(2));
            $('#sub_total').text(subtotal.toFixed(2));

            $("#monto_sub_total").val(subtotal.toFixed(2));
            $("#monto_total_igv").val(totalIGV.toFixed(2));
            $("#monto_total").val(total.toFixed(2));

        }

        function limpiarCamposDetalle() {
            $("#producto").val("").trigger("change");
            $("#cantidad").val("");
            $("#precio").val("");
        }

        $('#modal_editar_detalle').on('hidden.bs.modal', function(e) {
            $("#producto_editar").val("").trigger("change");
            $("#cantidad_editar").val("");
            $("#precio_editar").val("");
        });

    </script>
@endpush
