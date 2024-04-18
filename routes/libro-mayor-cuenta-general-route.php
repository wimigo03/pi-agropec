<?php

Route::prefix('libro-mayor-cuenta-general')->name('libro.mayor.cuenta.general.')->middleware(['auth:sanctum',config('jetstream.auth_session'),'verified',])->group(function () {
    Route::get('/index-after', 'LibroMayorCuentaGeneralController@indexAfter')->name('indexAfter')->middleware('can:libro.mayor.cuenta.general.index');
    Route::get('/{empresa_id}', 'LibroMayorCuentaGeneralController@index')->name('index')->middleware('can:libro.mayor.cuenta.general.index');
    Route::get('/search/{empresa_id}', 'LibroMayorCuentaGeneralController@search')->name('search')->middleware('can:libro.mayor.cuenta.general.index');
    Route::get('/excel/{empresa_id}', 'LibroMayorCuentaGeneralController@excel')->name('excel')->middleware('can:libro.mayor.cuenta.general.index');
    Route::get('/pdf/{empresa_id}', 'LibroMayorCuentaGeneralController@pdf')->name('pdf')->middleware('can:libro.mayor.cuenta.general.index');
});
