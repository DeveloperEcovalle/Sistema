<div class="modal inmodal" id="modal_crear_prototipo" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content animated bounceInRight">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span>
                </button>
                <i class="fa fa-cogs modal-icon"></i>
                <h4 class="modal-title">Prototipo</h4>
                <small class="font-bold">Crear nuevo Prototipo.</small>
            </div>
            <div class="modal-body">
                <form role="form" action="{{route('invdesarrollo.prototipo.store')}}" method="POST" id="crear_prototipo" enctype="multipart/form-data">
                    {{ csrf_field() }} {{method_field('POST')}}

                    <div class="form-group">
                        <label>Producto :</label>
                        <input type="text" id="producto" name="producto" class="form-control {{ $errors->has('producto') ? ' is-invalid' : '' }}" value="{{old('producto')}}" >
                        @if ($errors->has('producto'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('producto') }}</strong>
                        </span>
                        @endif
                    </div>

                   <div class="form-group">
                        <label>Fecha Registro:</label> 
                        <input type="date" class="form-control {{ $errors->has('fecha_registro') ? ' is-invalid' : '' }}" name="fecha_registro" id="fecha_registro" value="{{old('fecha_registro')}}" onkeyup="return mayus(this)">

                        @if ($errors->has('fecha_registro'))
                        <span class="invalid-feedback" role="alert">
                            <strong id="error-fecha_registro-guardar">{{ $errors->first('fecha_registro') }}</strong>
                        </span>
                        @endif
                    </div>

                   <div class="form-group">
                        <label>Fecha Inicio:</label> 
                        <input type="date" class="form-control {{ $errors->has('fecha_inicio') ? ' is-invalid' : '' }}" name="fecha_inicio" id="fecha_inicio" value="{{old('fecha_inicio')}}" onkeyup="return mayus(this)">

                        @if ($errors->has('fecha_inicio'))
                        <span class="invalid-feedback" role="alert">
                            <strong id="error-fecha_inicio-guardar">{{ $errors->first('fecha_inicio') }}</strong>
                        </span>
                        @endif
                    </div>

                   <div class="form-group">
                        <label>Fecha Fin:</label> 
                        <input type="date" class="form-control {{ $errors->has('fecha_fin') ? ' is-invalid' : '' }}" name="fecha_fin" id="fecha_fin" value="{{old('fecha_fin')}}" onkeyup="return mayus(this)">

                        @if ($errors->has('fecha_fin'))
                        <span class="invalid-feedback" role="alert">
                            <strong id="error-fecha_fin-guardar">{{ $errors->first('fecha_fin') }}</strong>
                        </span>
                        @endif
                    </div>

                   <div class="form-group">
                        <label>Registro:</label> 
                        <input type="text" class="form-control {{ $errors->has('registro') ? ' is-invalid' : '' }}" name="registro" id="registro" value="{{old('registro')}}" onkeyup="return mayus(this)">

                        @if ($errors->has('registro'))
                        <span class="invalid-feedback" role="alert">
                            <strong id="error-registro-guardar">{{ $errors->first('registro') }}</strong>
                        </span>
                        @endif
                    </div>

                   <div class="form-group">
                        <label>Imagen:</label> 
                        <input type="file" class="form-control {{ $errors->has('imagen') ? ' is-invalid' : '' }}" name="imagen" id="imagen" value="{{old('imagen')}}" onkeyup="return mayus(this)">

                        @if ($errors->has('imagen'))
                        <span class="invalid-feedback" role="alert">
                            <strong id="error-imagen-guardar">{{ $errors->first('imagen') }}</strong>
                        </span>
                        @endif
                    </div>

                    
                   <div class="form-group">
                        <label>Archivo Word:</label> 
                        <input type="file" class="form-control {{ $errors->has('archivo_word') ? ' is-invalid' : '' }}" name="archivo_word" id="archivo_word" value="{{old('archivo_word')}}" onkeyup="return mayus(this)">

                        @if ($errors->has('archivo_word'))
                        <span class="invalid-feedback" role="alert">
                            <strong id="error-archivo_word-guardar">{{ $errors->first('archivo_word') }}</strong>
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