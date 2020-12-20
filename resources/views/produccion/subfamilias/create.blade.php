@extends('layout') @section('content')

@section('produccion-active', 'active')
@section('subfamilia-active', 'active')
<div class="row wrapper border-bottom white-bg page-heading">
    
    <div class="col-lg-12">
        <h2 style="text-transform:uppercase;"><b>REGISTRAR NUEVA SUB FAMILIA</b></h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{route('home')}}">Panel de Control</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{route('produccion.subfamilia.index')}}">Sub familia</a>
            </li>
            <li class="breadcrumb-item active">
                <strong>Registrar</strong>
            </li>

            
        </ol>
    </div>



</div>


<div class="wrapper wrapper-content animated fadeInRight">

    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
                
                <div class="ibox-content">

                     <form action="{{route('produccion.subfamilia.store')}}" method="POST" id="enviar_subfamilia">
                        {{csrf_field()}}
              
                        <div class="row">
                            <div class="col-sm-6 b-r"><h4 class=""><b>Sub Familia</b></h4>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <p>Registrar datos de la Nueva Sub Familia:</p>
                                        </div>
                                    </div>                                        


                                    <div class="col-md-6">
                                            <label class="required">Descripción: </label>                                                     
                                            <input type="text" id="descripcion" class="form-control {{ $errors->has('descripcion') ? ' is-invalid' : '' }}" name="descripcion" value="{{ old('descripcion')}}"  style="text-transform:uppercase;" required>

                                            @if ($errors->has('descripcion'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('descripcion') }}</strong>
                                                </span>
                                            @endif 
                                        </div>   
                                        
                                    </div>



                                    <div class="form-group row" >
                                        <div class="col-md-6">
                                            <label class="required">Familia: </label> 
                                        
                                            <select class="select2_form form-control {{ $errors->has('familia') ? ' is-invalid' : '' }}" style="text-transform: uppercase; width:100%" value="{{old('familia')}}" name="familia" id="familia" required>
                                                <option></option>
                                                @foreach ($familias as $familia)   
                                                    <option value="{{$familia->id}}" @if(old('familia') == $familia->id ) {{'selected'}} @endif style="text-transform: uppercase;">{{$familia->familia}}</option>
                                                @endforeach
                                            </select>

                                            @if ($errors->has('familia'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('familia') }}</strong>
                                                </span>
                                            @endif
                                        </div>

                                     
                                        
                                    </div>


                                            
                                
                            </div>

                            



                        </div>


                      
                    
                    
                    <div class="hr-line-dashed"></div>
                    <div class="form-group row">
                        
                        <div class="col-md-6 text-left" style="color:#fcbc6c">
                            <i class="fa fa-exclamation-circle"></i> <small>Los campos marcados con asterisco (<label class="required"></label>) son obligatorios.</small>
                        </div>

                         <div class="col-md-6 text-right">
                            <a  href="{{route('produccion.subfamilias.index')}}"  id="btn_cancelar" class="btn btn-w-m btn-default">
                                <i class="fa fa-arrow-left"></i> Regresar
                            </a>
                            <button type="submit" id="btn_grabar" class="btn btn-w-m btn-primary">
                                <i class="fa fa-save"></i> Grabar
                            </button>
                        </div>

                    </div>

                    </form>

                </div>


            </div>
        </div>

    </div>

</div>

@stop

@push('styles')


<link href="{{asset('Inspinia/css/plugins/select2/select2.min.css')}}" rel="stylesheet">

<style>
    .logo {
        width: 200px;
        height: 200px;
        border-radius: 10%;
    }
    </style>
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

    
     $('#enviar_subfamilia').submit(function(e){
        e.preventDefault();
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger',
            },
            buttonsStyling: false
        })

        Swal.fire({
            title: 'Opción Guardar',
            text: "¿Seguro que desea guardar cambios?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: "#1ab394",
            confirmButtonText: 'Si, Confirmar',
            cancelButtonText: "No, Cancelar",
            }).then((result) => {
            if (result.isConfirmed) {
                    this.submit();
                }else if (
                /* Read more about handling dismissals below */
                result.dismiss === Swal.DismissReason.cancel
            ) {
                swalWithBootstrapButtons.fire(
                'Cancelado',
                'La Solicitud se ha cancelado.',
                'error'
                )
            }
            })
    })








</script>
@endpush