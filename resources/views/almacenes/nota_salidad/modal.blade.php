<div class="modal inmodal" id="modal_editar_detalle" tabindex="-1" role="dialog" aria-hidden="true" data-abierto="0">
    <div class="modal-dialog">
        <div class="modal-content animated bounceInRight">
            <div class="modal-header">
                <button type="button"  class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span>
                </button>
                <i class="fa fa-cogs modal-icon"></i>
                <h4 class="modal-title">Detalle de la Nota de Salidad</h4>
                <small class="font-bold">Editar detalle</small>
            </div>
            <div class="modal-body">
                <input type="hidden" id="indice" name="indice">  
                <div class="form-group row">
                    <div class="col-lg-12 col-xs-12">
                        <label class="col-form-label required">Producto-lote:</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="producto_lote" name="producto_lote" readonly> 
                            <span class="input-group-append"> 
                                <button type="button" class="btn btn-primary" id="buscarLotes" data-toggle="modal" data-target="#modal_lote"><i class='fa fa-search'></i> Buscar
                                </button>
                            </span>
                        </div>
                        <div class="invalid-feedback"><b><span id="error-producto"></span></b>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-lg-6 col-xs-12">
                        <label class="required">Cantidad</label>
                        <input type="text" id="cantidad" name="cantidad" class="form-control"  required>
                    </div>
                </div>
                <input type="hidden" name="lote" id="lote">
                <input type="hidden" name="producto" id="producto">
              
            </div>
            <div class="modal-footer">
                <div class="col-md-6 text-left">
                    <i class="fa fa-exclamation-circle leyenda-required"></i> <small class="leyenda-required">Los campos marcados con asterisco (<label class="required"></label>) son obligatorios.</small>
                </div>
                <div class="col-md-6 text-right">
                    <button type="button" id="btn_editar" class="btn btn-primary btn-sm"><i class="fa fa-save"></i> Guardar</button>
                    <button type="button"  class="btn btn-danger btn-sm" data-dismiss="modal"><i class="fa fa-times"></i> Cancelar</button>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
//Validacion al ingresar tablas
$("#btn_editar").click(function() {
    // limpiarErrores()
    var enviar = false;


    if ($('#modal_editar_detalle #cantidad').val() == '' ) {
        toastr.error('Ingrese los valores.', 'Error');
        enviar = true;
    }
    if (enviar != true) {
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger',
                container: 'my-swal',
            },
            buttonsStyling: false
        })
        Swal.fire({
            customClass: {
                container: 'my-swal'
            },
            title: 'Opción Modificar',
            text: "¿Seguro que desea Modificar Dispositivo?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: "#1ab394",
            confirmButtonText: 'Si, Confirmar',
            cancelButtonText: "No, Cancelar",
        }).then((result) => {
            if (result.isConfirmed) {
                actualizarTabla($('#modal_editar_detalle #indice').val());
            } else if (
                //Read more about handling dismissals below
                result.dismiss === Swal.DismissReason.cancel
            ) {
                swalWithBootstrapButtons.fire(
                    'Cancelado',
                    'La Solicitud se ha cancelado.',
                    'error'
                )
            }
        })
    }
})
function actualizarTabla(i) {
    var table = $('.dataTables-ingreso').DataTable();
    table.row(i).remove().draw();
    var detalle = {
        
        cantidad:  $('#modal_editar_detalle #cantidad').val(),
        lote_id: $('#modal_editar_detalle #lote').val(),
        producto_id:$( "#modal_editar_detalle #producto" ).val(),
        producto_lote:$('#modal_editar_detalle #producto_lote').val()
                }
    agregarTabla(detalle);
    $('#modal_editar_detalle').modal('hide');
}
</script>
@endpush
