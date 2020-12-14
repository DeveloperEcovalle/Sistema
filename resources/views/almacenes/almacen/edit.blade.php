<div class="modal inmodal" id="modal_editar_almacen" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content animated bounceInRight">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span>
                </button>
                <i class="fa fa-cogs modal-icon"></i>
                <h4 class="modal-title">Almacen</h4>
                <small class="font-bold">Modificar Almacen.</small>
            </div>
            <div class="modal-body">
                <form role="form" action="{{route('almacenes.almacen.update')}}" method="POST" id="editar_almacen">
                    {{ csrf_field() }} {{method_field('PUT')}}

                   <input type="hidden" name="tabla_id" id="tabla_id_editar" value="{{old('tabla_id')}}">
                   
                   <div class="form-group">
                        <label class="required">Descripción:</label> 
                        <input type="text" class="form-control {{ $errors->has('descripcion') ? ' is-invalid' : '' }}" name="descripcion" id="descripcion_editar" value="{{old('descripcion')}}" style="text-transform:uppercase" required>
                        
                        @if ($errors->has('descripcion'))
                        <span class="invalid-feedback" role="alert">
                            <strong id="error-descripcion">{{ $errors->first('descripcion') }}</strong>
                        </span>
                        @endif
                    </div>

                    <div class="form-group">
                        
                        <label class="required">Ubicación:</label>
                        <input type="text" class="form-control {{ $errors->has('ubicacion') ? ' is-invalid' : '' }}" id="ubicacion_editar" name="ubicacion" value="{{old('ubicacion')}}" style="text-transform:uppercase" required>

                        @if ($errors->has('ubicacion'))
                        <span class="invalid-feedback" role="alert">
                            <strong id="error-ubicacion">{{ $errors->first('ubicacion') }}</strong>
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