<?php

Route::prefix('sub-centros')->name('sub.centros.')->middleware(['auth:sanctum',config('jetstream.auth_session'),'verified',])->group(function () {
    Route::get('/create', 'SubCentroController@create')->name('create')->middleware('can:sub.centros.create');
    Route::get('/get_centros', 'SubCentroController@getCentros')->name('get.centros')->middleware('can:sub.centros.create');
    Route::post('/store', 'SubCentroController@store')->name('store')->middleware('can:sub.centros.create');
    Route::get('/habilitar/{id}', 'SubCentroController@habilitar')->name('habilitar')->middleware('can:sub.centros.habilitar');
    Route::get('/deshabilitar/{id}', 'SubCentroController@deshabilitar')->name('deshabilitar')->middleware('can:sub.centros.habilitar');
});
