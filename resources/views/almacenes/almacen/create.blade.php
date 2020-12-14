<div class="modal inmodal" id="modal_crear_almacen" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content animated bounceInRight">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span>
                </button>
                <i class="fa fa-cogs modal-icon"></i>
                <h4 class="modal-title">Almacen</h4>
                <small class="font-bold">Crear nuevo Almacen.</small>
            </div>
            <div class="modal-body">
                <form role="form" action="{{route('almacenes.almacen.store')}}" method="POST" id="crear_almacen">
                    {{ csrf_field() }} {{method_field('POST')}}

                   <div class="form-group">
                        <label class="required">Descripción:</label> 
                        <input type="text" class="form-control {{ $errors->has('descripcion_guardar') ? ' is-invalid' : '' }}" name="descripcion_guardar" id="descripcion_guardar" value="{{old('descripcion_guardar')}}" style="text-transform:uppercase" required>

                        @if ($errors->has('descripcion_guardar'))
                        <span class="invalid-feedback" role="alert">
                            <strong id="error-descripcion-guardar">{{ $errors->first('descripcion_guardar') }}</strong>
                        </span>
                        @endif
                    </div>

                    <div class="form-group">
                        
                        <label class="required">Ubicación:</label>
                        <input type="text" class="form-control {{ $errors->has('ubicacion_guardar') ? ' is-invalid' : '' }}" id="ubicacion_guardar" name="ubicacion_guardar" value="{{old('ubicacion_guardar')}}" style="text-transform:uppercase" required>
                        
                        @if ($errors->has('ubicacion_guardar'))
                        <span class="invalid-feedback" role="alert">
                            <strong id="error-ubicacion-guardar">{{ $errors->first('ubicacion_guardar') }}</strong>
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