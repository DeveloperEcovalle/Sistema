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
