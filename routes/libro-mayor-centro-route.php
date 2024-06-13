<?php

Route::prefix('libro-mayor-centro')->name('libro.mayor.centro.')->middleware(['auth:sanctum',config('jetstream.auth_session'),'verified',])->group(function () {
    Route::get('/', 'LibroMayorCentroController@index')->name('index')->middleware('can:libro.mayor.centro.index');
    Route::get('/get_centros', 'LibroMayorCentroController@getCentros')->name('get.centros')->middleware('can:libro.mayor.centro.index');
    Route::get('/get_subcentros', 'LibroMayorCentroController@getSubCentros')->name('get.subcentros')->middleware('can:libro.mayor.centro.index');
    Route::get('/search', 'LibroMayorCentroController@search')->name('search')->middleware('can:libro.mayor.centro.index');
    Route::get('/excel', 'LibroMayorCentroController@excel')->name('excel')->middleware('can:libro.mayor.centro.index');
    Route::get('/pdf', 'LibroMayorCentroController@pdf')->name('pdf')->middleware('can:libro.mayor.centro.index');
});
