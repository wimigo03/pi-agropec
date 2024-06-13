<?php

Route::prefix('libro-mayor-cuenta-general')->name('libro.mayor.cuenta.general.')->middleware(['auth:sanctum',config('jetstream.auth_session'),'verified',])->group(function () {
    //Route::get('/index-after', 'LibroMayorCuentaGeneralController@indexAfter')->name('indexAfter')->middleware('can:libro.mayor.cuenta.general.index');
    Route::get('/', 'LibroMayorCuentaGeneralController@index')->name('index')->middleware('can:libro.mayor.cuenta.general.index');
    Route::get('/get_plancuentas', 'LibroMayorCuentaGeneralController@getPlanCuentas')->name('get.plancuentas')->middleware('can:libro.mayor.cuenta.general.index');
    Route::get('/search', 'LibroMayorCuentaGeneralController@search')->name('search')->middleware('can:libro.mayor.cuenta.general.index');
    Route::get('/excel', 'LibroMayorCuentaGeneralController@excel')->name('excel')->middleware('can:libro.mayor.cuenta.general.index');
    Route::get('/pdf', 'LibroMayorCuentaGeneralController@pdf')->name('pdf')->middleware('can:libro.mayor.cuenta.general.index');
});
