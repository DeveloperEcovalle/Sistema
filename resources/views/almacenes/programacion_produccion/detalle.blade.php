@extends('layout') @section('content')

@section('almacenes-active', 'active')
@section('almacen_programacion_produccion-active', 'active')

    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10 col-md-10">
            <h2 style="text-transform:uppercase"><b>Listado de Programacion de Produccion</b></h2>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('home') }}">Panel de Control</a>
                </li>
                <li class="breadcrumb-item active">
                    <strong>Programacion Produccion</strong>
                </li>
            </ol>
        </div>
    </div>

    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h4 class=""><b>Detalle De Producto Terminado-Produccion</b></h4>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-9">
                                <h3>{{ $producto }}</h3>
                            </div>
                            <div class="col-lg-3">
                                <h4>Cantidad Requeridad : {{ $cantidad }}</h4>
                            </div>
                        </div>
                        <div class="ibox">
                            <div class="ibox-content">
                                <div class="panel-body">
                                    <div class="row m-t-sm">
                                        <div class="col-lg-12">
                                            <div class="table-responsive">
                                                <table
                                                    class="table dataTables-detalle-producto table-striped table-bordered table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th></th>
                                                            <th class="text-left">ARTÍCULO</th>
                                                            <th class="text-left">MEDIDA</th>
                                                            <th class="text-center">CANTIDAD x UNIDAD</th>
                                                            <th class="text-center">CANTIDAD REQUERIDA</th>
                                                            <th class="text-center">CANTIDAD ENVIADA</th>
                                                            <th class="text-center">ESTADO</th>
                                                            <th class="text-center">LOTES</th>

                                                        </tr>
                                                    </thead>
                                                    <tbody>

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <input type="hidden" name="articulos" id="articulos" value="{{ $articulos }}">
                                        <input type="hidden" name="cantidad" id="cantidad" value="{{ $cantidad }}">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group row">
                                            <div class="col-md-6 text-left">
                                                <i class="fa fa-exclamation-circle leyenda-required"></i> <small
                                                    class="leyenda-required">Los campos marcados con asterisco
                                                    (<label class="required"></label>) son obligatorios.</small>
                                            </div>
                                            <div class="col-md-6 text-right">
                                                <a href="{{ route('produccion.composicion.index') }}" id="btn_cancelar"
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('almacenes.programacion_produccion.modalote')
    @stop

    @push('styles')
        <link href="{{ asset('Inspinia/css/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css') }}"
            rel="stylesheet">
        <link href="{{ asset('Inspinia/css/plugins/dataTables/datatables.min.css') }}" rel="stylesheet">
        <link href="{{ asset('Inspinia/css/plugins/bootstrap-editable/bootstrap-editable.css') }}" rel="stylesheet">
        <style>
        </style>



    @endpush

    @push('scripts')
        <script src="{{ asset('Inspinia/js/plugins/dataTables/datatables.min.js') }}"></script>
        <script src="{{ asset('Inspinia/js/plugins/dataTables/dataTables.bootstrap4.min.js') }}"></script>
        <script src="{{ asset('Inspinia/js/plugins/bootstrap-editable/bootstrap-editable.min.js') }}"></script>

        <script>
            var table;
            var articulos = [];
            var cantidad;
            const getMethods = (obj) => {
                let properties = new Set()
                let currentObj = obj
                do {
                    Object.getOwnPropertyNames(currentObj).map(item => properties.add(item))
                } while ((currentObj = Object.getPrototypeOf(currentObj)))
                return [...properties.keys()].filter(item => typeof obj[item] === 'function')
            }

            //Modal Eliminar
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger',
                },
                buttonsStyling: false
            })

            $(document).ready(function() {
                //
                $.fn.editable.defaults.mode = 'inline';
                $('.dataTables-lotes').DataTable({
                    "bPaginate": true,
                    "bLengthChange": true,
                    "responsive": true,
                    "bFilter": true,
                    "bInfo": false,
                    "columnDefs": [{
                            "targets": 0,
                            "visible": false,
                            "searchable": false
                        },
                        {
                            searchable: false,
                            targets: -3,
                            data: null,
                            render: function(data, type, row) {

                                return '<a href="javascript:;" class="editable" data-type="text" data-pk="1" data-url="" ' +
                                    ' data-idlote="' + data[0] + '" data-lote="' + data[1] +
                                    '"  data-cantidad="' + data[2] + '" data-cantidadasignada="' +
                                    data[3] + '" data-fechavencimiento="' + data[4] + '" data-fila="' +
                                    data[5] + '"  data-title="Age:">' +
                                    data[3] + '</a>';
                            }

                        },
                        {
                            "targets": -1,
                            "visible": false,
                            "searchable": false
                        }

                    ],
                    'bAutoWidth': false,
                    'aoColumns': [{
                            sWidth: '0%'
                        },
                        {
                            sWidth: '20%',
                            sClass: 'text-left'
                        },
                        {
                            sWidth: '10%',
                            sClass: 'text-center'
                        },
                        {
                            sWidth: '10%',
                            sClass: 'text-center'
                        },
                        {
                            sWidth: '10%',
                            sClass: 'text-center'
                        },
                        {
                            sWidth: '0%'
                        },

                    ],
                    "language": {
                        url: "{{ asset('Spanish.json') }}"
                    },
                    "order": [
                        [0, "desc"]
                    ],
                    drawCallback: function(settings) {
                        var api = this.api();

                        $('.editable', api.table().body())
                            .editable()
                            .off('hidden')
                            .on('hidden', function(e, reason) {
                                if (reason === 'save') {
                                    var cantidad = parseFloat($(this).data('cantidad'));
                                    var cantidadasignada = parseFloat($(this).data('cantidadasignada'));
                                    var valor=parseFloat(e.target.firstChild.data);
                                    if(valor>cantidad)
                                    {
                                        $(this).editable('setValue',cantidadasignada);
                                        toastr.warning("La cantidad maxima es "+cantidad,"Advertencia")
                                    }
                                    else
                                    {
                                        var arreglo=[];
                                        $(this).data('cantidadasignada',valor);
                                        var fila=$(this).data('fila');
                                        var suma=0;
                                        $("#tablelote > tr").each(function(){
                                           $(this).children("td").each(function(index,td){
                                                if(index==2)
                                                {
                                                    var a=$(this).children("a");
                                                    arreglo.push(
                                                        {idlote: a.data('idlote'),
                                                        lote: a.data('lote'),
                                                        cantidad: a.data('cantidad'),
                                                        cantidadasignada:a.data('cantidadasignada'),
                                                        fechavencimiento:a.data('fechavencimiento')}
                                                    )
                                                    suma=suma+parseFloat(a.data('cantidadasignada'));
                                                }
                                           })
                                        })
                                        $("#ctotal").html(suma);
                                        var t= $('.dataTables-detalle-producto').DataTable();

                                        var ar=t.cell({row:fila,column:7}).data();
                                        ar[7]=arreglo;
                                    }
                                }
                            });
                    },
                });
                //
                table = $('.dataTables-detalle-producto').DataTable({
                    "dom": '<"html5buttons"B>lTfgitp',
                    "buttons": [{
                            extend: 'excelHtml5',
                            text: '<i class="fa fa-file-excel-o"></i> Excel',
                            titleAttr: 'Excel',
                            title: 'Detalle del Producto Terminado'
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
                    "responsive": true,
                    "bFilter": true,
                    "bInfo": false,
                    "columnDefs": [{
                            "targets": 0,
                            "visible": false,
                            "searchable": false
                        },
                        {
                            searchable: false,
                            targets: -4,
                            data: null,
                            render: function(data, type, row) {
                                var html;
                                if (data[5] >= data[4]) {
                                    html =
                                        "<div style='border: 1px solid;background-color:#1ab394;color:white;border-radius:5px;'>aceptado</div>"
                                } else {
                                    html =
                                        "<div style='border: 1px solid;background-color:rgb(255,15,0);color:white;border-radius:5px;'>denegado</div>"
                                }
                                return html;
                            }
                        },
                        {
                            searchable: false,
                            targets: -3,
                            data: null,
                            defaultContent: "<button type='button' class='btn btn-sm btn-warning mr-1 btn-edit'>" +
                                "<i class='fa fa-location-arrow'></i>" +
                                "</button>"
                        },
                        {
                            "targets": -2,
                            "visible": false,
                            "searchable": false,
                            "data": null
                        },
                        {
                            "targets": -1,
                            "visible": false,
                            "searchable": false,
                            "data": null
                        },
                    ],
                    'bAutoWidth': false,
                    'aoColumns': [{
                            sWidth: '0%'
                        },
                        {
                            sWidth: '30%',
                            sClass: 'text-left'
                        },
                        {
                            sWidth: '10%',
                            sClass: 'text-center'
                        },
                        {
                            sWidth: '10%',
                            sClass: 'text-center'
                        },
                        {
                            sWidth: '10%',
                            sClass: 'text-center'
                        },
                        {
                            sWidth: '10%',
                            sClass: 'text-center'
                        },
                        {
                            sWidth: '10%',
                            sClass: 'text-center'
                        },
                        {
                            sWidth: '0%'
                        },
                        {
                            sWidth: '0%'
                        }
                    ],
                    'data': getData(),
                    "language": {
                        url: "{{ asset('Spanish.json') }}"
                    },
                    "order": [
                        [0, "desc"]
                    ],
                });
            });


            function getData() {
                articulos = ($("#articulos").val() === undefined || $("#articulos").val() === "") ? [] : JSON.parse($(
                        "#articulos")
                    .val());
                cantidad = $("#cantidad").val();
                var data = [];
                articulos.forEach(obj => {
                    data.push([
                        String(obj.idarticulo),
                        String(obj.descripcion),
                        String(obj.medida),
                        parseFloat(obj.cantidad),
                        parseFloat(obj.cantidad * cantidad),
                        parseFloat(obj.cantidadLote),
                        "",
                        obj.lotes,
                        obj.comentario
                    ]);
                });
                return data;
            }
            $(document).on('click', '.btn-edit', function(event) {
                var table = $('.dataTables-detalle-producto').DataTable();
                var data = table.row($(this).parents('tr')).data();
                var fila = table.row($(this).parents('tr')).index();
                var cantidad=data[4];
                var articulo=data[1];
                var datoslotes = data[7];
                var comentario= data[8];
                $("#modal_lote #Articulo").html(articulo);
                $("#modal_lote #CantidadRequeridad").html("Cantidad:"+cantidad);
                $("#modal_lote #indice").val(fila);
                $("#modal_lote #comentario").val(comentario);
                var t = $('.dataTables-lotes').DataTable();
                t.clear().draw();
                var suma=0;
                datoslotes.forEach((value) => {
                    t.row.add([
                        value.idlote,
                        value.lote,
                        parseFloat(value.cantidad),
                        parseFloat(value.cantidadasignada),
                        value.fechavencimiento,
                        fila
                    ]).draw(false);
                    suma=suma+value.cantidadasignada;
                })
                $("#ctotal").html(suma);


                $("#modal_lote").modal('show');

                /*$('#modal_editar_detalle #indice').val(table.row($(this).parents('tr')).index());
                $('#modal_editar_detalle #lote').val(data[3]);
                $('#modal_editar_detalle #cantidad').val(data[2]);
                $('#modal_editar_detalle #prod_id').val(data[0]);
                $('#modal_editar_detalle #fechavencimiento').val(data[5]);
                $('#modal_editar_detalle').modal('show');
                $("#modal_editar_detalle #producto").val(data[0]).trigger('change');*/

            });
        </script>
    @endpush
