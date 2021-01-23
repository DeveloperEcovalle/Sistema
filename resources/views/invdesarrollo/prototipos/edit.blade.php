<div class="modal inmodal" id="modal_editar_prototipo" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content animated bounceInRight">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span>
                </button>
                <i class="fa fa-cogs modal-icon"></i>
                <h4 class="modal-title">Prototipo</h4>
                <small class="font-bold">Modificar Prototipo.</small>
            </div>
            <div class="modal-body">
                <form role="form" action="{{route('invdesarrollo.prototipo.update')}}" method="POST" enctype="multipart/form-data" id="editar_prototipo">
                    {{ csrf_field() }} {{method_field('PUT')}}

                   <input type="hidden" name="id" id="id_editar" value="{{old('id')}}">
   
   
                    <div class="form-group">
                        <label>Producto :</label>
                        <input type="text" id="producto_editar" name="producto" class="form-control {{ $errors->has('producto') ? ' is-invalid' : '' }}" value="{{old('producto')}}" >
                        @if ($errors->has('producto'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('producto') }}</strong>
                        </span>
                        @endif
                    </div>

                   <div class="form-group">
                        <label>Fecha Registro:</label> 
                        <input type="date" class="form-control {{ $errors->has('fecha_registro') ? ' is-invalid' : '' }}" name="fecha_registro" id="fecha_registro_editar" value="{{old('fecha_registro')}}" onkeyup="return mayus(this)">
                        
                        @if ($errors->has('fecha_registro'))
                        <span class="invalid-feedback" role="alert">
                            <strong id="error-fecha_registro">{{ $errors->first('fecha_registro') }}</strong>
                        </span>
                        @endif
                    </div>

                   <div class="form-group">
                        <label>Fecha Inicio:</label> 
                        <input type="date" class="form-control {{ $errors->has('fecha_inicio') ? ' is-invalid' : '' }}" name="fecha_inicio" id="fecha_inicio_editar" value="{{old('fecha_inicio')}}" onkeyup="return mayus(this)">
                        
                        @if ($errors->has('fecha_inicio'))
                        <span class="invalid-feedback" role="alert">
                            <strong id="error-fecha_inicio">{{ $errors->first('fecha_inicio') }}</strong>
                        </span>
                        @endif
                    </div>

                   <div class="form-group">
                        <label>Fecha Fin:</label> 
                        <input type="date" class="form-control {{ $errors->has('fecha_fin') ? ' is-invalid' : '' }}" name="fecha_fin" id="fecha_fin_editar" value="{{old('fecha_fin')}}" onkeyup="return mayus(this)">
                        
                        @if ($errors->has('fecha_fin'))
                        <span class="invalid-feedback" role="alert">
                            <strong id="error-fecha_fin">{{ $errors->first('fecha_fin') }}</strong>
                        </span>
                        @endif
                    </div>

                   <div class="form-group">
                        <label>Linea Caja Texto Registrar:</label> 
                        <input type="text" class="form-control {{ $errors->has('linea_caja_texto_registrar') ? ' is-invalid' : '' }}" name="linea_caja_texto_registrar" id="linea_caja_texto_registrar_editar" value="{{old('linea_caja_texto_registrar')}}" onkeyup="return mayus(this)">
                        
                        @if ($errors->has('linea_caja_texto_registrar'))
                        <span class="invalid-feedback" role="alert">
                            <strong id="error-linea_caja_texto_registrar">{{ $errors->first('linea_caja_texto_registrar') }}</strong>
                        </span>
                        @endif
                    </div>

                   <div class="form-group">
                        <label>Imagen:</label> 
                        

                        <input type="file" class="form-control {{ $errors->has('imagen') ? ' is-invalid' : '' }}" name="imagen" id="imagen_editar" value="{{old('imagen')}}" onkeyup="return mayus(this)">
                        
                        @if ($errors->has('imagen'))
                        <span class="invalid-feedback" role="alert">
                            <strong id="error-imagen">{{ $errors->first('imagen') }}</strong>
                        </span>
                        @endif
                    </div>
                    <div class="form-group row justify-content-center">
                        <div class="col-6 align-content-center">
                            <div class="row justify-content-end">
                                <a href="javascript:void(0);" id="limpiar_logo">
                                    <span class="badge badge-danger">x</span>
                                </a>
                            </div>
                            <div class="row justify-content-center">
                                <p>
                                    @if(true)
                                    <img class="imagen" id="ruta_imagen" src="{{Storage::url('public/prototipos/VPb0x5fmoWG6NSJrsTI7yIlrNaKdgYDKQcb5cmjf.jpg')}}" alt="">
                                    <input id="url_imagen" name="url_imagen" type="hidden"
                                        value="imagen">
                                    @else
                                    <img class="imagen" src="{{asset('storage/empresas/logos/default.png')}}"
                                        alt="">
                                    <input id="url_imagen" name="url_imagen" type="hidden" value="">
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                     <div class="form-group">
                        <label>Archivo Word:</label> 
                        <input type="file" class="form-control {{ $errors->has('archivo_word') ? ' is-invalid' : '' }}" name="archivo_word" id="archivo_word_editar" value="{{old('archivo_word')}}" onkeyup="return mayus(this)" >
                        
                        @if ($errors->has('archivo_word'))
                        <span class="invalid-feedback" role="alert">
                            <strong id="error-archivo_word">{{ $errors->first('archivo_word') }}</strong>
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
