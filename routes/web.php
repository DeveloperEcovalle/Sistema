<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('logout', 'Auth\LoginController@logout')->name('logout');

//Parametro
Route::get('parametro/getApiruc/{ruc}', 'ParametroController@apiRuc')->name('getApiruc');
Route::get('parametro/getApidni/{dni}', 'ParametroController@apiDni')->name('getApidni');


// Mantenimiento
//Tabla General
Route::prefix('mantenimiento/tablas/generales')->group(function() {
    Route::get('index', 'Mantenimiento\Tabla\GeneralController@index')->name('mantenimiento.tabla.general.index');
    Route::get('getTable','Mantenimiento\Tabla\GeneralController@getTable')->name('getTable');
    Route::put('update', 'Mantenimiento\Tabla\GeneralController@update')->name('mantenimiento.tabla.general.update');
});

//Tabla Detalle
Route::prefix('mantenimiento/tablas/detalles')->group(function() {
    Route::get('index/{id}', 'Mantenimiento\Tabla\DetalleController@index')->name('mantenimiento.tabla.detalle.index');
    Route::get('getTable/{id}','Mantenimiento\Tabla\DetalleController@getTable')->name('getTableDetalle');
    Route::get('destroy/{id}', 'Mantenimiento\Tabla\DetalleController@destroy')->name('mantenimiento.tabla.detalle.destroy');
    Route::post('store', 'Mantenimiento\Tabla\DetalleController@store')->name('mantenimiento.tabla.detalle.store');
    Route::put('update', 'Mantenimiento\Tabla\DetalleController@update')->name('mantenimiento.tabla.detalle.update');
});

//Empresas
Route::prefix('mantenimiento/empresas')->group(function() {
    Route::get('index', 'Mantenimiento\Empresa\EmpresaController@index')->name('mantenimiento.empresas.index');
    Route::get('getBusiness','Mantenimiento\Empresa\EmpresaController@getBusiness')->name('getBusiness');
    Route::get('create','Mantenimiento\Empresa\EmpresaController@create')->name('mantenimiento.empresas.create');
    Route::post('store', 'Mantenimiento\Empresa\EmpresaController@store')->name('mantenimiento.empresas.store');
    Route::get('destroy/{id}', 'Mantenimiento\Empresa\EmpresaController@destroy')->name('mantenimiento.empresas.destroy');
    Route::get('show/{id}', 'Mantenimiento\Empresa\EmpresaController@show')->name('mantenimiento.empresas.show');
    Route::get('edit/{id}', 'Mantenimiento\Empresa\EmpresaController@edit')->name('mantenimiento.empresas.edit');
    Route::put('update/{id}', 'Mantenimiento\Empresa\EmpresaController@update')->name('mantenimiento.empresas.update');
});
//Compras
//Categoria
Route::prefix('compras/categorias')->group(function() {
    Route::get('index', 'Compras\CategoriaController@index')->name('compras.categoria.index');
    Route::get('getCategory','Compras\CategoriaController@getCategory')->name('getCategory');
    Route::get('destroy/{id}', 'Compras\CategoriaController@destroy')->name('compras.categoria.destroy');
    Route::post('store', 'Compras\CategoriaController@store')->name('compras.categoria.store');
    Route::put('update', 'Compras\CategoriaController@update')->name('compras.categoria.update');
});
//Articulos
Route::prefix('compras/articulos')->group(function() {
    Route::get('create','Compras\ArticuloController@create')->name('compras.articulo.create');
    Route::get('edit/{id}','Compras\ArticuloController@edit')->name('compras.articulo.edit');
    Route::get('show/{id}','Compras\ArticuloController@show')->name('compras.articulo.show');
    Route::get('index', 'Compras\ArticuloController@index')->name('compras.articulo.index');
    Route::get('getArticle','Compras\ArticuloController@getArticle')->name('getArticle');
    Route::get('destroy/{id}', 'Compras\ArticuloController@destroy')->name('compras.articulo.destroy');
    Route::post('store', 'Compras\ArticuloController@store')->name('compras.articulo.store');
    Route::put('update/{id}', 'Compras\ArticuloController@update')->name('compras.articulo.update');
});
//Proveedores
Route::prefix('compras/proveedores')->group(function() {
    Route::get('index', 'Compras\ProveedorController@index')->name('compras.proveedor.index');
    Route::get('getProvider','Compras\ProveedorController@getProvider')->name('getProvider');
    Route::get('create','Compras\ProveedorController@create')->name('compras.proveedor.create');
    Route::post('store', 'Compras\ProveedorController@store')->name('compras.proveedor.store');
    Route::get('edit/{id}','Compras\ProveedorController@edit')->name('compras.proveedor.edit');
    Route::get('show/{id}','Compras\ProveedorController@show')->name('compras.proveedor.show');
    Route::put('update/{id}', 'Compras\ProveedorController@update')->name('compras.proveedor.update');
    Route::get('destroy/{id}', 'Compras\ProveedorController@destroy')->name('compras.proveedor.destroy');
});

//Ordenes de Compra
Route::prefix('compras/ordenes')->group(function() {
    Route::get('index', 'Compras\OrdenController@index')->name('compras.orden.index');
    Route::get('getOrder','Compras\OrdenController@getOrder')->name('getOrder');
    Route::get('create','Compras\OrdenController@create')->name('compras.orden.create');
    Route::post('store', 'Compras\OrdenController@store')->name('compras.orden.store');
    Route::get('edit/{id}','Compras\OrdenController@edit')->name('compras.orden.edit');
    Route::get('show/{id}','Compras\OrdenController@show')->name('compras.orden.show');
    Route::put('update/{id}', 'Compras\OrdenController@update')->name('compras.orden.update');
    Route::get('destroy/{id}', 'Compras\OrdenController@destroy')->name('compras.orden.destroy');
    Route::get('reporte/{id}','Compras\OrdenController@report')->name('compras.orden.reporte');
    Route::get('email/{id}','Compras\OrdenController@email')->name('compras.orden.email');
    Route::get('concretada/{id}','Compras\OrdenController@concretized')->name('compras.orden.concretada');
    Route::get('consultaEnvios/{id}','Compras\OrdenController@send')->name('compras.orden.envios');
    Route::get('documento/{id}','Compras\OrdenController@document')->name('compras.orden.documento');
    Route::get('nuevodocumento/{id}','Compras\OrdenController@newdocument')->name('compras.orden.nuevodocumento');

    Route::get('confirmarEliminar/{id}','Compras\OrdenController@confirmDestroy')->name('compras.orden.confirmDestroy');

    //Pagos
    Route::get('pagos/index/{id}', 'Compras\PagoController@index')->name('compras.pago.index');
    Route::get('getPay/{id}','Compras\PagoController@getPay')->name('getPay');
    Route::get('pagos/create/{id}', 'Compras\PagoController@create')->name('compras.pago.create');
    Route::post('pagos/store/', 'Compras\PagoController@store')->name('compras.pago.store');
    Route::get('pagos/destroy/', 'Compras\PagoController@destroy')->name('compras.pago.destroy');
    Route::get('pagos/show/', 'Compras\PagoController@show')->name('compras.pago.show');
});

Route::prefix('compras/documentos')->group(function(){

    Route::get('index', 'Compras\DocumentoController@index')->name('compras.documento.index');
    Route::get('getDocument','Compras\DocumentoController@getDocument')->name('getDocument');
    Route::get('create', 'Compras\DocumentoController@create')->name('compras.documento.create');
    Route::post('store', 'Compras\DocumentoController@store')->name('compras.documento.store');
    Route::get('edit/{id}','Compras\DocumentoController@edit')->name('compras.documento.edit');
    Route::put('update/{id}', 'Compras\DocumentoController@update')->name('compras.documento.update');
    Route::get('destroy/{id}', 'Compras\DocumentoController@destroy')->name('compras.documento.destroy');
    Route::get('show/{id}','Compras\DocumentoController@show')->name('compras.documento.show');
    Route::get('reporte/{id}','Compras\DocumentoController@report')->name('compras.documento.reporte');

    Route::get('tipoPago/{id}','Compras\DocumentoController@TypePay')->name('compras.documento.tipo_pago.existente');

    //Pagos
    Route::get('pagos/index/{id}', 'Compras\Documentos\PagoController@index')->name('compras.documentos.pago.index');
    Route::get('getPay/{id}','Compras\Documentos\PagoController@getPayDocument')->name('getPay.documentos');
    Route::get('pagos/create/{id}', 'Compras\Documentos\PagoController@create')->name('compras.documentos.pago.create');
    Route::post('pagos/store/', 'Compras\Documentos\PagoController@store')->name('compras.documentos.pago.store');
    Route::get('pagos/destroy/{id}', 'Compras\Documentos\PagoController@destroy')->name('compras.documentos.pago.destroy');
    Route::get('pagos/show/{id}', 'Compras\Documentos\PagoController@show')->name('compras.documentos.pago.show');
    Route::get('getBox/document/{id}', 'Compras\Documentos\PagoController@getBox')->name('compras.documentos.pago.getBox');

    //Pago Transferencia
    Route::get('transferencia/pagos/index/{id}', 'Compras\Documentos\TransferenciaController@index')->name('compras.documentos.transferencia.pago.index');
    Route::get('transferencia/getPay/{id}','Compras\Documentos\TransferenciaController@getPay')->name('compras.documentos.transferencia.getPay');
    Route::get('transferencia/pagos/create/{id}', 'Compras\Documentos\TransferenciaController@create')->name('compras.documentos.transferencia.pago.create');
    Route::post('transferencia/pagos/store/', 'Compras\Documentos\TransferenciaController@store')->name('compras.documentos.transferencia.pago.store');
    Route::get('transferencia/pagos/destroy/', 'Compras\Documentos\TransferenciaController@destroy')->name('compras.documentos.transferencia.pago.destroy');
    Route::get('transferencia/pagos/show/', 'Compras\Documentos\TransferenciaController@show')->name('compras.documentos.transferencia.pago.show');


});

//Seguridad
//Usuarios
Route::prefix('seguridad/usuarios')->group(function() {
    Route::get('index', 'Seguridad\UsuarioController@index')->name('seguridad.usuario.index');
    Route::get('getUsers','Seguridad\UsuarioController@getUsers')->name('getUsers');
    Route::get('create', 'Seguridad\UsuarioController@create')->name('seguridad.usuario.create');
    Route::get('edit/{id}', 'Seguridad\UsuarioController@edit')->name('seguridad.usuario.edit');
    Route::put('update/{id}', 'Seguridad\UsuarioController@update')->name('seguridad.usuario.update');
    Route::post('store', 'Seguridad\UsuarioController@store')->name('seguridad.usuario.store');
    Route::get('getEmployee', 'Seguridad\UsuarioController@getEmployee')->name('seguridad.usuario.getEmployee');
    Route::get('getEmployeeedit/{id}', 'Seguridad\UsuarioController@getEmployeeedit')->name('seguridad.usuario.getEmployee.edit');
    Route::get('destroy/{id}', 'Seguridad\UsuarioController@destroy')->name('seguridad.usuario.destroy');
});

//POS
//Caja Chica Pos
//Almacen
Route::prefix('pos/')->group(function() {
    Route::get('cajachica/index', 'Pos\CajaController@index')->name('pos.caja.index');
    Route::get('getBox','Pos\CajaController@getBox')->name('getBox');
    Route::get('cajachica/destroy/{id}', 'Pos\CajaController@destroy')->name('pos.caja.destroy');
    Route::get('cajachica/cerrar/{id}', 'Pos\CajaController@cerrar')->name('pos.caja.cerrar');
    Route::post('cajachica/store', 'Pos\CajaController@store')->name('pos.caja.store');
    Route::put('update', 'Pos\CajaController@update')->name('pos.caja.update');
    Route::get('getEmployee/caja_chica', 'Pos\CajaController@getEmployee')->name('pos.caja.getEmployee');
});


//Almacenes
//Almacen
Route::prefix('almacenes/almacen')->group(function() {
    Route::get('index', 'Almacenes\AlmacenController@index')->name('almacenes.almacen.index');
    Route::get('getRepository','Almacenes\AlmacenController@getRepository')->name('getRepository');
    Route::get('destroy/{id}', 'Almacenes\AlmacenController@destroy')->name('almacenes.almacen.destroy');
    Route::post('store', 'Almacenes\AlmacenController@store')->name('almacenes.almacen.store');
    Route::put('update', 'Almacenes\AlmacenController@update')->name('almacenes.almacen.update');
});

//Familias
Route::prefix('almacenes/categorias/pt')->group(function() {
    Route::get('index', 'Almacenes\FamiliaController@index')->name('almacenes.familias.index');
    Route::get('getfamilia','Almacenes\FamiliaController@getfamilia')->name('getfamilia');
    Route::get('destroy/{id}', 'Almacenes\FamiliaController@destroy')->name('almacenes.familias.destroy');
    Route::post('store', 'Almacenes\FamiliaController@store')->name('almacenes.familias.store');
    Route::put('update', 'Almacenes\FamiliaController@update')->name('almacenes.familias.update');
});

//SubFamilias
Route::prefix('almacenes/subcategoria/pt')->group(function() {
    Route::get('index', 'Almacenes\SubFamiliaController@index')->name('almacenes.subfamilia.index');
    Route::get('getsubfamilia','Almacenes\SubFamiliaController@getSub')->name('getSub');
    Route::get('destroy/{id}', 'Almacenes\SubFamiliaController@destroy')->name('almacenes.subfamilia.destroy');

    Route::post('store', 'Almacenes\SubFamiliaController@store')->name('almacenes.subfamilia.store');
    
    Route::put('update', 'Almacenes\SubFamiliaController@update')->name('almacenes.subfamilia.update');
    Route::post('getByFamilia', 'Almacenes\SubFamiliaController@getByFamilia')->name('almacenes.subfamilia.getByFamilia');

    Route::get('getBySubFamilia/{id}', 'Almacenes\SubFamiliaController@getBySubFamilia')->name('almacenes.subfamilia.getBySubFamilia');

    Route::get('subfamilia/familia', 'Almacenes\SubFamiliaController@getFamilia')->name('subfamilia.familia');
});

// Productos
Route::prefix('almacenes/productos')->group(function() {
    Route::get('/', 'Almacenes\ProductoController@index')->name('almacenes.producto.index');
    Route::get('/getTable', 'Almacenes\ProductoController@getTable')->name('almacenes.producto.getTable');
    Route::get('/registrar', 'Almacenes\ProductoController@create')->name('almacenes.producto.create');
    Route::post('/registrar', 'Almacenes\ProductoController@store')->name('almacenes.producto.store');
    Route::get('/actualizar/{id}', 'Almacenes\ProductoController@edit')->name('almacenes.producto.edit');
    Route::put('/actualizar/{id}', 'Almacenes\ProductoController@update')->name('almacenes.producto.update');
    Route::get('/datos/{id}', 'Almacenes\ProductoController@show')->name('almacenes.producto.show');
    Route::get('/destroy/{id}', 'Almacenes\ProductoController@destroy')->name('almacenes.producto.destroy');
    Route::post('/destroyDetalle', 'Almacenes\ProductoController@destroyDetalle')->name('almacenes.producto.destroyDetalle');
    Route::post('/getCodigo', 'Almacenes\ProductoController@getCodigo')->name('almacenes.producto.getCodigo');

    Route::get('/obtenerProducto/{id}', 'Almacenes\ProductoController@obtenerProducto')->name('almacenes.producto.obtenerProducto');

    Route::get('/productoDescripcion/{id}', 'Almacenes\ProductoController@productoDescripcion')->name('almacenes.producto.productoDescripcion');

});

// Composicion
Route::prefix('produccion/composicion')->group(function() {
    Route::get('/', 'Produccion\ComposicionController@index')->name('produccion.composicion.index');
    Route::get('/getTable', 'Produccion\ComposicionController@getTable')->name('produccion.composicion.getTable');
    Route::get('/registrar', 'Produccion\ComposicionController@create')->name('produccion.composicion.create');
    Route::post('/registrar', 'Produccion\ComposicionController@store')->name('produccion.composicion.store');
    Route::get('/actualizar/{id}', 'Produccion\ComposicionController@edit')->name('produccion.composicion.edit');
    Route::put('/actualizar/{id}', 'Produccion\ComposicionController@update')->name('produccion.composicion.update');
    Route::get('/datos/{id}', 'Produccion\ComposicionController@show')->name('produccion.composicion.show');
    Route::get('/destroy/{id}', 'Produccion\ComposicionController@destroy')->name('produccion.composicion.destroy');
    Route::post('/destroyDetalle', 'Produccion\ComposicionController@destroyDetalle')->name('produccion.composicion.destroyDetalle');
    Route::post('/getCodigo', 'Produccion\ComposicionController@getCodigo')->name('produccion.composicion.getCodigo');
});


// Ubigeo
Route::prefix('mantenimiento/ubigeo')->group(function() {
    Route::post('/provincias', 'Mantenimiento\Ubigeo\UbigeoController@provincias')->name('mantenimiento.ubigeo.provincias');
    Route::post('/distritos', 'Mantenimiento\Ubigeo\UbigeoController@distritos')->name('mantenimiento.ubigeo.distritos');
    Route::post('/api_ruc', 'Mantenimiento\Ubigeo\UbigeoController@api_ruc')->name('mantenimiento.ubigeo.api_ruc');
});

// Empleados
Route::prefix('mantenimiento/empleados')->group(function() {
    Route::get('/', 'Mantenimiento\Empleado\EmpleadoController@index')->name('mantenimiento.empleado.index');
    Route::get('/getTable', 'Mantenimiento\Empleado\EmpleadoController@getTable')->name('mantenimiento.empleado.getTable');
    Route::get('/registrar', 'Mantenimiento\Empleado\EmpleadoController@create')->name('mantenimiento.empleado.create');
    Route::post('/registrar', 'Mantenimiento\Empleado\EmpleadoController@store')->name('mantenimiento.empleado.store');
    Route::get('/actualizar/{id}', 'Mantenimiento\Empleado\EmpleadoController@edit')->name('mantenimiento.empleado.edit');
    Route::put('/actualizar/{id}', 'Mantenimiento\Empleado\EmpleadoController@update')->name('mantenimiento.empleado.update');
    Route::get('/datos/{id}', 'Mantenimiento\Empleado\EmpleadoController@show')->name('mantenimiento.empleado.show');
    Route::get('/destroy/{id}', 'Mantenimiento\Empleado\EmpleadoController@destroy')->name('mantenimiento.empleado.destroy');
    Route::post('/getDNI', 'Mantenimiento\Empleado\EmpleadoController@getDNI')->name('mantenimiento.empleado.getDni');
});

// Vendedores
Route::prefix('mantenimiento/vendedores')->group(function() {
    Route::get('/', 'Mantenimiento\Vendedor\VendedorController@index')->name('mantenimiento.vendedor.index');
    Route::get('/getTable', 'Mantenimiento\Vendedor\VendedorController@getTable')->name('mantenimiento.vendedor.getTable');
    Route::get('/registrar', 'Mantenimiento\Vendedor\VendedorController@create')->name('mantenimiento.vendedor.create');
    Route::post('/registrar', 'Mantenimiento\Vendedor\VendedorController@store')->name('mantenimiento.vendedor.store');
    Route::get('/actualizar/{id}', 'Mantenimiento\Vendedor\VendedorController@edit')->name('mantenimiento.vendedor.edit');
    Route::put('/actualizar/{id}', 'Mantenimiento\Vendedor\VendedorController@update')->name('mantenimiento.vendedor.update');
    Route::get('/datos/{id}', 'Mantenimiento\Vendedor\VendedorController@show')->name('mantenimiento.vendedor.show');
    Route::get('/destroy/{id}', 'Mantenimiento\Vendedor\VendedorController@destroy')->name('mantenimiento.vendedor.destroy');
    Route::post('/getDNI', 'Mantenimiento\Vendedor\VendedorController@getDNI')->name('mantenimiento.vendedor.getDni');
});

// Clientes
Route::prefix('ventas/clientes')->group(function() {
    Route::get('/', 'Ventas\ClienteController@index')->name('ventas.cliente.index');
    Route::get('/getTable', 'Ventas\ClienteController@getTable')->name('ventas.cliente.getTable');
    Route::get('/registrar', 'Ventas\ClienteController@create')->name('ventas.cliente.create');
    Route::post('/registrar', 'Ventas\ClienteController@store')->name('ventas.cliente.store');
    Route::get('/actualizar/{id}', 'Ventas\ClienteController@edit')->name('ventas.cliente.edit');
    Route::put('/actualizar/{id}', 'Ventas\ClienteController@update')->name('ventas.cliente.update');
    Route::get('/datos/{id}', 'Ventas\ClienteController@show')->name('ventas.cliente.show');
    Route::get('/destroy/{id}', 'Ventas\ClienteController@destroy')->name('ventas.cliente.destroy');
    Route::post('/getDocumento', 'Ventas\ClienteController@getDocumento')->name('ventas.cliente.getDocumento');

    //Tiendas
    Route::get('tiendas/index/{id}', 'Ventas\TiendaController@index')->name('clientes.tienda.index');
    Route::get('tiendas/getShop/{id}','Ventas\TiendaController@getShop')->name('clientes.tienda.shop');
    Route::get('tiendas/create/{id}', 'Ventas\TiendaController@create')->name('clientes.tienda.create');
    Route::post('tiendas/store/', 'Ventas\TiendaController@store')->name('clientes.tienda.store');
    Route::put('tiendas/update/{id}', 'Ventas\TiendaController@update')->name('clientes.tienda.update');
    Route::get('tiendas/destroy/{id}', 'Ventas\TiendaController@destroy')->name('clientes.tienda.destroy');
    Route::get('tiendas/show/{id}', 'Ventas\TiendaController@show')->name('clientes.tienda.show');
    Route::get('tiendas/actualizar/{id}', 'Ventas\TiendaController@edit')->name('clientes.tienda.edit');


});


// Cotizaciones
Route::prefix('ventas/cotizaciones')->group(function() {
    Route::get('/', 'Ventas\CotizacionController@index')->name('ventas.cotizacion.index');
    Route::get('/getTable', 'Ventas\CotizacionController@getTable')->name('ventas.cotizacion.getTable');
    Route::get('/registrar', 'Ventas\CotizacionController@create')->name('ventas.cotizacion.create');
    Route::post('/registrar', 'Ventas\CotizacionController@store')->name('ventas.cotizacion.store');
    Route::get('/actualizar/{id}', 'Ventas\CotizacionController@edit')->name('ventas.cotizacion.edit');
    Route::put('/actualizar/{id}', 'Ventas\CotizacionController@update')->name('ventas.cotizacion.update');
    Route::get('/datos/{id}', 'Ventas\CotizacionController@show')->name('ventas.cotizacion.show');
    Route::get('/destroy/{id}', 'Ventas\CotizacionController@destroy')->name('ventas.cotizacion.destroy');
    Route::get('reporte/{id}','Ventas\CotizacionController@report')->name('ventas.cotizacion.reporte');
    Route::get('email/{id}','Ventas\CotizacionController@email')->name('ventas.cotizacion.email');
    Route::get('documento/{id}','Ventas\CotizacionController@document')->name('ventas.cotizacion.documento');
    
});

// Documentos - cotizaciones
Route::prefix('ventas/documentos')->group(function(){

    Route::get('index', 'Ventas\DocumentoController@index')->name('ventas.documento.index');
    Route::get('getDocument','Ventas\DocumentoController@getDocument')->name('ventas.getDocument');
    Route::get('create', 'Ventas\DocumentoController@create')->name('ventas.documento.create');
    Route::post('store', 'Ventas\DocumentoController@store')->name('ventas.documento.store');
    Route::get('edit/{id}','Ventas\DocumentoController@edit')->name('ventas.documento.edit');
    Route::put('update/{id}', 'Ventas\DocumentoController@update')->name('ventas.documento.update');
    Route::get('destroy/{id}', 'Ventas\DocumentoController@destroy')->name('ventas.documento.destroy');
    Route::get('show/{id}','Ventas\DocumentoController@show')->name('ventas.documento.show');
    Route::get('reporte/{id}','Ventas\DocumentoController@report')->name('ventas.documento.reporte');
    Route::get('tipoPago/{id}','Ventas\DocumentoController@TypePay')->name('ventas.documento.tipo_pago.existente');

    //Pagos
    // Route::get('pagos/index/{id}', 'Compras\Documentos\PagoController@index')->name('compras.documentos.pago.index');
    // Route::get('getPay/{id}','Compras\Documentos\PagoController@getPayDocument')->name('getPay.documentos');
    // Route::get('pagos/create/{id}', 'Compras\Documentos\PagoController@create')->name('compras.documentos.pago.create');
    // Route::post('pagos/store/', 'Compras\Documentos\PagoController@store')->name('compras.documentos.pago.store');
    // Route::get('pagos/destroy/{id}', 'Compras\Documentos\PagoController@destroy')->name('compras.documentos.pago.destroy');
    // Route::get('pagos/show/{id}', 'Compras\Documentos\PagoController@show')->name('compras.documentos.pago.show');
    // Route::get('getBox/document/{id}', 'Compras\Documentos\PagoController@getBox')->name('compras.documentos.pago.getBox');

    //Pago Transferencia
    // Route::get('transferencia/pagos/index/{id}', 'Compras\Documentos\TransferenciaController@index')->name('compras.documentos.transferencia.pago.index');
    // Route::get('transferencia/getPay/{id}','Compras\Documentos\TransferenciaController@getPay')->name('compras.documentos.transferencia.getPay');
    // Route::get('transferencia/pagos/create/{id}', 'Compras\Documentos\TransferenciaController@create')->name('compras.documentos.transferencia.pago.create');
    // Route::post('transferencia/pagos/store/', 'Compras\Documentos\TransferenciaController@store')->name('compras.documentos.transferencia.pago.store');
    // Route::get('transferencia/pagos/destroy/', 'Compras\Documentos\TransferenciaController@destroy')->name('compras.documentos.transferencia.pago.destroy');
    // Route::get('transferencia/pagos/show/', 'Compras\Documentos\TransferenciaController@show')->name('compras.documentos.transferencia.pago.show');





});


// Talonarios
Route::prefix('mantenimiento/talonarios')->group(function() {
    Route::get('/', 'Mantenimiento\TalonarioController@index')->name('mantenimiento.talonario.index');
    Route::get('/getTable', 'Mantenimiento\TalonarioController@getTable')->name('mantenimiento.talonario.getTable');
    Route::post('/registrar', 'Mantenimiento\TalonarioController@store')->name('mantenimiento.talonario.store');
    Route::put('/actualizar', 'Mantenimiento\TalonarioController@update')->name('mantenimiento.talonario.update');
    Route::get('/destroy/{id}', 'Mantenimiento\TalonarioController@destroy')->name('mantenimiento.talonario.destroy');
});

//Registro_Sanitario
Route::prefix('invdesarrollo/registro_sanitario')->group(function() {
    Route::get('index', 'InvDesarrollo\RegistroSanitarioController@index')->name('invdesarrollo.registro_sanitario.index');
    Route::get('getRegistroSanitario','InvDesarrollo\RegistroSanitarioController@getRegistroSanitario')->name('getRegistroSanitario');
    Route::get('destroy/{id}', 'InvDesarrollo\RegistroSanitarioController@destroy')->name('invdesarrollo.registro_sanitario.destroy');
    Route::post('store', 'InvDesarrollo\RegistroSanitarioController@store')->name('invdesarrollo.registro_sanitario.store');
    Route::put('update', 'InvDesarrollo\RegistroSanitarioController@update')->name('invdesarrollo.registro_sanitario.update');
});


//Prototipos
Route::prefix('invdesarrollo/prototipos')->group(function() {
    Route::get('index', 'InvDesarrollo\PrototipoController@index')->name('invdesarrollo.prototipo.index');
    Route::get('getPrototipo','InvDesarrollo\PrototipoController@getPrototipo')->name('getPrototipo');
    Route::get('create','InvDesarrollo\PrototipoController@create')->name('invdesarrollo.prototipo.create');
    Route::post('store', 'InvDesarrollo\PrototipoController@store')->name('invdesarrollo.prototipo.store');
    Route::get('edit/{id}','InvDesarrollo\PrototipoController@edit')->name('invdesarrollo.prototipo.edit');
    Route::get('show/{id}','InvDesarrollo\PrototipoController@show')->name('invdesarrollo.prototipo.show');
    Route::put('update/{id}', 'InvDesarrollo\PrototipoController@update')->name('invdesarrollo.prototipo.update');
    Route::get('destroy/{id}', 'InvDesarrollo\PrototipoController@destroy')->name('invdesarrollo.prototipo.destroy');
    Route::get('reporte/{id}','InvDesarrollo\PrototipoController@report')->name('invdesarrollo.prototipo.reporte');
    Route::get('email/{id}','InvDesarrollo\PrototipoController@email')->name('invdesarrollo.prototipo.email');
});

// Guia Interna
Route::prefix('invdesarrollo/guias')->group(function() {
    Route::get('index', 'InvDesarrollo\GuiaController@index')->name('invdesarrollo.guia.index');
    Route::get('getGuia','InvDesarrollo\GuiaController@getGuia')->name('getGuia');
    Route::get('create','InvDesarrollo\GuiaController@create')->name('invdesarrollo.guia.create');
    Route::post('store', 'InvDesarrollo\GuiaController@store')->name('invdesarrollo.guia.store');
    Route::get('edit/{id}','InvDesarrollo\GuiaController@edit')->name('invdesarrollo.guia.edit');
    Route::get('show/{id}','InvDesarrollo\GuiaController@show')->name('invdesarrollo.guia.show');
    Route::put('update/{id}', 'InvDesarrollo\GuiaController@update')->name('invdesarrollo.guia.update');
    Route::get('destroy/{id}', 'InvDesarrollo\GuiaController@destroy')->name('invdesarrollo.guia.destroy');
    Route::get('reporte/{id}','InvDesarrollo\GuiaController@report')->name('invdesarrollo.guia.reporte');
    Route::get('email/{id}','InvDesarrollo\GuiaController@email')->name('invdesarrollo.guia.email');
});

// Maquinaria_equipos
Route::prefix('almacenes/maquinarias_equipos')->group(function() {
    Route::get('index', 'Almacenes\Maquinarias_equiposController@index')->name('almacenes.maquinaria_equipo.index');
    Route::get('getPrototipo','Almacenes\Maquinarias_equiposController@getMaquinaria_equipo')->name('getMaquinaria_equipo');
    Route::get('destroy/{id}', 'Almacenes\Maquinarias_equiposController@destroy')->name('almacenes.maquinaria_equipo.destroy');
    Route::post('store', 'Almacenes\Maquinarias_equiposController@store')->name('almacenes.maquinaria_equipo.store');
    Route::put('update', 'Almacenes\Maquinarias_equiposController@update')->name('almacenes.maquinaria_equipo.update');
});

// Linea de Produccionlinea_produccion
Route::prefix('produccion/lineas_produccion')->group(function() {
    Route::get('index', 'Produccion\LineaProduccionController@index')->name('produccion.linea_produccion.index');
    Route::get('getLineaProduccion','Produccion\LineaProduccionController@getLineaProduccion')->name('getLineaProduccion');
    Route::get('create','Produccion\LineaProduccionController@create')->name('produccion.linea_produccion.create');
    Route::post('store', 'Produccion\LineaProduccionController@store')->name('produccion.linea_produccion.store');
    Route::get('edit/{id}','Produccion\LineaProduccionController@edit')->name('produccion.linea_produccion.edit');
    Route::get('show/{id}','Produccion\LineaProduccionController@show')->name('produccion.linea_produccion.show');
    Route::put('update/{id}', 'Produccion\LineaProduccionController@update')->name('produccion.linea_produccion.update');
    Route::get('destroy/{id}', 'Produccion\LineaProduccionController@destroy')->name('produccion.linea_produccion.destroy');
    Route::get('reporte/{id}','Produccion\LineaProduccionController@report')->name('produccion.linea_produccion.reporte');
    Route::get('email/{id}','Produccion\LineaProduccionController@email')->name('produccion.linea_produccion.email');
});