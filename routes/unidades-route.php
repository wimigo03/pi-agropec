<?php

Route::prefix('unidades')->name('unidades.')->middleware(['auth:sanctum',config('jetstream.auth_session'),'verified',])->group(function () {
    Route::get('/index-after', 'UnidadController@indexAfter')->name('indexAfter')->middleware('can:unidades.index');
    Route::get('/{empresa_id}', 'UnidadController@index')->name('index')->middleware('can:unidades.index');
    Route::get('/search/{empresa_id}', 'UnidadController@search')->name('search')->middleware('can:unidades.index');
    Route::get('/create/{empresa_id}', 'UnidadController@create')->name('create')->middleware('can:unidades.create');
    Route::post('/store', 'UnidadController@store')->name('store')->middleware('can:unidades.create');
});