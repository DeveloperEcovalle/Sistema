@extends('layout') @section('content')
@section('compras-active', 'active')
@section('orden-compra-active', 'active')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-8">
       <h2  style="text-transform:uppercase"><b>Detalle de la Orden de Compra: # {{$orden->id}}</b></h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{route('home')}}">Panel de Control</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{route('compras.orden.index')}}">Ordenes de Compra</a>
            </li>
            <li class="breadcrumb-item active">
                <strong>Detalle</strong>
            </li>
        </ol>
    </div>
    <div class="col-lg-4">
        <div class="title-action">
            <a href="{{route('compras.orden.edit', $orden->id)}}" class="btn btn-warning btn-sm"><i
                    class="fa fa-edit"></i> Editar</a>
            <a href="#" onclick="generarReporte()" class="btn btn-danger btn-sm"><i
                    class="fa fa-file-pdf-o "></i> Reporte </a>
            <a href="#" onclick="enviarReporte()" class="btn btn-primary btn-sm"><i
                    class="fa fa-send "></i> Enviar </a>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-lg-12">
        <div class="wrapper wrapper-content animated fadeInRight">
            <div class="ibox-content p-xl">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="row">
                            <div class="col-md-12 text-center">
                                @if($orden->empresa->ruta_logo)
                                <img src="{{Storage::url($orden->empresa->ruta_logo)}}" class="img-fluid m-b"
                                    width="190px" height="190px">
                                @else
                                <img src="{{asset('storage/empresas/logos/default.png')}}" class="img-fluid m-b"
                                    width="190px" height="190px">
                                @endif
                            </div>

                        </div>

                    </div>

                    <div class="col-sm-6 text-right">
                        <div class="row">
                            <div class="col-md-12">
                                <strong>{{$orden->empresa->razon_social}}</strong>
                                <br>
                                RUC:{{$orden->empresa->ruc}}
                                <br>
                                {{$orden->empresa->direccion_fiscal}}
                            </div>

                        </div>


                        <div class="row">
                            <div class="col-md-6 text-center">

                            </div>
                            <div class="col-md-6 text-right">
                                <h4>ORDEN DE COMPRA</h4>
                                <h4 class="text-navy">OC-00{{$orden->id}}</h4>
                                <hr>
                                <div class="table-responsive">
                                    <table class="table table-bordered" style="text-transform: uppercase;">
                                        <thead>
                                            <tr>
                                                <th colspan="2" class="text-center">FECHAS</th>

                                            </tr>
                                            <tr>
                                                <th class="text-center">DOC.</th>
                                                <th class="text-center">ENTREGA</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                
                                                <th class="text-center">
                                                    <span
                                                        style="font-weight: normal">{{ Carbon\Carbon::parse($orden->fecha_emision)->format('d/m/y') }}</span>
                                                </th>
                                                <th class="text-center">
                                                    <span
                                                        style="font-weight: normal">{{ Carbon\Carbon::parse($orden->fecha_entrega)->format('d/m/y') }}</span>
                                                </th>

                                            </tr>

                                        </tbody>

                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        
                        <div class="row">
                            <div class="col-md-6 text-center">

                            </div>
                            <div class="col-md-6 text-center">

                            </div>
                        </div>

                    </div>
                    <hr>

                </div>

                




                <div class="row">
                    <div class="col-md-6 b-r text-center"  onkeyup="return mayus(this)">
                        <p><b>DATOS DE LA EMPRESA</b></p>
                        <dl class="row mb-0">
                            <div class="col-sm-4 text-sm-right">
                                <dt>ESTADO:</dt>
                            </div>
                            <div class="col-sm-8 text-sm-left">
                                <dd class="mb-1"><span class="label label-primary">{{$orden->empresa->estado}}</span>
                                </dd>
                            </div>
                        </dl>
                        <dl class="row mb-0">
                            <div class="col-sm-4 text-sm-right">
                                <dt>RUC:</dt>
                            </div>
                            <div class="col-sm-8 text-sm-left">
                                <dd class="mb-1 text-navy">{{$orden->empresa->ruc}}</dd>
                            </div>
                        </dl>
                        <dl class="row mb-0">
                            <div class="col-sm-4 text-sm-right">
                                <dt>RAZON SOCIAL:</dt>
                            </div>
                            <div class="col-sm-8 text-sm-left">
                                <dd class="mb-1"> {{$orden->empresa->razon_social}}</dd>
                            </div>
                        </dl>
                        <dl class="row mb-0">
                            <div class="col-sm-4 text-sm-right">
                                <dt>COMERCIAL:</dt>
                            </div>
                            <div class="col-sm-8 text-sm-left">
                                <dd class="mb-1"> {{$orden->empresa->razon_social_abreviada}}</dd>
                            </div>
                        </dl>


                        <dl class="row mb-0">
                            <div class="col-sm-4 text-sm-right">
                                <dt>DIRECCION:</dt>
                            </div>
                            <div class="col-sm-8 text-sm-left">
                                <dd class="mb-1"> {{$orden->empresa->direccion_fiscal}} </dd>
                            </div>
                        </dl>
                        <dl class="row mb-0">
                            <div class="col-sm-4 text-sm-right">
                                <dt>CORREO:</dt>
                            </div>
                            <div class="col-sm-8 text-sm-left">
                                <dd class="mb-1">
                                    @if($orden->empresa->correo)
                                    {{$orden->empresa->correo}}
                                    @else
                                    -
                                    @endif

                                </dd>
                            </div>
                        </dl>
                        <dl class="row mb-0">
                            <div class="col-sm-4 text-sm-right">
                                <dt>TELEFONO:</dt>
                            </div>
                            <div class="col-sm-8 text-sm-left">
                                <dd class="mb-1">
                                    @if($orden->empresa->telefono)
                                    {{$orden->empresa->telefono}}
                                    @else
                                    -
                                    @endif

                                </dd>
                            </div>
                        </dl>
                        <dl class="row mb-0">
                            <div class="col-sm-4 text-sm-right">
                                <dt>CELULAR:</dt>
                            </div>
                            <div class="col-sm-8 text-sm-left">
                                <dd class="mb-1">
                                    @if($orden->empresa->celular)
                                    {{$orden->empresa->celular}}
                                    @else
                                    -
                                    @endif
                                </dd>
                            </div>
                        </dl>
                    </div>
                    <div class="col-md-6 text-center"  onkeyup="return mayus(this)">
                        <p><b>DATOS DEL PROVEEDOR</b></p>
                        <dl class="row mb-0">
                            <div class="col-sm-4 text-sm-right">
                                <dt>ESTADO:</dt>
                            </div>
                            <div class="col-sm-8 text-sm-left">
                                <dd class="mb-1"><span class="label label-primary">{{$orden->proveedor->estado}}</span>
                                </dd>
                            </div>
                        </dl>
                        <dl class="row mb-0">
                            @if($orden->proveedor->ruc)

                            <div class="col-sm-4 text-sm-right">
                                <dt>RUC:</dt>
                            </div>
                            <div class="col-sm-8 text-sm-left">
                                <dd class="mb-1 text-navy">{{$orden->empresa->ruc}}</dd>
                            </div>

                            @else
                            <div class="col-sm-4 text-sm-right">
                                <dt>DNI:</dt>
                            </div>
                            <div class="col-sm-8 text-sm-left">
                                <dd class="mb-1 text-navy">{{$orden->empresa->dni}}</dd>
                            </div>

                            @endif


                        </dl>
                        <dl class="row mb-0">
                            <div class="col-sm-4 text-sm-right">
                                <dt>DESCRIPCION:</dt>
                            </div>
                            <div class="col-sm-8 text-sm-left">
                                <dd class="mb-1"> {{$orden->proveedor->descripcion}}</dd>
                            </div>
                        </dl>


                        <dl class="row mb-0">
                            <div class="col-sm-4 text-sm-right">
                                <dt>DIRECCION:</dt>
                            </div>
                            <div class="col-sm-8 text-sm-left">
                                <dd class="mb-1"> {{$orden->proveedor->direccion}} </dd>
                            </div>
                        </dl>
                        <dl class="row mb-0">
                            <div class="col-sm-4 text-sm-right">
                                <dt>ZONA:</dt>
                            </div>
                            <div class="col-sm-8 text-sm-left">
                                <dd class="mb-1"> {{$orden->proveedor->zona}}</dd>
                            </div>
                        </dl>

                        <dl class="row mb-0">
                            <div class="col-sm-4 text-sm-right">
                                <dt>CORREO:</dt>
                            </div>
                            <div class="col-sm-8 text-sm-left">
                                <dd class="mb-1">
                                    {{$orden->proveedor->correo}}
                                </dd>
                            </div>
                        </dl>
                        <dl class="row mb-0">
                            <div class="col-sm-4 text-sm-right">
                                <dt>TELEFONO:</dt>
                            </div>
                            <div class="col-sm-8 text-sm-left">
                                <dd class="mb-1">
                                    @if($orden->proveedor->telefono)
                                    {{$orden->proveedor->telefono}}
                                    @else
                                    -
                                    @endif

                                </dd>
                            </div>
                        </dl>
                        <dl class="row mb-0">
                            <div class="col-sm-4 text-sm-right">
                                <dt>CELULAR:</dt>
                            </div>
                            <div class="col-sm-8 text-sm-left">
                                <dd class="mb-1">
                                    @if($orden->proveedor->celular)
                                    {{$orden->proveedor->celular}}
                                    @else
                                    -
                                    @endif
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <hr>

                <div class="table-responsive">
                    <table class="table invoice-table table table-bordered"  onkeyup="return mayus(this)">
                        <thead>
                            <tr>
                                <th class=>ARTÍCULO</th>
                                <th class="text-center">PRESENTACIÓN</th>
                                <th class="text-center">CANTIDAD</th>
                                <th class="text-center">PRECIO UNIT.</th>
                                <th class="text-center">IMPORTE</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($detalles as $detalle)
                            <tr>
                                <td>
                                    <div><strong>{{$detalle->articulo->descripcion}}</strong></div>
                                </td>
                                <td class="text-center">
                                    @foreach($presentaciones as $presentacion)
                                    @if($presentacion->descripcion == $detalle->articulo->presentacion)
                                    {{$presentacion->simbolo}}
                                    @endif
                                    @endforeach
                                </td>
                                <td class="text-center">{{$detalle->cantidad}}</td>
                                <td class="text-center">{{$detalle->precio}}</td>
                                <td class="text-center subtotal">{{$detalle->cantidad * $detalle->precio}}</td>

                            </tr>
                            @endforeach


                        </tbody>
                        <tfoot  onkeyup="return mayus(this)">
                            <tr>
                                <th colspan="4" style="text-align:right"><strong>Sub Total :</strong></th>
                                <th class="text-center"><span id="">{{$moneda.'  '.$subtotal}}</span></th>

                            </tr>
                            <tr>
                                <th colspan="4" style="text-align:right"><strong>IGV
                                        @if($orden->igv){{$orden->igv}}%@endif :</strong></th>
                                <th class="text-center"><span id="">{{$moneda.'  '.$igv}}</span></th>

                            </tr>
                            <tr>
                                <th colspan="4" style="text-align:right"><strong>Total :</strong></th>
                                <th class="text-center"><span id="total">{{$moneda.'  '.$total}}</span></th>

                            </tr>
                        </tfoot>
                    </table>
                </div>

                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <div class="table-responsive">
                            <table class="table table-bordered" style="text-transform: uppercase;">
                                <thead>
                                    <tr>
                                        <th colspan="2" class="text-center">OBSERVACION</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th class="">
                                            @if($orden->observacion)
                                            <span style="font-weight: normal">{{$orden->observacion}}</span>
                                            @else
                                            <span style="font-weight: normal">NO ESPECIFICADO</span>
                                            @endif

                                        </th>

                                    </tr>

                                </tbody>

                            </table>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="table-responsive">
                            <table class="table table-bordered" style="text-transform: uppercase;">
                                <thead>
                                    <tr>
                                        <th colspan="2" class="text-center">DATOS ADICIONALES</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th class="text-left">
                                            CONDICION DE ORDEN DE COMPRA
                                        </th>
                                        <th class="text-left">
                                            <span style="font-weight: normal">{{$orden->modo_compra}}</span>
                                        </th>

                                    </tr>
                                    <tr>
                                        <th class="text-left">
                                            TIPO DE MONEDA
                                        </th>
                                        <th class="text-left">
                                            <span style="font-weight: normal">{{$orden->moneda}}</span>
                                        </th>

                                    </tr>
                                    <tr>
                                        <th class="text-left">
                                            TIPO DE CAMBIO
                                        </th>
                                        <th class="text-left">
                                        <span style="font-weight: normal">{{$orden->tipo_cambio}}</span>
                                        </th>

                                    </tr>
                                    <tr>
                                        <th class="text-left">
                                            DIRECCION DE SERVICIO
                                        </th>
                                        <th class="text-left">
                                            <span style="font-weight: normal"></span>
                                        </th>

                                    </tr>
                                    <tr>
                                        <th class="text-left">
                                            EMPRESA DE TRANSPORTE
                                        </th>
                                        <th class="text-left">
                                            <span style="font-weight: normal">{{$orden->proveedor->transporte}}</span>
                                        </th>

                                    </tr>

                                    <tr>
                                        <th class="text-left">
                                            CONTACTO DE ENTREGA
                                        </th>
                                        <th class="text-left">
                                            <span style="font-weight: normal"></span>
                                        </th>

                                    </tr>
                                    

                                </tbody>

                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@stop
@push('styles')
@endpush
@push('scripts')
<script>
    function generarReporte() {
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger',
            },
            buttonsStyling: false
        })

        Swal.fire({
            title: "Opción Reporte",
            text: "¿Seguro que desea generar reporte?",
            showCancelButton: true,
            icon: 'info',
            confirmButtonColor: "#1ab394",
            confirmButtonText: 'Si, Confirmar',
            cancelButtonText: "No, Cancelar",
            // showLoaderOnConfirm: true,
        }).then((result) => {
            if (result.value) {
                window.location.href = "{{route('compras.orden.reporte', $orden->id)}}"
                Swal.fire({
                    title: '¡Cargando!',
                    type: 'info',
                    text: 'Generando Reporte',
                    showConfirmButton:false,
                    onBeforeOpen: () => {
                        Swal.showLoading()
                    }
                })
                
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

    }

</script>
<script>
    function enviarReporte() {
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger',
            },
            buttonsStyling: false
        })

        Swal.fire({
            title: "Opción Reporte",
            text: "¿Seguro que desea enviar reporte por correo?",
            showCancelButton: true,
            icon: 'info',
            confirmButtonColor: "#1ab394",
            confirmButtonText: 'Si, Confirmar',
            cancelButtonText: "No, Cancelar",
            // showLoaderOnConfirm: true,
        }).then((result) => {
            if (result.value) {
                window.location.href = "{{route('compras.orden.email', $orden->id)}}"
                Swal.fire({
                    title: '¡Enviando!',
                    type: 'info',
                    text: 'El Reporte se esta enviando al correo: '+"{{$orden->proveedor->correo}}",
                    showConfirmButton:false,
                    onBeforeOpen: () => {
                        Swal.showLoading()
                    }
                })
                
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

    }

</script>


@endpush