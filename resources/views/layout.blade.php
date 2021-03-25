<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Ecovalle | Sistema de Producción</title>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{asset('img/ecologo.ico')}}" />

    <link href="{{asset('Inspinia/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('Inspinia/font-awesome/css/font-awesome.css')}}" rel="stylesheet">

    <link href="{{asset('Inspinia/css/animate.css')}}" rel="stylesheet">
    <link href="{{asset('Inspinia/css/style.css')}}" rel="stylesheet">



    <!-- Toastr style -->
    <link href="{{asset('Inspinia/css/plugins/toastr/toastr.min.css')}}" rel="stylesheet">




    @stack('styles')

    <!-- Styles -->
    <link href="{{asset('css/style.css')}}" rel="stylesheet">

</head>

<body>
    <div id="">
        <nav class="navbar-default navbar-static-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav metismenu" id="side-menu">
                    <!-- Sidebar  Menu -->
                    @include('partials.nav')
                    <!-- /.Sidebar Menu -->
                </ul>

            </div>
        </nav>

        <div id="page-wrapper" class="gray-bg">
            <div class="row border-bottom">
                <nav class="navbar navbar-static-top white-bg" role="navigation" style="margin-bottom: 0">
                    <div class="navbar-header">
                        <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i
                                class="fa fa-bars"></i> </a>
                    </div>
                    <ul class="nav navbar-top-links navbar-right">
                        <li>
                            <span class="m-r-sm text-muted welcome-message">Bienvenid@ <b>
                                    {{auth()->user()->usuario}}</b></span>
                        </li>


                        <li>
                            <a href="{{route('logout')}}">
                                <i class="fa fa-sign-out"></i> Cerrar Sesión
                            </a>
                        </li>

                    </ul>

                </nav>
            </div>

            <div class="loader-spinner">
                <div class="centrado" id="onload">
                    <div class="loadingio-spinner-blocks-zcepr5tohl">
                        <div class="ldio-6fqlsp2qlpd">
                            <div style='left:38px;top:38px;animation-delay:0s'></div>
                            <div style='left:80px;top:38px;animation-delay:0.125s'></div>
                            <div style='left:122px;top:38px;animation-delay:0.25s'></div>
                            <div style='left:38px;top:80px;animation-delay:0.875s'></div>
                            <div style='left:122px;top:80px;animation-delay:0.375s'></div>
                            <div style='left:38px;top:122px;animation-delay:0.75s'></div>
                            <div style='left:80px;top:122px;animation-delay:0.625s'></div>
                            <div style='left:122px;top:122px;animation-delay:0.5s'></div>
                        </div>
                    </div>

                </div>
            </div>


            <div id="content-system" style="">
                <!-- Contenido del Sistema -->
                @yield('content')
                <!-- /.Contenido del Sistema -->
            </div>

            <div class="footer">
                <div class="float-right" onkeyup="return mayus(this)">
                    DEVELOPER <strong>SISCOM SAC</strong>
                </div>
                <div  onkeyup="return mayus(this)">
                    <strong>Copyright</strong> AgroEnsancha Eirl &copy; 2020-2021
                </div>
            </div>

        </div>

    </div>

    <!-- Mainly scripts -->
    <script src="{{asset('Inspinia/js/jquery-3.1.1.min.js')}}"></script>
    <script src="{{asset('Inspinia/js/popper.min.js')}}"></script>
    <script src="{{asset('Inspinia/js/bootstrap.js')}}"></script>
    <script src="{{asset('Inspinia/js/plugins/metisMenu/jquery.metisMenu.js')}}"></script>
    <script src="{{asset('Inspinia/js/plugins/slimscroll/jquery.slimscroll.min.js')}}"></script>

    <!-- Custom and plugin javascript -->
    <script src="{{asset('Inspinia/js/inspinia.js')}}"></script>
    <script src="{{asset('Inspinia/js/plugins/pace/pace.min.js')}}"></script>

    <!-- jQuery UI -->
    <script src="{{asset('Inspinia/js/plugins/jquery-ui/jquery-ui.min.js')}}"></script>

    <!-- Toastr script -->
    <script src="{{asset('Inspinia/js/plugins/toastr/toastr.min.js')}}"></script>

    <!-- Propio scripts -->
    <script src="{{ asset('Inspinia/js/scripts.js') }}"></script>

    <!-- SweetAlert -->
    <script src="{{asset('SweetAlert/sweetalert2@10.js')}}"></script>

    @stack('scripts')

    <script>
    @if(Session::has('success'))
    toastr.success("{{ Session::get('success') }}")
    @endif

    @if(Session::has('error'))
    toastr.error("{{ Session::get('error') }}")
    @endif


    //Mensaje de Session
    @if(session('guardar') == 'success')
    Swal.fire({
        icon: 'success',
        title: 'Guardado',
        text: '¡Acción realizada satisfactoriamente!',
        showConfirmButton: false,
        timer: 1500
    })
    @endif

    @if(session('eliminar') == 'success')
    Swal.fire({
        icon: 'success',
        title: 'Eliminado',
        text: '¡Acción realizada satisfactoriamente!',
        showConfirmButton: false,
        timer: 1500
    })
    @endif

    @if(session('cerrada') == 'success')
    Swal.fire({
        icon: 'success',
        title: 'Caja Chica Cerrada',
        text: '¡Acción realizada satisfactoriamente!',
        showConfirmButton: false,
        timer: 1500
    })
    @endif

    @if(session('modificar') == 'success')
    Swal.fire({
        icon: 'success',
        title: 'Modificado',
        text: '¡Acción realizada satisfactoriamente!',
        showConfirmButton: false,
        timer: 1500
    })
    @endif

    @if(session('concretar') == 'success')
    Swal.fire({
        icon: 'success',
        title: 'Concretada',
        text: '¡Acción realizada satisfactoriamente!',
        showConfirmButton: false,
        timer: 1500
    })
    @endif

    @if(session('enviar') == 'success')
    Swal.fire({
        icon: 'success',
        title: 'Enviado',
        text: '¡Acción realizada satisfactoriamente!',
        showConfirmButton: false,
        timer: 1500
    })
    @endif

    @if(session('error_caja') == 'success')
    Swal.fire({
        icon: 'error',
        title: 'Caja Chica',
        text: '¡Caja Chica esta siendo utilizada en algun pago!',
        showConfirmButton: false,
        timer: 1500
    })
    @endif

    @if(session('exitosa') == 'success')
    Swal.fire({
        icon: 'success',
        title: 'Acción Exitosa',
        text: '¡Puede ingresar nuevo tipo de pago!',
        showConfirmButton: false,
        timer: 1500
    })
    @endif

    @if(session('sunat_existe') == 'error')
    Swal.fire({
        icon: 'error',
        title: 'Documento de Venta',
        text: 'Existe un comprobante electronico',
        showConfirmButton: false,
        timer: 2500
    })
    @endif

    @if(session('error_orden_produccion') == 'error')
    Swal.fire({
        icon: 'error',
        title: 'Orden de Producción',
        text: 'Falta la confirmación del Área de Producción',
        showConfirmButton: false,
        timer: 2500
    })
    @endif

    @if(session('error_orden_almacen') == 'error')
    Swal.fire({
        icon: 'error',
        title: 'Orden de Producción',
        text: 'Falta la confirmación del Área de Almacen',
        showConfirmButton: false,
        timer: 2500
    })
    @endif

    @if(session('error_orden_areas') == 'error')
    Swal.fire({
        icon: 'error',
        title: 'Orden de Producción',
        text: 'Falta la confirmación de las Áreas de Almacen y Producción',
        showConfirmButton: false,
        timer: 2500
    })
    @endif
    
    </script>

    <script>
        function consultaExitosa() {
            Swal.fire(
                '¡Búsqueda Exitosa!',
                'Datos ingresados.',
                'success'
            )
        }
        //Loader
        window.addEventListener("load",function(){
            $('.loader-spinner').hide();
            $("#content-system").css("display", "");
        })
        

    </script>

</body>

</html>