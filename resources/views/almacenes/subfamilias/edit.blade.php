<div class="modal inmodal" id="modal_subfamilia" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content animated bounceInRight">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span>
                </button>
                <i class="fa fa-cogs modal-icon"></i>
                <h4 class="modal-title">Sub Categoria</h4>
                <small class="font-bold">Modificar sub categoria.</small>
            </div>
            <div class="modal-body">
                <form role="form" action="{{route('almacenes.subfamilia.update')}}" method="POST" id="editar_subfamilia">
                    {{ csrf_field() }} {{method_field('PUT')}}

                   <input type="hidden" name="sub_familia_id_editar" id="sub_familia_id_editar" value="{{old('sub_familia_id_editar')}}">
                   <input type="hidden" name="familia_id_2_editar" id="familia_id_2_editar" value="{{old('familia_id_2_editar')}}">

                   
                   <div class="form-group">
                        
                        <label class="required">Descripción :</label>
                        <input type="text" class="form-control {{ $errors->has('descripcion_editar') ? ' is-invalid' : '' }}" id="descripcion_editar" name="descripcion_editar" value="{{old('descripcion_editar')}}" onkeyup="return mayus(this)" required>
                        
                        @if ($errors->has('descripcion_editar'))
                        <span class="invalid-feedback" role="alert">
                            <strong id="error-descripcion-guardar">{{ $errors->first('descripcion_editar') }}</strong>
                        </span>
                        @endif
                        
                    </div>


                   <div class="form-group">
                        <label class="required">Categoria:</label> 
                        <select class="form-control  {{ $errors->has('familia_id_editar') ? ' is-invalid' : '' }}" style="text-transform: uppercase; width:100%" value="{{old('familia_id_editar')}}" name="familia_id_editar" id="familia_id_editar"  required>
                            <option></option>
                        </select>

                        @if ($errors->has('familia_id_editar'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('familia_id_editar') }}</strong>
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

@push('styles')
<link href="{{asset('Inspinia/css/plugins/select2/select2.min.css')}}" rel="stylesheet">
@endpush 
@push('scripts')
<!-- Select2 -->
<script src="{{asset('Inspinia/js/plugins/select2/select2.full.min.js')}}"></script>

<script>

    //Select2
    $(".select2_form").select2({
        placeholder: "SELECCIONAR",
        allowClear: true,
        height: '200px',
        width: '100%',
    });
    $("#familia_id_editar").select2({
        placeholder: "SELECCIONAR",
        allowClear: true,
        height: '200px',
        width: '100%',
    });

</script>

@endpush