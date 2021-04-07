<div class="modal inmodal" id="modal_lote" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content animated bounceInRight">
            <div class="modal-header">
                <button type="button" onclick="limpiarModallote()" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span>
                </button>
                <i class="fa fa-cogs modal-icon"></i>
                <h4 class="modal-title"></h4>
                <small class="font-bold"></small>
            </div>
            <div class="modal-body">
                <div class="form-group m-l">
                    <span><b>Instrucciones:</b> Doble click en el registro del producto a vender.</span>
                </div>
                <div class="form-group">
                    <div class="table-responsive m-t">
                        <table class="table dataTables-lotes table-bordered" style="width:100%; text-transform:uppercase;" id="table_lotes">
                            <thead>
                            <tr>
                                <th class="text-center"></th>
                                <th class="text-center">PRODUCTO</th> 
                                <th class="text-center">UNIME</th>
                                <th class="text-center">LOTE</th>
                                <th class="text-center">FECHA VENCE.</th>
                                <th class="text-center">CANTID.</th>
                                <th class="text-center">FAMILIA</th>
                                <th class="text-center">PREC.VENTA</th>

                            </tr>
                            </thead>
                            <tbody>

                            </tbody>

                        </table>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <div class="col-md-6 text-left">
                    <i class="fa fa-exclamation-circle leyenda-required"></i> <small class="leyenda-required">Seleccionar el lote del producto a vender.</small>
                </div>
                <div class="col-md-6 text-right">
                    <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i class="fa fa-times"></i> Cancelar</button>
                </div>
            </div>

        </div>
    </div>
</div>

@push('styles')
<!-- DataTable -->
<link href="{{asset('Inspinia/css/plugins/dataTables/datatables.min.css')}}" rel="stylesheet">
<style>

    @media (min-width: 992px){
        .modal-lg {
            max-width: 1200px;
        }
    }

    #table_lotes div.dataTables_wrapper div.dataTables_filter{
        text-align: left !important;
    }
    #table_lotes tr[data-href] {
        cursor: pointer;
    }
    #table_lotes tbody .fila_lote.selected {
        /* color: #151515 !important;*/
        font-weight: 400; 
        color: white !important;
        background-color: #18a689 !important;
        /* background-color: #CFCFCF !important; */
    }

    #modal_lote  div.dataTables_wrapper div.dataTables_filter{
        text-align:left !important;
    }


    @media only screen and (max-width: 992px) {

        #table_tabla_registro_filter{
            text-align:left;
        }

        #table_lotes_filter{
            text-align: left;
        }
        #table_lotes  div.dataTables_wrapper div.dataTables_paginate ul.pagination {
            float:left;
            margin: 10px 0;
            white-space: nowrap;
        }

    }

    @media only screen and (min-width: 428px) and (max-width: 1190px) {
        /* Para tables: */
        #modal_lote div.dataTables_filter input { 
            width: 175% !important;
            display: inline-block !important;
        }
    }

    @media only screen and (max-width: 428px) {
        /* Para celular: */
        #modal_lote  div.dataTables_filter input { 
            width: 100% !important;
            display: inline-block !important;
        }
    }

    @media only screen and (min-width: 1190px) {

        #modal_lote div.dataTables_filter input { 
            width: 363% !important;
            display: inline-block !important;
        }
    }
</style>
@endpush

@push('scripts')
<!-- DataTable -->
<script src="{{asset('Inspinia/js/plugins/dataTables/datatables.min.js')}}"></script>
<script>


function obtenerLotesproductos(tipo_cliente) {
    //RUTA LOTES PRODUCTOS
    var url = '{{ route("ventas.getLot", ":id")}}';
    url = url.replace(':id', tipo_cliente);
    //ELIMINAR EL DATATABLE PARA VOLVER A INSTANCIARLO
    $(".dataTables-lotes").dataTable().fnDestroy();
    //INSTANCIAR DATATABLE
    var lotes = $('.dataTables-lotes').DataTable({
        "dom": 
                "<'row'<'col-sm-12 col-md-12 col-lg-12'f>>" +
                "<'row'<'col-sm-12'tr>>"+ 
                "<'row justify-content-between'<'col information-content p-0'i><''p>>",

        "bPaginate": true,
        "serverSide":true,
        "processing":true,
        "ajax": url,
        "columns": [
            {data: 'id', className: "text-center", name:"lote_productos.id" ,visible: false},
            {data: 'nombre', className: "text-left", name:"productos.nombre" },
            {data: 'unidad_producto', className: "text-center", name:"tabladetalles.simbolo" },
            {data: 'codigo', className: "text-center", name:"lote_productos.codigo" },
            {data: 'fecha_venci', className: "text-center", name:"lote_productos.fecha_vencimiento" },
            {data: 'cantidad_logica', className: "text-center", name:"lote_productos.cantidad_logica" },
            {data: 'familia', className: "text-center", name:"familias.familia" },
            {data: 'precio_venta', className: "text-center", name:"productos_clientes.monto" },
        ],
        "bLengthChange": true,
        "bFilter": true,
        "order": [],
        "bInfo": true,
        "bAutoWidth": false,
        "language": {
                    "url": "{{asset('Spanish.json')}}"
        },
        createdRow: function(row, data, dataIndex, cells) {
            $(row).addClass('fila_lote');
            $(row).attr('data-href', "");
        },


    });
}

$(document).ready(function() {
   
    $('buttons-html5').removeClass('.btn-default');
    $('#table_lotes_wrapper').removeClass('');

    $('.dataTables-lotes tbody').on( 'click', 'tr', function () {
            $('.dataTables-lotes').DataTable().$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
    } );

    //DOBLE CLICK EN LOTES
    $ ('.dataTables-lotes'). on ('dblclick', 'tbody td', function () {
        var lote =  $('.dataTables-lotes').DataTable();
        var data = lote.row(this).data();
        ingresarProducto(data)
    });

})

function ingresarProducto(producto) {
    //LIMPIAR ERRORES AL INGRESAR PRODUCTO LOTE
    limpiarErrores()
    //HABILITAR CAMPOS DEL PRODUCTO
    $('#precio').prop('disabled' , false)
    $('#cantidad').prop('disabled' , false)
    $('#btn_agregar_detalle').prop('disabled' , false)
    //INGRESAR DATOS DEL PRODUCTO A LOS CAMPOS
    $('#precio').val(evaluarPrecioigv(producto))
    $('#cantidad').val(producto.cantidad_logica)
    $('#producto_unidad').val(producto.unidad_producto)
    $('#producto_id').val(producto.id)
    $('#producto_lote').val(producto.nombre+' - '+ producto.codigo)
    //AGREGAR LIMITE A LA CANTIDAD SEGUN EL LOTE SELECCIONADO
    $("#cantidad").attr({ 
        "max" : producto.cantidad_logica,
        "min" : 1,
    });
    $("#precio").attr({ 
        "min" : 1,
    });
    //LIMPIAR MODAL
    limpiarModallote()
}

function evaluarPrecioigv(producto) {
    if ( producto.moneda == 4 ) {
        //PRODUCTO SIN IGV
        if ( producto.igv == '0' ) {
            var precio = Number(producto.precio_venta * 0.18) + Number(producto.precio_venta)
            return Number(precio).toFixed(2)
        }else{
            //PRODUCTO CON IGV
            var precio = producto.precio_venta
            return Number(precio).toFixed(2)
        }                        
    }else{
        toastr.error('Precio registrado diferente a Soles (S/.).', 'Error');
        return 0.00
    }
}

function limpiarModallote() {
    //ACTUALIZAR DATATABLE
    $('.dataTables-lotes').DataTable().ajax.reload();
    //CERRAR MODAL
    $('#modal_lote').modal('hide');
}
//AL ABRIR EL MODAL SE DEBE DE ACTUALIZAR EL DATATABLE 
$('#modal_lote').on('show.bs.modal', function(e) {
    //ACTUALIZAR DATATABLE
    $('.dataTables-lotes').DataTable().ajax.reload();
});


</script>
@endpush