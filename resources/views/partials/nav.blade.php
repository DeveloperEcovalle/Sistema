    <li class="nav-header">
        <div class="dropdown profile-element">
            <img alt="image" class="rounded-circle" height="48" width="48"  src="{{asset('img/user.jpg')}}"/>
            <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                <span class="block m-t-xs font-bold">{{auth()->user()->usuario}}</span>
                <span class="text-muted text-xs block">Administrador <b class="caret"></b></span>
            </a>
            <ul class="dropdown-menu animated fadeInRight m-t-xs">
                <li><a class="dropdown-item" href="login.html">Cerrar Sesión</a></li>
            </ul>
        </div>
        <div class="logo-element">
            <img src="{{asset('img/ecologo.jpeg')}}" height="30" width="45">
        </div>
    </li>


    
    <li class="active">
        <a href="{{route('home')}}"><i class="fa fa-th-large"></i> <span class="nav-label">Panel de control</span></a>
    </li>



    <li>
        <a href="layouts.html"><i class="fa fa-pie-chart"></i> <span class="nav-label">Estadisticas</span></a>
    </li>

    
    <li>
        <a href="#"><i class="fa fa-shopping-cart"></i> <span class="nav-label">Compras</span><span class="fa arrow"></span></a>
        <ul class="nav nav-second-level collapse">
            <li><a href="{{route('compras.articulo.index')}}">Artículos</a></li>
            <li><a href="{{route('compras.categoria.index')}}">Categorias</a></li>
            <li><a href="{{route('compras.proveedor.index')}}">Proveedores</a></li>
            <li><a href="ecommerce-cart.html">Ordenes</a></li>
            <li><a href="ecommerce-orders.html">Documentos</a></li>
        </ul>
    </li>

    <li>
        <a href="#"><i class="fa fa-signal"></i> <span class="nav-label">Ventas</span><span class="fa arrow"></span></a>
        <ul class="nav nav-second-level collapse">
            <li><a href="search_results.html">Clientes</a></li>
            <li><a href="lockscreen.html">Cotizaciones</a></li>
            <li><a href="lockscreen.html">Pedidos</a></li>
            <li><a href="lockscreen.html">Documentos</a></li>
            
        </ul>
    </li>

    
    <li>
        <a href="#"><i class="fa fa-bar-chart-o"></i> <span class="nav-label">Producción</span><span class="fa arrow"></span></a>
        <ul class="nav nav-second-level collapse">
            <li><a href="search_results.html">Familia</a></li>
            <li><a href="lockscreen.html">Sub Familia</a></li>
            <li><a href="invoice.html">Unidad de Medida</a></li>
            <li><a href="login.html">Producto Terminado</a></li>
            <li><a href="login_two_columns.html">Linea de Producción</a></li>
            <li><a href="forgot_password.html">Detalle de Linea</a></li>
            <li><a href="register.html">Ordenes</a></li>
            <li><a href="404.html">Programación O/P</a></li>
            <li><a href="500.html">Consultas</a></li>
        </ul>
    </li>

    <li>
        <a href="#"><i class="fa fa-suitcase"></i> <span class="nav-label">Almacenes </span><span class="fa arrow"></span></a>
        <ul class="nav nav-second-level collapse">
            <li><a href="{{route('almacenes.almacen.index')}}">Almacen</a></li>
            <li><a href="search_results.html">Transferencias</a></li>
            <li>
                <a href="#">Consultas <span class="fa arrow"></span></a>
                <ul class="nav nav-third-level">
                    <li>
                        <a href="#">Stock</a>
                    </li>
                    <li>
                        <a href="#">Valorizada</a>
                    </li>

                </ul>
            </li>


        </ul>
    </li>

    <li>
        <a href="#"><i class="fa fa-money"></i> <span class="nav-label">Cuentas </span><span class="fa arrow"></span></a>
        <ul class="nav nav-second-level collapse">
            <li>
                <a href="#">Proveedores <span class="fa arrow"></span></a>
                <ul class="nav nav-third-level">
                    <li>
                        <a href="#">Consultas</a>
                    </li>
                    <li>
                        <a href="#">Reporte</a>
                    </li>
                    <li>
                        <a href="#">Adelanto</a>
                    </li>

                </ul>
            </li>

            <li>
                <a href="#">Clientes <span class="fa arrow"></span></a>
                <ul class="nav nav-third-level">
                    <li>
                        <a href="#">Consultas</a>
                    </li>
                    <li>
                        <a href="#">Reporte</a>
                    </li>
                    <li>
                        <a href="#">Adelanto</a>
                    </li>

                </ul>
            </li>

        </ul>
    </li>

    <li>
        <a href="#"><i class="fa fa-cogs"></i> <span class="nav-label">Mantenimiento</span><span class="fa arrow"></span></a>
        <ul class="nav nav-second-level collapse">
            <li><a href="search_results.html">Empleados</a></li>
            <li><a href="{{route('mantenimiento.empresas.index')}}">Empresas</a></li>
            <li><a href="lockscreen.html">Parametros</a></li>
            <li><a href="lockscreen.html">Metodos de Pago</a></li>
            <li><a href="lockscreen.html">Tipos de Pago</a></li>
            <li><a href="lockscreen.html">Origen de Documentos</a></li>
            <li><a href="lockscreen.html">Tipos de Documentos</a></li>
            <li><a href="{{route('mantenimiento.tabla.general.index')}}">Tablas Generales</a></li>
            
        </ul>
    </li>

    <li>
        <a href="#"><i class="fa fa-shield"></i> <span class="nav-label">Seguridad</span><span class="fa arrow"></span></a>
        <ul class="nav nav-second-level collapse">
            <li><a href="search_results.html">Usuarios</a></li>
            <li><a href="lockscreen.html">Roles</a></li>
        </ul>
    </li>

