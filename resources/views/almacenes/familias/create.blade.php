<div class="modal inmodal" id="modal_crear_familia" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content animated bounceInRight">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span>
                </button>
                <i class="fa fa-cogs modal-icon"></i>
                <h4 class="modal-title">Familia</h4>
                <small class="font-bold">Crear nueva Familia.</small>
            </div>
            <div class="modal-body">
                <form role="form" action="{{route('almacenes.familias.store')}}" method="POST" id="crear_familia">
                    {{ csrf_field() }} {{method_field('POST')}}

                   <div class="form-group">
                        <label class="required">Familia:</label> 
                        <input type="text" class="form-control {{ $errors->has('familia_guardar') ? ' is-invalid' : '' }}" name="familia_guardar" id="familia_guardar" value="{{old('familia_guardar')}}"   onkeyup="return mayus(this)" required>

                        @if ($errors->has('familia_guardar'))
                        <span class="invalid-feedback" role="alert">
                            <strong id="error-familia-guardar">{{ $errors->first('familia_guardar') }}</strong>
                        </span>
                        @endif
                    </div>
            </div>
                
                    <div class="modal-footer">
                        <div class="col-md-6 text-left" style="color:#fcbc6c">
                            <i class="fa fa-exclamation-circle"></i> <small>Los campos marcados con asterisco (<label class="required"></label>) son obligatorios.</small>
                        </div>
                        <div class="col-md-6 text-right">
                            <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-save"></i> Guardar</button>
                            <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i class="fa fa-times"></i> Cancelar</button>
                        </div>
                    </div>
                
                </form>
        </div>
    </div>
</div>