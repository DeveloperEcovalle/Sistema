<div class="modal inmodal" id="modal_editar_detalle" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content animated bounceInRight">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span>
                </button>
                <i class="fa fa-cogs modal-icon"></i>
                <h4 class="modal-title">Detalle de la cotización</h4>
                <small class="font-bold">Editar detalle</small>
            </div>
            <div class="modal-body">
                <input type="hidden" id="id_editar" name="id_editar">
                <div class="form-group">
                    <label class="required">Producto</label>
                    <select id="producto_editar" name="producto_editar" class="select2_form form-control" disabled>
                        <option></option>
                        @foreach($productos as $producto)
                            <option value="{{ $producto->id }}">{{ $producto->getDescripcionCompleta() }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group row">
                    <div class="col-lg-6 col-xs-12">
                        <label class="required">Cantidad</label>
                        <input type="text" id="cantidad_editar" name="cantidad_editar" class="form-control" maxlength="10" onkeypress="return isNumber(event);" required>
                    </div>
                    <div class="col-lg-6 col-xs-12">
                        <label class="required">Precio</label>
                        <input type="text" id="precio_editar" name="precio_editar" class="form-control" maxlength="15" onkeypress="return filterFloat(event, this, true);" required>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <div class="col-md-6 text-left">
                    <i class="fa fa-exclamation-circle leyenda-required"></i> <small class="leyenda-required">Los campos marcados con asterisco (<label class="required"></label>) son obligatorios.</small>
                </div>
                <div class="col-md-6 text-right">
                    <button type="button" id="btn_editar_detalle" class="btn btn-primary btn-sm"><i class="fa fa-save"></i> Guardar</button>
                    <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i class="fa fa-times"></i> Cancelar</button>
                </div>
            </div>

        </div>
    </div>
</div>
