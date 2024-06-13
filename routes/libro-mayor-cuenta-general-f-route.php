<?php

Route::prefix('libro-mayor-cuenta-general-f')->name('libro.mayor.cuenta.general.f.')->middleware(['auth:sanctum',config('jetstream.auth_session'),'verified',])->group(function () {
    Route::get('/', 'LibroMayorCuentaGeneralFController@index')->name('index')->middleware('can:libro.mayor.cuenta.general.f.index');
    Route::get('/get_plancuentas', 'LibroMayorCuentaGeneralFController@getPlanCuentas')->name('get.plancuentas')->middleware('can:libro.mayor.cuenta.general.f.index');
    Route::get('/search', 'LibroMayorCuentaGeneralFController@search')->name('search')->middleware('can:libro.mayor.cuenta.general.f.index');
    Route::get('/excel', 'LibroMayorCuentaGeneralFController@excel')->name('excel')->middleware('can:libro.mayor.cuenta.general.f.index');
    Route::get('/pdf', 'LibroMayorCuentaGeneralFController@pdf')->name('pdf')->middleware('can:libro.mayor.cuenta.general.f.index');
});
