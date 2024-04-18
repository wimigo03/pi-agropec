<?php

Route::prefix('libro-mayor-cuenta-general-f')->name('libro.mayor.cuenta.general.f.')->middleware(['auth:sanctum',config('jetstream.auth_session'),'verified',])->group(function () {
    Route::get('/{empresa_id}', 'LibroMayorCuentaGeneralFController@index')->name('index')->middleware('can:libro.mayor.cuenta.general.f.index');
    Route::get('/search/{empresa_id}', 'LibroMayorCuentaGeneralFController@search')->name('search')->middleware('can:libro.mayor.cuenta.general.f.index');
    Route::get('/excel/{empresa_id}', 'LibroMayorCuentaGeneralFController@excel')->name('excel')->middleware('can:libro.mayor.cuenta.general.f.index');
    Route::get('/pdf/{empresa_id}', 'LibroMayorCuentaGeneralFController@pdf')->name('pdf')->middleware('can:libro.mayor.cuenta.general.f.index');
});
