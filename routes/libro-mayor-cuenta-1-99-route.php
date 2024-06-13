<?php

Route::prefix('libro-mayor-cuenta-1-99')->name('libro.mayor.cuenta.1.99.')->middleware(['auth:sanctum',config('jetstream.auth_session'),'verified',])->group(function () {
    Route::get('/', 'LibroMayorCuenta199Controller@index')->name('index')->middleware('can:libro.mayor.cuenta.1.99.index');
    Route::get('/get_plancuentas', 'LibroMayorCuenta199Controller@getPlanCuentas')->name('get.plancuentas')->middleware('can:libro.mayor.cuenta.1.99.index');
    Route::get('/search', 'LibroMayorCuenta199Controller@search')->name('search')->middleware('can:libro.mayor.cuenta.1.99.index');
    Route::get('/excel', 'LibroMayorCuenta199Controller@excel')->name('excel')->middleware('can:libro.mayor.cuenta.1.99.index');
    Route::get('/pdf', 'LibroMayorCuenta199Controller@pdf')->name('pdf')->middleware('can:libro.mayor.cuenta.1.99.index');
});
