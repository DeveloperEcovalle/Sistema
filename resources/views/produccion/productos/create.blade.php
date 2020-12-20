@extends('layout') @section('content')
@include('produccion.productos.edit-detalle')
@section('produccion-active', 'active')
@section('productos-active', 'active')

<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-12">
        <h2 style="text-transform:uppercase;"><b>REGISTRAR NUEVO PRODUCTO TERMINADO</b></h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('home') }}">Panel de Control</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('produccion.producto.index') }}">Productos Terminados</a>
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
                    <form action="{{ route('produccion.familias.store') }}" method="POST" id="form_registrar_producto">
                        @csrf
                        <div class="row"
                        >
                            <div class="col-lg-8 col-xs-12 b-r">
                                <h4><b>Datos Generales</b></h4>
                                <div class="form-group row">
                                    <div class="col-lg-4 col-xs-12">
                                        <label class="required">Código ISO</label>
                                        <input type="text" id="codigo" name="codigo" class="form-control {{ $errors->has('codigo') ? ' is-invalid' : '' }}" value="{{old('codigo')}}" maxlength="50" onkeyup="return mayus(this)" required>
                                        @if ($errors->has('codigo'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('codigo') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="col-lg-8 col-xs-12">
                                        <label class="required">Nombre</label>
                                        <input type="text" id="nombre" name="nombre" class="form-control {{ $errors->has('nombre') ? ' is-invalid' : '' }}" value="{{old('nombre')}}" maxlength="191" onkeyup="return mayus(this)" required>
                                        @if ($errors->has('nombre'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('nombre') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-lg-6 col-xs-12">
                                        <label class="required">Familia</label>
                                        <select id="familia" name="familia" class="select2_form form-control {{ $errors->has('familia') ? ' is-invalid' : '' }}">
                                            <option></option>
                                            @foreach($familias as $familia)
                                                <option value="{{ $familia->id }}" {{ (old('familia') == $familia->id ? "selected" : "") }} >{{ $familia->familia }}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('familia'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('familia') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="col-lg-6 col-xs-12">
                                        <label class="required">Sub Familia</label>
                                        <select id="sub_familia" name="sub_familia" class="select2_form form-control {{ $errors->has('sub_familia') ? ' is-invalid' : '' }}">
                                            <option></option>
                                        </select>
                                        @if ($errors->has('sub_familia'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('sub_familia') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-lg-6 col-xs-12">
                                        <label class="required">Presentación</label>
                                        <select id="presentacion" name="presentacion" class="select2_form form-control {{ $errors->has('presentacion') ? ' is-invalid' : '' }}">
                                            <option></option>
                                            @foreach(presentaciones() as $presentacion)
                                                <option value="{{ $presentacion->simbolo }}" {{ (old('presentacion') == $presentacion->simbolo ? "selected" : "") }}>{{ $presentacion->descripcion }}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('presentacion'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('presentacion') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-xs-12">
                                <h4><b>Cantidades y Precios</b></h4>
                                <div class="form-group row">
                                    <div class="col-lg-6 col-xs-12">
                                        <label class="required">Stock</label>
                                        <input type="text" id="stock" name="stock" class="form-control {{ $errors->has('stock') ? ' is-invalid' : '' }}" value="{{old('stock')}}" maxlength="10" onkeypress="return isNumber(event);" required>
                                        @if ($errors->has('stock'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('stock') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="col-lg-6 col-xs-12">
                                        <label class="required">Stock mínimo</label>
                                        <input type="text" id="stock_minimo" name="stock_minimo" class="form-control {{ $errors->has('stock_minimo') ? ' is-invalid' : '' }}" value="{{old('stock_minimo')}}" maxlength="10" onkeypress="return isNumber(event);" required>
                                        @if ($errors->has('stock_minimo'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('stock_minimo') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-lg-6 col-xs-12">
                                        <label class="required">Precio venta mínimo</label>
                                        <input type="text" id="precio_venta_minimo" name="precio_venta_minimo" class="form-control {{ $errors->has('precio_venta_minimo') ? ' is-invalid' : '' }}" value="{{old('precio_venta_minimo')}}" maxlength="15" onkeypress="return filterFloat(event, this);" required>
                                        @if ($errors->has('precio_venta_minimo'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('precio_venta_minimo') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="col-lg-6 col-xs-12">
                                        <label class="required">Precio venta máximo</label>
                                        <input type="precio_venta_maximo" id="precio_venta_maximo" name="precio_venta_maximo" class="form-control {{ $errors->has('precio_venta_maximo') ? ' is-invalid' : '' }}" value="{{old('precio_venta_maximo')}}" maxlength="15" onkeypress="return filterFloat(event, this);" required>
                                        @if ($errors->has('precio_venta_maximo'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('precio_venta_maximo') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-lg-12 col-xs-12">
                                        <label class="required">Incluye IGV</label>
                                        <div class="row">
                                            <div class="col-sm-6 col-xs-6">
                                                <div class="radio">
                                                    <input type="radio" name="igv" id="igv_si" value="1" checked="">
                                                    <label for="igv_si">
                                                        SI
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-sm-6 col-xs-6">
                                                <div class="radio">
                                                    <input type="radio" name="igv" id="igv_no" value="0">
                                                    <label for="igv_no">
                                                        NO
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="col-lg-12 col-xs-12 m-t-sm">
                                <div class="panel panel-primary">
                                    <div class="panel-heading">
                                        <b>Detalles del producto</b>
                                    </div>
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-group row">
                                                    <div class="col-lg-6 col-xs-12">
                                                        <label class="required">Artículo</label>
                                                        <select id="articulo" name="articulo" class="select2_form form-control {{ $errors->has('articulo') ? ' is-invalid' : '' }}">
                                                            <option></option>
                                                            @foreach($articulos as $articulo)
                                                                <option value="{{ $articulo->id }}" {{ (old('articulo') == $articulo->id ? "selected" : "") }} >{{ $articulo->getDescripcionCompleta() }}</option>
                                                            @endforeach
                                                        </select>
                                                        @if ($errors->has('articulo'))
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $errors->first('articulo') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                    <div class="col-lg-2 col-xs-12">
                                                        <label class="required">Cantidad</label>
                                                        <input type="text" id="cantidad" name="cantidad" class="form-control {{ $errors->has('cantidad') ? ' is-invalid' : '' }}" value="{{old('cantidad')}}" maxlength="10" onkeypress="return isNumber(event);" required>
                                                        @if ($errors->has('cantidad'))
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $errors->first('cantidad') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                    <div class="col-lg-2 col-xs-12">
                                                        <label class="required">Peso</label>
                                                        <input type="text" id="peso" name="peso" class="form-control {{ $errors->has('peso') ? ' is-invalid' : '' }}" value="{{old('peso')}}" maxlength="15" onkeypress="return filterFloat(event, this);" required>
                                                        @if ($errors->has('peso'))
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $errors->first('peso') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <div class="col-lg-10 col-xs-12">
                                                        <label>Observación</label>
                                                        <input type="text" id="observacion" name="peso" class="form-control {{ $errors->has('observacion') ? ' is-invalid' : '' }}" value="{{old('observacion')}}" maxlength="300" onkeyup="return mayus(this)">
                                                        @if ($errors->has('observacion'))
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $errors->first('observacion') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                    <div class="col-lg-2 col-xs-12">
                                                        <button type="button" id="btn_agregar_detalle" class="btn btn-primary btn-block m-t-lg"><i class="fa fa-plus-circle"></i> Agregar</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row m-t-sm">
                                            <div class="col-lg-12">
                                                <div class="table-responsive">
                                                    <table class="table dataTables-detalle-producto table-striped table-bordered table-hover" style="text-transform:uppercase;">
                                                        <thead>
                                                        <tr>
                                                            <th></th>
                                                            <th class="text-left">ARTÍCULO</th>
                                                            <th class="text-center">CANTIDAD</th>
                                                            <th class="text-center">PESO</th>
                                                            <th class="text-left">OBSERVACIÓN</th>
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
    <script>
        var table;
        var detalles = [];
        var objectRowDelete, objectRowEdit;

        //Modal Eliminar
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger',
            },
            buttonsStyling: false
        })

        $(document).ready(function() {
            $(".select2_form").select2({
                placeholder: "SELECCIONAR",
                allowClear: true,
                height: '200px',
                width: '100%',
            });

            table = $('.dataTables-detalle-producto').DataTable({
                "responsive": true,
                "bPaginate": false,
                "bLengthChange": false,
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
                        targets: -1,
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
                    { sWidth: '40%', sClass: 'text-left' },
                    { sWidth: '10%', sClass: 'text-center' },
                    { sWidth: '10%', sClass: 'text-center' },
                    { sWidth: '30%', sClass: 'text-left' },
                    { sWidth: '10%', sClass: 'text-center' },
                ],
                'data': null,
                "language": {
                    url: "{{asset('Spanish.json')}}"
                },
                "order": [[ 0, "desc" ]],
            });

            //Controlar Error
            $.fn.DataTable.ext.errMode = 'throw';

            $("#btn_agregar_detalle").on("click", agregarDetalle);

            $("#btn_editar_detalle").on("click", editarDetalle);

            $('.dataTables-detalle-producto tbody').on('click', 'button.btn-edit', cargarDetalle);

            $('.dataTables-detalle-producto tbody').on('click', 'button.btn-delete', eliminarDetalle);

        });

        function agregarDetalle() {

            var detalle = {
                articulo_id: $("#articulo").val(),
                articulo: $("#articulo").select2('data')[0].text,
                cantidad: parseInt($("#cantidad").val()),
                peso: parseFloat($("#peso").val()),
                observacion: $("#observacion").val()
            };

            if (validarDetalle(detalle)) {
                table.row.add([
                    detalle.articulo_id,
                    detalle.articulo,
                    detalle.cantidad,
                    detalle.peso,
                    detalle.observacion
                ]).draw(false);
                detalles.push(detalle);
                limpiarCamposDetalle();
            }
        }

        function cargarDetalle() {
            //console.log(table.row($(this).parents("tr")).data());
            var dataRow = table.row($(this).parents("tr")).data();
            objectRowEdit = table.row($(this).parents("tr"));
            $("#articulo_editar").val(dataRow[0]).trigger("change");
            $("#cantidad_editar").val(dataRow[2]);
            $("#peso_editar").val(dataRow[3]);
            $("#observacion_editar").val(dataRow[4]);

            $('#modal_editar_detalle').modal('show');
        }

        function editarDetalle() {
            var detalle = {
                articulo_id: $("#articulo_editar").val(),
                articulo: $("#articulo_editar").select2('data')[0].text,
                cantidad: parseInt($("#cantidad_editar").val()),
                peso: parseFloat($("#peso_editar").val()),
                observacion: $("#observacion_editar").val()
            };

            if (validarDetalle(detalle, true)) {
                var indexRow = objectRowEdit.index();
                detalles.forEach((element, index) => {
                    if(element.articulo_id === detalle.articulo_id) {
                        detalles[index] = detalle;
                    }
                });
                objectRowEdit.cell(indexRow, 0).data(detalle.articulo_id).draw(false);
                objectRowEdit.cell(indexRow, 1).data(detalle.articulo).draw(false);
                objectRowEdit.cell(indexRow, 2).data(detalle.cantidad).draw(false);
                objectRowEdit.cell(indexRow, 3).data(detalle.peso).draw(false);
                objectRowEdit.cell(indexRow, 4).data(detalle.observacion).draw(false);

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
                    var removeIndex = detalles.map(function(item) { return item.articulo_id; }).indexOf(dataRow[0]);
                    detalles.splice(removeIndex, 1);

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
                if (detalle.articulo_id === undefined || detalle.articulo_id === null || detalle.articulo_id.length === 0
                    || detalle.articulo === undefined || detalle.articulo === null || detalle.articulo.length === 0) {
                    toastr.error('El campo Artículo es obligatorio');
                    return false;
                }
                if (detalles.find(d => d.articulo_id === detalle.articulo_id) !== undefined) {
                    toastr.error('El artículo seleccionado ya existe en el detalle del producto');
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
            if (detalle.peso === undefined || detalle.peso === null || Number.isNaN(detalle.peso)) {
                toastr.error('El campo Peso es obligatorio');
                return false;
            }
            if (detalle.peso <= 0) {
                toastr.error('El peso ingresado debe ser mayor a cero');
                return false;
            }

            return true;
        }

        function limpiarCamposDetalle() {
            $("#articulo").val("").trigger("change");
            $("#cantidad").val("");
            $("#peso").val("");
            $("#observacion").val("");
        }

        $('#modal_editar_detalle').on('hidden.bs.modal', function(e) {
            $("#articulo_editar").val("").trigger("change");
            $("#cantidad_editar").val("");
            $("#peso_editar").val("");
            $("#observacion_editar_editar").val("");
        });

    </script>
@endpush
