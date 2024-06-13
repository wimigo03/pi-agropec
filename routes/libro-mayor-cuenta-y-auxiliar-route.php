<?php

Route::prefix('libro-mayor-cuenta-y-auxiliar')->name('libro.mayor.cuenta.y.auxiliar.')->middleware(['auth:sanctum',config('jetstream.auth_session'),'verified',])->group(function () {
    Route::get('/', 'LibroMayorCuentaYAuxiliarController@index')->name('index')->middleware('can:libro.mayor.cuenta.y.auxiliar.index');
    Route::get('/get_plancuentas', 'LibroMayorCuentaYAuxiliarController@getPlanCuentas')->name('get.plancuentas')->middleware('can:libro.mayor.cuenta.y.auxiliar.index');
    Route::get('/get_plancuentasauxiliares', 'LibroMayorCuentaYAuxiliarController@getPlanCuentasAuxiliares')->name('get.plancuentasauxiliares')->middleware('can:libro.mayor.cuenta.y.auxiliar.index');
    Route::get('/search', 'LibroMayorCuentaYAuxiliarController@search')->name('search')->middleware('can:libro.mayor.cuenta.y.auxiliar.index');
    Route::get('/excel', 'LibroMayorCuentaYAuxiliarController@excel')->name('excel')->middleware('can:libro.mayor.cuenta.y.auxiliar.index');
    Route::get('/pdf', 'LibroMayorCuentaYAuxiliarController@pdf')->name('pdf')->middleware('can:libro.mayor.cuenta.y.auxiliar.index');
});
