<?php

Route::prefix('precio-productos')->name('precio.productos.')->middleware(['auth:sanctum',config('jetstream.auth_session'),'verified',])->group(function () {
    Route::get('/index-after', 'PrecioProductoController@indexAfter')->name('indexAfter')->middleware('can:precio.productos.index');
    Route::get('/{empresa_id}', 'PrecioProductoController@index')->name('index')->middleware('can:precio.productos.index');
    Route::get('/search-tipo/{empresa_id}', 'PrecioProductoController@searchTipo')->name('search.tipo')->middleware('can:precio.productos.index');
    Route::get('/search-tipo-base/{empresa_id}', 'PrecioProductoController@searchTipoBase')->name('search.tipo.base')->middleware('can:precio.productos.index');
    Route::post('/store', 'PrecioProductoController@store')->name('store')->middleware('can:precio.productos.create');
    Route::get('/show/{producto_id}', 'PrecioProductoController@show')->name('show')->middleware('can:precio.productos.show');
    Route::get('/get_subcategorias_precio_productos/{empresa_id}/{id}', 'PrecioProductoController@getSubCategorias')->name('get.subcategorias.precio.productos')->middleware('can:precio.productos.index');
});