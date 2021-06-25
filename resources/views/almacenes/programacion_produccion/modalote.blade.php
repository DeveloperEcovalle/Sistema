<div class="modal inmodal" id="modal_lote" >
    <div class="modal-dialog modal-lg">
        <div class="modal-content animated bounceInRight">
            <div class="modal-header">
                <i class="fa fa-cogs modal-icon"></i>
                <h4 class="modal-title"></h4>
                <small class="font-bold"></small>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <div class="row">
                        <div class="col-lg-8" id="Articulo" style="font-weight: bold; font-size:14px">
                            Articulo
                        </div>
                        <div class="col-lg-4" id="CantidadRequeridad"  style="font-weight: bold; font-size:14px">
                            Cantidad
                        </div>
                    </div>
                    <input type="hidden" id="indice" name="indice">
                    <div class="table-responsive m-t">
                        <table class="table dataTables-lotes table-bordered"
                            style="width:100%; text-transform:uppercase;" id="table_lotes">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th class="text-center">LOTE</th>
                                    <th class="text-center">CANTIDAD</th>
                                    <th class="text-center">CANTIDAD ASIGNADA</th>
                                    <th class="text-center">FECHA VENCIMIENTO</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="tablelote">
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th></th>
                                    <th colspan="2">Cantidad Total</th>
                                    <th id="ctotal"></th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div>
                    <label for="">Comentario:</label>
                    <input type="text" name="comentario" id="comentario" class="form-control">
                        {{-- <textarea name="comentario" id="comentario" cols="20" rows="3" class="form-control">

                        </textarea> --}}
                </div>
            </div>
            <div class="modal-footer">
                <div class="col-md-6 text-left">
                    <i class="fa fa-exclamation-circle leyenda-required"></i> <small
                        class="leyenda-required">Seleccionar el lote del producto a vender.</small>
                </div>
                <div class="col-md-6 text-right">
                    <button type="button" id="guardar" class="btn btn-success btn-sm" data-dismiss="modal"><i class="fa fa-check"></i>
                        Guardar</button>
                    <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i class="fa fa-times"></i>
                            Cancelar</button>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
    <script>
        $("#guardar").on("click", function(){
            var fila= $("#modal_lote #indice").val();
            var t=$(".dataTables-detalle-producto").DataTable();
             //t.cell({row:fila,column:7}).data($("#comentario").val())
             var ar=t.cell({row:fila,column:8}).data();
             ar[8]=$("#comentario").val();
        })
    </script>
@endpush
