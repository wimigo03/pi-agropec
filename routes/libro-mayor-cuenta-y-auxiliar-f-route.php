<?php

Route::prefix('libro-mayor-cuenta-y-auxiliar-f')->name('libro.mayor.cuenta.y.auxiliar.f.')->middleware(['auth:sanctum',config('jetstream.auth_session'),'verified',])->group(function () {
    Route::get('/', 'LibroMayorCuentaYAuxiliarFController@index')->name('index')->middleware('can:libro.mayor.cuenta.y.auxiliar.f.index');
    Route::get('/get_plancuentas', 'LibroMayorCuentaYAuxiliarFController@getPlanCuentas')->name('get.plancuentas')->middleware('can:libro.mayor.cuenta.y.auxiliar.f.index');
    Route::get('/get_plancuentasauxiliares', 'LibroMayorCuentaYAuxiliarFController@getPlanCuentasAuxiliares')->name('get.plancuentasauxiliares')->middleware('can:libro.mayor.cuenta.y.auxiliar.f.index');
    Route::get('/search', 'LibroMayorCuentaYAuxiliarFController@search')->name('search')->middleware('can:libro.mayor.cuenta.y.auxiliar.f.index');
    Route::get('/excel', 'LibroMayorCuentaYAuxiliarFController@excel')->name('excel')->middleware('can:libro.mayor.cuenta.y.auxiliar.f.index');
    Route::get('/pdf', 'LibroMayorCuentaYAuxiliarFController@pdf')->name('pdf')->middleware('can:libro.mayor.cuenta.y.auxiliar.f.index');
});
