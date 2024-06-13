<?php

Route::prefix('libro-mayor-centro-f')->name('libro.mayor.centro.f.')->middleware(['auth:sanctum',config('jetstream.auth_session'),'verified',])->group(function () {
    Route::get('/', 'LibroMayorCentroFController@index')->name('index')->middleware('can:libro.mayor.centro.f.index');
    Route::get('/get_centros', 'LibroMayorCentroFController@getCentros')->name('get.centros')->middleware('can:libro.mayor.centro.f.index');
    Route::get('/get_subcentros', 'LibroMayorCentroFController@getSubCentros')->name('get.subcentros')->middleware('can:libro.mayor.centro.f.index');
    Route::get('/search', 'LibroMayorCentroFController@search')->name('search')->middleware('can:libro.mayor.centro.f.index');
    Route::get('/excel', 'LibroMayorCentroFController@excel')->name('excel')->middleware('can:libro.mayor.centro.f.index');
    Route::get('/pdf', 'LibroMayorCentroFController@pdf')->name('pdf')->middleware('can:libro.mayor.centro.f.index');
});
