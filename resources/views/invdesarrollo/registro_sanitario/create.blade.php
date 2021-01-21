<div class="modal inmodal" id="modal_crear_registro_sanitario" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content animated bounceInRight">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span>
                </button>
                <i class="fa fa-cogs modal-icon"></i>
                <h4 class="modal-title">Registro Sanitario</h4>
                <small class="font-bold">Crear nuevo Registro Sanitario.</small>
            </div>
            <div class="modal-body">
                <form role="form" action="{{route('invdesarrollo.registro_sanitario.store')}}" method="POST" id="crear_registro_sanitario" enctype="multipart/form-data">
                    {{ csrf_field() }} {{method_field('POST')}}

                    <div class="form-group">
                        <label class="required">Productos : </label>
                        <select
                            class="select2_form form-control {{ $errors->has('producto_id') ? ' is-invalid' : '' }}"
                            style="text-transform: uppercase; width:100%" value="{{old('producto_id')}}"
                            name="producto_id" id="producto_id" required>
                            <option></option>
                            @foreach ($productos as $producto)
                            <option value="{{$producto->id}}" @if(old('producto_id')==$producto->id )
                                {{'selected'}} @endif >{{$producto->nombre}}</option>
                            @endforeach
                        </select>
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
                        <label>Archivo Word:</label> 
                        <input type="file" class="form-control {{ $errors->has('archivo_word') ? ' is-invalid' : '' }}" name="archivo_word" id="archivo_word" value="{{old('archivo_word')}}" onkeyup="return mayus(this)">

                        @if ($errors->has('archivo_word'))
                        <span class="invalid-feedback" role="alert">
                            <strong id="error-archivo_word-guardar">{{ $errors->first('archivo_word') }}</strong>
                        </span>
                        @endif
                    </div>

                   <div class="form-group">
                        <label>Archivo Pdf:</label> 
                        <input type="file" class="form-control {{ $errors->has('archivo_pdf') ? ' is-invalid' : '' }}" name="archivo_pdf" id="archivo_pdf" value="{{old('archivo_pdf')}}" onkeyup="return mayus(this)">

                        @if ($errors->has('archivo_pdf'))
                        <span class="invalid-feedback" role="alert">
                            <strong id="error-archivo_pdf-guardar">{{ $errors->first('archivo_pdf') }}</strong>
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