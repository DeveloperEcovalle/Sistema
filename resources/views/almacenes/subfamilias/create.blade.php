<div class="modal inmodal" id="modal_sub_familia" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content animated bounceInRight">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span>
                </button>
                <i class="fa fa-cogs modal-icon"></i>
                <h4 class="modal-title">Sub Familia</h4>
                <small class="font-bold">Crear nueva sub familia.</small>
            </div>
            <div class="modal-body">
                <form role="form" action="{{route('almacenes.subfamilia.store')}}" method="POST" id="crear_subfamilia">
                    {{ csrf_field() }} {{method_field('POST')}}



                    <div class="form-group">
                        
                        <label class="required">Descripción:</label>
                        <input type="text" class="form-control {{ $errors->has('descripcion') ? ' is-invalid' : '' }}" id="descripcion" name="descripcion" value="{{old('descripcion')}}" onkeyup="return mayus(this)" required>
                        
                        @if ($errors->has('descripcion'))
                        <span class="invalid-feedback" role="alert">
                            <strong id="error-saldo_inicial-guardar">{{ $errors->first('descripcion') }}</strong>
                        </span>
                        @endif
                    
                    </div>

                    <div class="form-group">
                        <label class="required">Familia:</label> 
                        <select class="form-control {{ $errors->has('familia_id') ? ' is-invalid' : '' }}" style="text-transform: uppercase; width:100%" value="{{old('familia_id')}}" name="familia_id" id="familia_id" required>
                            <option></option>
                        </select>

                        @if ($errors->has('familia_id'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('familia_id') }}</strong>
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

    $('#familia_id').select2({
        placeholder: "SELECCIONAR",
        allowClear: true,
        height: '200px',
        width: '100%',
	    ajax: {
			url: "{{route('subfamilia.familia')}}",
            dataType: 'json',
            type: 'GET',
            
            processResults: function (data) {
  
                return {
					results: $.map(data, function (item) {
						return {
                            id: item.id,
                            text: item.familia
                            }
						})
					}
				}
				
            },
	});



</script>

@endpush