<?php

Route::prefix('tipo-precios')->name('tipo.precios.')->middleware(['auth:sanctum',config('jetstream.auth_session'),'verified',])->group(function () {
    //Route::get('/index-after', 'TipoPrecioController@indexAfter')->name('indexAfter')->middleware('can:tipo.precios.index');
    Route::get('/{empresa_id}', 'TipoPrecioController@index')->name('index')->middleware('can:tipo.precios.index');
    Route::get('/search/{empresa_id}', 'TipoPrecioController@search')->name('search')->middleware('can:tipo.precios.index');
    Route::get('/create/{empresa_id}', 'TipoPrecioController@create')->name('create')->middleware('can:tipo.precios.create');
    Route::post('/store', 'TipoPrecioController@store')->name('store')->middleware('can:tipo.precios.create');
    //Route::get('/editar/{id}', 'TipoPrecioController@editar')->name('editar')->middleware('can:tipo.precios.editar');
    //Route::post('/update', 'TipoPrecioController@update')->name('update')->middleware('can:tipo.precios.editar');
    Route::get('/habilitar/{id}', 'TipoPrecioController@habilitar')->name('habilitar')->middleware('can:tipo.precios.habilitar');
    Route::get('/deshabilitar/{id}', 'TipoPrecioController@deshabilitar')->name('deshabilitar')->middleware('can:tipo.precios.habilitar');
});