@extends('layout') @section('content')
@section('almacenes-active', 'active')
@section('productos-active', 'active')

<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-8 col-xs-12">
       <h2  style="text-transform:uppercase"><b>DETALLE DEL PRODUCTO TERMINADO: #{{ $producto->id }}</b></h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('home') }}">Panel de Control</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('almacenes.producto.index') }}">Productos Terminados</a>
            </li>
            <li class="breadcrumb-item active">
                <strong>Detalle</strong>
            </li>
        </ol>
    </div>
    <div class="col-lg-4 col-xs-12">
        <div class="title-action">
            <a href="{{route('almacenes.producto.edit', $producto->id)}}" class="btn btn-warning btn-sm"><i
                class="fa fa-edit"></i> Editar
            </a>
        </div>
    </div>
</div>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-content">
                    <div class="row">
                        <div class="col-lg-6 col-xs-12 b-r">
                            <h4><b><i class="fa fa-caret-right"></i> Datos Generales</b></h4>
                            <div class="row">
                                <div class="col-lg-6 col-xs-12">
                                    <label><strong>CÓDIGO ISO</strong></label>
                                    <p>{{ $producto->codigo }}</p>
                                </div>


                                <div class="col-lg-6 col-xs-12">
                                    <label><strong>CÓDIGO DE BARRA</strong></label>
                                    @if($producto->codigo_barra)
                                        <p>{{$producto->codigo_barra}}</p>
                                    @else
                                        <p>-</p>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-lg-6 col-xs-12">
                                    <label><strong>UNIDA DE MEDIDA: </strong></label>
                                    @foreach(unidad_medida() as $medida)
                                        @if ($medida->id == $producto->medida)
                                            <p>{{ $medida->simbolo.' - '.$medida->descripcion }}</p>
                                        @endif
                                    @endforeach
                                </div>

                                <div class="col-lg-6 col-xs-12">
                                    <label><strong>PESO (KG): </strong></label>
                                    <p>{{$producto->peso_producto}}</p>

                                </div>
                               
                                
                                
                            </div>

                            <div class="form-group">
                               
                               <label> <strong>DESCRIPCION DEL PRODUCTO</strong> </label>
                               <p>{{ $producto->nombre }}</p>
                               
                           </div>

                           <div class="form-group">
                               
                               <label> <strong>LINEA COMERCIAL</strong> </label>
                               @foreach(lineas_comerciales() as $linea)
                                    @if ($linea->id == $producto->linea_comercial)
                                        <p>{{ $linea->descripcion }}</p>
                                    @endif
                                @endforeach
                               
                           </div>


                            <div class="form-group row">
                                <div class="col-lg-6 col-xs-12">
                                    <label> <strong>FAMILIA</strong></label>
                                    <p>{{ $producto->familia->familia }}</p>
                                </div>
                                <div class="col-lg-6 col-xs-12">
                                    <label> <strong>SUB FAMILIA</strong></label>
                                    <p>{{ $producto->sub_familia->descripcion }}</p>
                                </div>
                            </div>

                            <hr>
                            <h4><b><i class="fa fa-caret-right"></i> Montos segun Clientes</b></h4>

                            <div class="row">
                                

                                <div class="table-responsive">
                                    <table
                                        class="table dataTables-clientes table-striped table-bordered table-hover"
                                        style="text-transform:uppercase">
                                        <thead>
                                            <tr>
                                                
                                                <th class="text-center">CLIENTE</th>
                                                <th class="text-center">MONEDA</th>
                                                <th class="text-center">MONTO</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($clientes as $cliente)
                                            <tr>
                                                <td class="text-left">{{$cliente->cliente}}</td>
                                                <td class="text-center">
                                                    @foreach(tipos_moneda() as $tipo)
                                                        @if ($tipo->id == $cliente->moneda)
                                                            {{$tipo->descripcion}}              
                                                        @endif
                                                    @endforeach
                                                
                                                </td>
                                                <td class="text-center">{{$cliente->monto}}</td>
                                            </tr>
                                        @endforeach

                                        </tbody>

                                    </table>
                                </div>
                    
                    
                    
                            </div>


                        </div>
                        <div class="col-lg-6 col-xs-12 b-r">
                            <h4><b><i class="fa fa-caret-right"></i> Cantidades y Precios</b></h4>
                            <div class="form-group row">
                                <div class="col-lg-6 col-xs-12">
                                    <label><strong>STOCK</strong></label>
                                    <p>{{ $producto->stock }}</p>
                                </div>
                                <div class="col-lg-6 col-xs-12">
                                    <label><strong>STOCK MÍNIMO</strong></label>
                                    <p>{{ $producto->stock_minimo }}</p>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-lg-6 col-xs-12">
                                    <label><strong>P. V. MÍNIMO</strong></label>
                                    <p>S/ {{ $producto->precio_venta_minimo }}</p>
                                </div>
                                <div class="col-lg-6 col-xs-12">
                                    <label><strong>P. V. MÁXIMO</strong></label>
                                    <p>S/ {{ $producto->precio_venta_maximo }}</p>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-lg-6 col-xs-12">
                                    <label><strong>IGV (18%)</strong></label>
                                    <p>{{ ($producto->igv == 1) ? 'SI' : 'NO' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

     



                </div>
            </div>
        </div>
    </div>
</div>

@stop

@push('styles')
    <link href="{{ asset('Inspinia/css/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css') }}" rel="stylesheet">
    <link href="{{asset('Inspinia/css/plugins/dataTables/datatables.min.css')}}" rel="stylesheet">
@endpush

@push('scripts')
    <!-- iCheck -->
    <script src="{{ asset('Inspinia/js/plugins/iCheck/icheck.min.js') }}"></script>
    <script src="{{asset('Inspinia/js/plugins/dataTables/datatables.min.js')}}"></script>
    <script src="{{asset('Inspinia/js/plugins/dataTables/dataTables.bootstrap4.min.js')}}"></script>
    <script>
        $(document).ready(function() {

        // DataTables
        $('.dataTables-clientes').DataTable({
            "dom": 'lTfgitp',

            "bPaginate": true,
            "bLengthChange": true,
            "bFilter": true,
            "bInfo": true,
            "bAutoWidth": false,
            "language": {
                "url": "{{asset('Spanish.json')}}"
            },


        });


        })

    </script>
@endpush
