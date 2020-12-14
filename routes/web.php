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
Route::get('mantenimiento/tablas/generales', 'Mantenimiento\Tabla\GeneralController@index')->name('mantenimiento.tabla.general.index');

Route::get('mantenimiento/tablas/generales/getTable','Mantenimiento\Tabla\GeneralController@getTable')->name('getTable');

Route::put('mantenimiento/tablas/generales/update', 'Mantenimiento\Tabla\GeneralController@update')->name('mantenimiento.tabla.general.update');

//Tabla Detalle
Route::get('mantenimiento/tablas/detalles/{id}', 'Mantenimiento\Tabla\DetalleController@index')->name('mantenimiento.tabla.detalle.index');

Route::get('mantenimiento/tablas/generales/getTable/{id}','Mantenimiento\Tabla\DetalleController@getTable')->name('getTableDetalle');

Route::get('mantenimiento/tablas/detalles/destroy/{id}', 'Mantenimiento\Tabla\DetalleController@destroy')->name('mantenimiento.tabla.detalle.destroy');

Route::post('mantenimiento/tablas/detalles/store', 'Mantenimiento\Tabla\DetalleController@store')->name('mantenimiento.tabla.detalle.store');

Route::put('mantenimiento/tablas/detalles/update', 'Mantenimiento\Tabla\DetalleController@update')->name('mantenimiento.tabla.detalle.update');

//Empresas
Route::get('mantenimiento/empresas', 'Mantenimiento\EmpresaController@index')->name('mantenimiento.empresas.index');

Route::get('mantenimiento/empresas/getBusiness','Mantenimiento\EmpresaController@getBusiness')->name('getBusiness');

Route::get('mantenimiento/empresas/create','Mantenimiento\EmpresaController@create')->name('mantenimiento.empresas.create');

Route::post('mantenimiento/empresas/create', 'Mantenimiento\EmpresaController@store')->name('mantenimiento.empresas.store');

Route::get('mantenimiento/empresas/destroy/{id}', 'Mantenimiento\EmpresaController@destroy')->name('mantenimiento.empresas.destroy');

Route::get('mantenimiento/empresas/show/{id}', 'Mantenimiento\EmpresaController@show')->name('mantenimiento.empresas.show');

Route::get('mantenimiento/empresas/edit/{id}', 'Mantenimiento\EmpresaController@edit')->name('mantenimiento.empresas.edit');

Route::put('mantenimiento/empresas/update/{id}', 'Mantenimiento\EmpresaController@update')->name('mantenimiento.empresas.update');

//Compras
//Categoria
Route::get('compras/categorias/index', 'compras\CategoriaController@index')->name('compras.categoria.index');

Route::get('compras/categorias/getCategory','compras\CategoriaController@getCategory')->name('getCategory');

Route::get('compras/categorias/destroy/{id}', 'compras\CategoriaController@destroy')->name('compras.categoria.destroy');

Route::post('compras/categorias/store', 'compras\CategoriaController@store')->name('compras.categoria.store');

Route::put('compras/categorias/update', 'compras\CategoriaController@update')->name('compras.categoria.update');

//Articulos

Route::get('compras/articulos/create','Compras\ArticuloController@create')->name('compras.articulo.create');

Route::get('compras/articulos/edit/{id}','Compras\ArticuloController@edit')->name('compras.articulo.edit');

Route::get('compras/articulos/show/{id}','Compras\ArticuloController@show')->name('compras.articulo.show');

Route::get('compras/articulos/index', 'Compras\ArticuloController@index')->name('compras.articulo.index');

Route::get('compras/articulos/getArticle','Compras\ArticuloController@getArticle')->name('getArticle');

Route::get('compras/articulos/destroy/{id}', 'Compras\ArticuloController@destroy')->name('compras.articulo.destroy');

Route::post('compras/articulos/store', 'Compras\ArticuloController@store')->name('compras.articulo.store');

Route::put('compras/articulos/update/{id}', 'Compras\ArticuloController@update')->name('compras.articulo.update');

//Proveedores

Route::get('compras/proveedores/index', 'Compras\ProveedorController@index')->name('compras.proveedor.index');

Route::get('compras/proveedores/getProvider','Compras\ProveedorController@getProvider')->name('getProvider');

Route::get('compras/proveedores/create','Compras\ProveedorController@create')->name('compras.proveedor.create');

Route::post('compras/proveedores/store', 'Compras\ProveedorController@store')->name('compras.proveedor.store');









//Almacenes
//Almacen
Route::get('almacenes/almacen/index', 'almacenes\AlmacenController@index')->name('almacenes.almacen.index');

Route::get('almacenes/almacen/getRepository','almacenes\AlmacenController@getRepository')->name('getRepository');

Route::get('almacenes/almacen/destroy/{id}', 'almacenes\AlmacenController@destroy')->name('almacenes.almacen.destroy');

Route::post('almacenes/almacen/store', 'almacenes\AlmacenController@store')->name('almacenes.almacen.store');

Route::put('almacenes/almacen/update', 'almacenes\AlmacenController@update')->name('almacenes.almacen.update');


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

// Ubigeo
Route::prefix('mantenimiento/ubigeo')->group(function() {
    Route::post('/provincias', 'Mantenimiento\Ubigeo\UbigeoController@provincias')->name('mantenimiento.ubigeo.provincias');
    Route::post('/distritos', 'Mantenimiento\Ubigeo\UbigeoController@distritos')->name('mantenimiento.ubigeo.distritos');
});
