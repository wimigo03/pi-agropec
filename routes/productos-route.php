<?php

Route::prefix('productos')->name('productos.')->middleware(['auth:sanctum',config('jetstream.auth_session'),'verified',])->group(function () {
    Route::get('/index-after', 'ProductoController@indexAfter')->name('indexAfter')->middleware('can:productos.index');
    Route::get('/{empresa_id}', 'ProductoController@index')->name('index')->middleware('can:productos.index');
    Route::get('/search/{empresa_id}', 'ProductoController@search')->name('search')->middleware('can:productos.index');
    Route::get('/create/{empresa_id}', 'ProductoController@create')->name('create')->middleware('can:productos.create');
    Route::post('/store', 'ProductoController@store')->name('store')->middleware('can:productos.create');
    Route::get('/get_codigo_master/{categoria_master_id}', 'ProductoController@getCodigoMaster')->name('get.codigo.master');
    Route::get('/get_codigo/{categoria_id}', 'ProductoController@getCodigo')->name('get.codigo');
    Route::get('/get_codigo_sin/{categoria_master_id}', 'ProductoController@getCodigoSinCategoria')->name('get.codigo.sin.categoria');
    Route::get('/editar/{id}', 'ProductoController@editar')->name('editar')->middleware('can:productos.modificar');
    Route::post('/update', 'ProductoController@update')->name('update')->middleware('can:productos.modificar');
    Route::get('/show/{id}', 'ProductoController@show')->name('show')->middleware('can:productos.show');
    Route::get('/pdf/{id}', 'ProductoController@pdf')->name('pdf')->middleware('can:productos.pdf');
    Route::get('/habilitar/{id}', 'ProductoController@habilitar')->name('habilitar')->middleware('can:productos.habilitar');
    Route::get('/deshabilitar/{id}', 'ProductoController@deshabilitar')->name('deshabilitar')->middleware('can:productos.habilitar');
});