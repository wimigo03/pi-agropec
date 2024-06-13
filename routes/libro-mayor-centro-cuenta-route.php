<?php

Route::prefix('libro-mayor-centro-cuenta')->name('libro.mayor.centro.cuenta.')->middleware(['auth:sanctum',config('jetstream.auth_session'),'verified',])->group(function () {
    Route::get('/', 'LibroMayorCentroCuentaController@index')->name('index')->middleware('can:libro.mayor.centro.cuenta.index');
    Route::get('/get_centros', 'LibroMayorCentroCuentaController@getCentros')->name('get.centros')->middleware('can:libro.mayor.centro.cuenta.index');
    Route::get('/get_subcentros', 'LibroMayorCentroCuentaController@getSubCentros')->name('get.subcentros')->middleware('can:libro.mayor.centro.cuenta.index');
    Route::get('/get_plancuentas', 'LibroMayorCentroCuentaController@getPlanCuentas')->name('get.plancuentas')->middleware('can:libro.mayor.centro.cuenta.index');
    Route::get('/search', 'LibroMayorCentroCuentaController@search')->name('search')->middleware('can:libro.mayor.centro.cuenta.index');
    Route::get('/excel', 'LibroMayorCentroCuentaController@excel')->name('excel')->middleware('can:libro.mayor.centro.cuenta.index');
    Route::get('/pdf', 'LibroMayorCentroCuentaController@pdf')->name('pdf')->middleware('can:libro.mayor.centro.cuenta.index');
});
