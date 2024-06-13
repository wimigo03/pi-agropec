<?php

Route::prefix('libro-mayor-centro-cuenta-f')->name('libro.mayor.centro.cuenta.f.')->middleware(['auth:sanctum',config('jetstream.auth_session'),'verified',])->group(function () {
    Route::get('/', 'LibroMayorCentroCuentaFController@index')->name('index')->middleware('can:libro.mayor.centro.cuenta.f.index');
    Route::get('/get_centros', 'LibroMayorCentroCuentaFController@getCentros')->name('get.centros')->middleware('can:libro.mayor.centro.cuenta.f.index');
    Route::get('/get_subcentros', 'LibroMayorCentroCuentaFController@getSubCentros')->name('get.subcentros')->middleware('can:libro.mayor.centro.cuenta.f.index');
    Route::get('/get_plancuentas', 'LibroMayorCentroCuentaFController@getPlanCuentas')->name('get.plancuentas')->middleware('can:libro.mayor.centro.cuenta.f.index');
    Route::get('/search', 'LibroMayorCentroCuentaFController@search')->name('search')->middleware('can:libro.mayor.centro.cuenta.f.index');
    Route::get('/excel', 'LibroMayorCentroCuentaFController@excel')->name('excel')->middleware('can:libro.mayor.centro.cuenta.f.index');
    Route::get('/pdf', 'LibroMayorCentroCuentaFController@pdf')->name('pdf')->middleware('can:libro.mayor.centro.cuenta.f.index');
});
