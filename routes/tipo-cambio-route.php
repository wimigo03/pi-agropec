<?php

Route::prefix('tipo-cambio')->name('tipo.cambio.')->middleware(['auth:sanctum',config('jetstream.auth_session'),'verified',])->group(function () {
    Route::get('/index-after', 'TipoCambioController@indexAfter')->name('indexAfter')->middleware('can:tipo.cambio.index');
    Route::get('/{empresa_id}', 'TipoCambioController@index')->name('index')->middleware('can:tipo.cambio.index');
    Route::get('/search/{empresa_id}', 'TipoCambioController@search')->name('search')->middleware('can:tipo.cambio.index');
    Route::get('/create/{empresa_id}', 'TipoCambioController@create')->name('create')->middleware('can:tipo.cambio.create');
    Route::post('/store', 'TipoCambioController@store')->name('store')->middleware('can:tipo.cambio.create');
    Route::get('/editar/{id}', 'TipoCambioController@editar')->name('editar')->middleware('can:tipo.cambio.editar');
    Route::post('/update', 'TipoCambioController@update')->name('update')->middleware('can:tipo.cambio.editar');
});