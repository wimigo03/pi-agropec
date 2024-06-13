<?php

Route::prefix('libro-mayor-cuenta-1-99-f')->name('libro.mayor.cuenta.1.99.f.')->middleware(['auth:sanctum',config('jetstream.auth_session'),'verified',])->group(function () {
    Route::get('/', 'LibroMayorCuenta199FController@index')->name('index')->middleware('can:libro.mayor.cuenta.1.99.f.index');
    Route::get('/get_plancuentas', 'LibroMayorCuenta199FController@getPlanCuentas')->name('get.plancuentas')->middleware('can:libro.mayor.cuenta.1.99.f.index');
    Route::get('/search', 'LibroMayorCuenta199FController@search')->name('search')->middleware('can:libro.mayor.cuenta.1.99.f.index');
    Route::get('/excel', 'LibroMayorCuenta199FController@excel')->name('excel')->middleware('can:libro.mayor.cuenta.1.99.f.index');
    Route::get('/pdf', 'LibroMayorCuenta199FController@pdf')->name('pdf')->middleware('can:libro.mayor.cuenta.1.99.f.index');
});
