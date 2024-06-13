<?php

Route::prefix('centros')->name('centros.')->middleware(['auth:sanctum',config('jetstream.auth_session'),'verified',])->group(function () {
    Route::get('/', 'CentroController@index')->name('index')->middleware('can:centros.index');
    Route::get('/search', 'CentroController@search')->name('search')->middleware('can:centros.index');
    Route::get('/create', 'CentroController@create')->name('create')->middleware('can:centros.create');
    Route::post('/store', 'CentroController@store')->name('store')->middleware('can:centros.create');
});
