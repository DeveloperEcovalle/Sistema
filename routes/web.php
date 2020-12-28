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
    Route::get('index', 'Mantenimiento\EmpresaController@index')->name('mantenimiento.empresas.index');
    Route::get('getBusiness','Mantenimiento\EmpresaController@getBusiness')->name('getBusiness');
    Route::get('create','Mantenimiento\EmpresaController@create')->name('mantenimiento.empresas.create');
    Route::post('store', 'Mantenimiento\EmpresaController@store')->name('mantenimiento.empresas.store');
    Route::get('destroy/{id}', 'Mantenimiento\EmpresaController@destroy')->name('mantenimiento.empresas.destroy');
    Route::get('show/{id}', 'Mantenimiento\EmpresaController@show')->name('mantenimiento.empresas.show');
    Route::get('edit/{id}', 'Mantenimiento\EmpresaController@edit')->name('mantenimiento.empresas.edit');
    Route::put('update/{id}', 'Mantenimiento\EmpresaController@update')->name('mantenimiento.empresas.update');
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
    //Pagos
    Route::get('pagos/index/{id}', 'Compras\PagoController@index')->name('compras.pago.index');
    Route::get('getPay/{id}','Compras\PagoController@getPay')->name('getPay');
    Route::get('pagos/create/{id}', 'Compras\PagoController@create')->name('compras.pago.create');
    Route::post('pagos/store/', 'Compras\PagoController@store')->name('compras.pago.store');
    Route::get('pagos/edit/', 'Compras\PagoController@edit')->name('compras.pago.edit');
    Route::put('pagos/update/{id}', 'Compras\PagoController@update')->name('compras.pago.update');
    Route::get('pagos/destroy/', 'Compras\PagoController@destroy')->name('compras.pago.destroy');
    Route::get('pagos/show/', 'Compras\PagoController@show')->name('compras.pago.show');

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
});

//Familias
Route::prefix('produccion/familias')->group(function() {
    Route::get('index', 'Produccion\FamiliaController@index')->name('produccion.familias.index');
    Route::get('getfamilia','Produccion\FamiliaController@getfamilia')->name('getfamilia');
    Route::get('destroy/{id}', 'Produccion\FamiliaController@destroy')->name('produccion.familias.destroy');
    Route::post('store', 'Produccion\FamiliaController@store')->name('produccion.familias.store');
    Route::put('update', 'Produccion\FamiliaController@update')->name('produccion.familias.update');
});

//SubFamilias
Route::prefix('produccion/subfamilias')->group(function() {
    Route::get('index', 'Produccion\SubFamiliaController@index')->name('produccion.subfamilia.index');
    Route::get('getsubfamilia','Produccion\SubFamiliaController@getfamilia')->name('getsubfamilia');
    Route::get('destroy/{id}', 'Produccion\SubFamiliaController@destroy')->name('produccion.subfamilia.destroy');
    Route::post('store', 'Produccion\SubFamiliaController@store')->name('produccion.subfamilia.store');
    Route::put('update', 'Produccion\SubFamiliaController@update')->name('produccion.subfamilia.update');
    Route::post('getByFamilia', 'Produccion\SubFamiliaController@getByFamilia')->name('produccion.subfamilia.getByFamilia');
});

// Productos
Route::prefix('produccion/productos')->group(function() {
    Route::get('/', 'Produccion\ProductoController@index')->name('produccion.producto.index');
    Route::get('/getTable', 'Produccion\ProductoController@getTable')->name('produccion.producto.getTable');
    Route::get('/registrar', 'Produccion\ProductoController@create')->name('produccion.producto.create');
    Route::post('/registrar', 'Produccion\ProductoController@store')->name('produccion.producto.store');
    Route::get('/actualizar/{id}', 'Produccion\ProductoController@edit')->name('produccion.producto.edit');
    Route::put('/actualizar/{id}', 'Produccion\ProductoController@update')->name('produccion.producto.update');
    Route::get('/datos/{id}', 'Produccion\ProductoController@show')->name('produccion.producto.show');
    Route::get('/destroy/{id}', 'Produccion\ProductoController@destroy')->name('produccion.producto.destroy');
    Route::post('/destroyDetalle', 'Produccion\ProductoController@destroyDetalle')->name('produccion.producto.destroyDetalle');
    Route::post('/getCodigo', 'Produccion\ProductoController@getCodigo')->name('produccion.producto.getCodigo');
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
});
