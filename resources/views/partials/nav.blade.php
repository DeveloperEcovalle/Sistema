    <li class="nav-header">
        <div class="dropdown profile-element">

            @if(auth()->user()->ruta_imagen)
                <img alt="image"  alt="{{auth()->user()->usuario}}" class="rounded-circle" height="48" width="48"  src="{{Storage::url(auth()->user()->ruta_imagen)}}"/>
            @else
                <img alt="{{auth()->user()->usuario}}" alt="{{auth()->user()->usuario}}" class="rounded-circle" height="48" width="48"  src="{{ asset('storage/usuarios/default.jpg') }}"/>

            @endif


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



    <li>
        <a href="{{route('home')}}"><i class="fa fa-th-large"></i> <span class="nav-label">Panel de control</span></a>
    </li>



    <li>
        <a href="layouts.html"><i class="fa fa-pie-chart"></i> <span class="nav-label">Estadisticas</span></a>
    </li>


    <li class="@yield('pos-active')">
        <a href="#"><i class="fa fa-archive"></i> <span class="nav-label">Pos</span><span class="fa arrow"></span></a>
        <ul class="nav nav-second-level collapse">
            <li class="@yield('caja_chica-active')"><a href="{{route('pos.caja.index')}}">Caja Chica Pos</a></li>
        </ul>
    </li>


    <li class="@yield('compras-active')">
        <a href="#"><i class="fa fa-shopping-cart"></i> <span class="nav-label">Compras</span><span class="fa arrow"></span></a>
        <ul class="nav nav-second-level collapse">
            <li class="@yield('articulo-active')"><a href="{{route('compras.articulo.index')}}">Artículos</a></li>
            <li class="@yield('categoria-active')"><a href="{{route('compras.categoria.index')}}">Categorias</a></li>
            <li class="@yield('proveedor-active')"><a href="{{route('compras.proveedor.index')}}">Proveedores</a></li>
            <li class="@yield('orden-compra-active')"><a href="{{route('compras.orden.index')}}">Ordenes</a></li>
            <li class="@yield('documento-active')"><a href="{{route('compras.documento.index')}}">Documentos</a></li>
        </ul>
    </li>

    <li class="@yield('ventas-active')">
        <a href="#"><i class="fa fa-signal"></i> <span class="nav-label">Ventas</span><span class="fa arrow"></span></a>
        <ul class="nav nav-second-level collapse">
            <li class="@yield('clientes-active')"><a href="{{ route('ventas.cliente.index') }}">Clientes</a></li>
            <li class="@yield('cotizaciones-active')"><a href="{{ route('ventas.cotizacion.index') }}">Cotizaciones</a></li>
            <li class="@yield('documentos-active')"><a href="{{route('ventas.documento.index')}}">Documentos</a></li>

        </ul>
    </li>


    <li class="@yield('produccion-active')">
        <a href="#"><i class="fa fa-bar-chart-o"></i> <span class="nav-label">Producción</span><span class="fa arrow"></span></a>
        <ul class="nav nav-second-level collapse">
            <li class="@yield('composicion-active')"><a href="{{ route('produccion.composicion.index') }}">Composicion de Productos Terminados</a></li>
            <li><a href="login_two_columns.html">Linea de Producción</a></li>
            <li><a href="forgot_password.html">Detalle de Linea</a></li>
            <li><a href="register.html">Ordenes</a></li>
            <li><a href="404.html">Programación O/P</a></li>
            <li><a href="500.html">Consultas</a></li>
            <li class="@yield('maquinaria_equipo-active')"><a href="{{route('produccion.maquinaria_equipo.index')}}">Maquinarias-Equipos</a></li>
            <li class="@yield('linea_produccion-active')"><a href="{{route('produccion.linea_produccion.index')}}">Lineas de Produccion</a></li>
        </ul>
    </li>

    <li class="@yield('invdesarrollo-active')">
        <a href="#"><i class="fa fa-bar-chart-o"></i> <span class="nav-label">Inv + Desarrollo </span><span class="fa arrow"></span></a>
        <ul class="nav nav-second-level collapse">
            <li class="@yield('registro_sanitario-active')"><a href="{{route('invdesarrollo.registro_sanitario.index')}}">Registro Sanitario</a></li>
           <li class="@yield('prototipo-active')"><a href="{{route('invdesarrollo.prototipo.index')}}">Prototipos</a></li>
           <li class="@yield('guia-active')"><a href="{{route('invdesarrollo.guia.index')}}">Guias Internas</a></li>
        </ul>
    </li>
    
    <li class="@yield('almacenes-active')">
        <a href="#"><i class="fa fa-suitcase"></i> <span class="nav-label">Almacenes </span><span class="fa arrow"></span></a>
        <ul class="nav nav-second-level collapse">
            <li class="@yield('almacen-active')"><a href="{{route('almacenes.almacen.index')}}">Almacen</a></li>
            <li class="@yield('familia-active')"><a href="{{route('almacenes.familias.index')}}">Familia</a></li>
            <li class="@yield('subfamilia-active')"><a href="{{route('almacenes.subfamilia.index')}}">Sub Familia</a></li>
            <li class="@yield('productos-active')"><a href="{{ route('almacenes.producto.index') }}">Producto Terminado</a></li>
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

    <li class="@yield('mantenimiento-active')">
        <a href="#"><i class="fa fa-cogs"></i> <span class="nav-label">Mantenimiento</span><span class="fa arrow"></span></a>
        <ul class="nav nav-second-level collapse">
            <li class="@yield('empleados-active')"><a href="{{ route('mantenimiento.empleado.index') }}">Empleados</a></li>
            <li class="@yield('vendedores-active')"><a href="{{ route('mantenimiento.vendedor.index') }}">Vendedores</a></li>
            <li class="@yield('empresas-active')"><a href="{{route('mantenimiento.empresas.index')}}">Empresas</a></li>
            <li class="@yield('talonarios-active')"><a href="{{ route('mantenimiento.talonario.index') }}">Talonarios</a></li>
            <li><a href="lockscreen.html">Metodos de Pago</a></li>
            <li><a href="lockscreen.html">Tipos de Pago</a></li>
            <li><a href="lockscreen.html">Origen de Documentos</a></li>
            <li><a href="lockscreen.html">Tipos de Documentos</a></li>
            <li class="@yield('tablas-active')"><a href="{{route('mantenimiento.tabla.general.index')}}">Tablas Generales</a></li>

        </ul>
    </li>

    <li class="@yield('seguridad-active')">
        <a href="#"><i class="fa fa-shield"></i> <span class="nav-label">Seguridad</span><span class="fa arrow"></span></a>
        <ul class="nav nav-second-level collapse">
            <li class="@yield('usuarios-active')"><a href="{{route('seguridad.usuario.index')}}">Usuarios</a></li>
            <li><a href="lockscreen.html">Roles</a></li>
        </ul>
    </li>

