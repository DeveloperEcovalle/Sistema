<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Ecovalle | Sistema de Producción</title>

    <link href="{{asset('Inspinia/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('Inspinia/font-awesome/css/font-awesome.css')}}" rel="stylesheet">

    <link href="{{asset('Inspinia/css/animate.css')}}" rel="stylesheet">
    <link href="{{asset('Inspinia/css/style.css')}}" rel="stylesheet">



    <!-- Toastr style -->
    <link href="{{asset('Inspinia/css/plugins/toastr/toastr.min.css')}}" rel="stylesheet">





    @stack('styles')

</head>

<body>
    <div id="wrapper">
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
                <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>
            </div>
                <ul class="nav navbar-top-links navbar-right">
                    <li>
                        <span class="m-r-sm text-muted welcome-message">Bienvenid@ <b> {{auth()->user()->usuario}}</b></span>
                    </li>


                    <li>
                        <a href="{{route('logout')}}">
                            <i class="fa fa-sign-out"></i> Cerrar Sesión
                        </a>
                    </li>

                </ul>

            </nav>
        </div>


        <div>
        <!-- Contenido del Sistema -->
            @yield('content')
        <!-- /.Contenido del Sistema -->
        </div>

        <div class="footer">
            <div class="float-right">
                10GB of <strong>250GB</strong> Free.
            </div>
            <div>
                <strong>Copyright</strong> Example Company &copy; 2014-2018
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

        //Mensaje de Session
        @if (session('guardar') == 'success' )
            Swal.fire({
                    icon: 'success',
                    title: 'Guardado',
                    text: '¡Acción realizada satisfactoriamente!',
                    showConfirmButton: false,
                    timer: 1500
            })
        @endif

        @if (session('eliminar') == 'success' )
            Swal.fire({
                    icon: 'success',
                    title: 'Eliminado',
                    text: '¡Acción realizada satisfactoriamente!',
                    showConfirmButton: false,
                    timer: 1500
            })
        @endif

        @if (session('modificar') == 'success' )
            Swal.fire({
                    icon: 'success',
                    title: 'Modificado',
                    text: '¡Acción realizada satisfactoriamente!',
                    showConfirmButton: false,
                    timer: 1500
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

    </script>

</body>
</html>
