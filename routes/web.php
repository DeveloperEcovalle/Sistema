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
    Route::get('index', 'compras\CategoriaController@index')->name('compras.categoria.index');
    Route::get('getCategory','compras\CategoriaController@getCategory')->name('getCategory');
    Route::get('destroy/{id}', 'compras\CategoriaController@destroy')->name('compras.categoria.destroy');
    Route::post('store', 'compras\CategoriaController@store')->name('compras.categoria.store');
    Route::put('update', 'compras\CategoriaController@update')->name('compras.categoria.update');
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
    Route::post('update/{id}', 'Compras\ProveedorController@update')->name('compras.proveedor.update');
    Route::get('destroy/{id}', 'Compras\ProveedorController@destroy')->name('compras.proveedor.destroy');
});


//Almacenes
//Almacen
Route::prefix('almacenes/almacen')->group(function() {
    Route::get('index', 'almacenes\AlmacenController@index')->name('almacenes.almacen.index');
    Route::get('getRepository','almacenes\AlmacenController@getRepository')->name('getRepository');
    Route::get('destroy/{id}', 'almacenes\AlmacenController@destroy')->name('almacenes.almacen.destroy');
    Route::post('store', 'almacenes\AlmacenController@store')->name('almacenes.almacen.store');
    Route::put('update', 'almacenes\AlmacenController@update')->name('almacenes.almacen.update');
});

// Empleados
Route::prefix('mantenimiento/empleados')->group(function() {
    Route::get('/', 'Mantenimiento\Empleado\EmpleadoController@index')->name('mantenimiento.empleado.index');
    Route::get('/getTable', 'Mantenimiento\Empleado\EmpleadoController@getTable')->name('mantenimiento.empleado.getTable');
    Route::get('/registrar', 'Mantenimiento\Empleado\EmpleadoController@create')->name('mantenimiento.empleado.create');
    Route::post('/registrar', 'Mantenimiento\Empleado\EmpleadoController@store')->name('mantenimiento.empleado.store');
    Route::get('/actualizar/{id}', 'Mantenimiento\Empleado\EmpleadoController@edit')->name('mantenimiento.empleado.edit');
    Route::post('/actualizar/{id}', 'Mantenimiento\Empleado\EmpleadoController@update')->name('mantenimiento.empleado.update');
    Route::put('/activate/{id}', 'Mantenimiento\Empleado\EmpleadoController@active')->name('mantenimiento.empleado.activate');
    Route::put('/deactivate/{id}', 'Mantenimiento\Empleado\EmpleadoController@deactivate')->name('mantenimiento.empleado.deactivate');
});
