<?php

Route::prefix('categorias')->name('categorias.')->middleware(['auth:sanctum',config('jetstream.auth_session'),'verified',])->group(function () {
    Route::get('/index-after', 'CategoriaController@indexAfter')->name('indexAfter')->middleware('can:categorias.index');
    Route::get('/{empresa_id}/{status_platos}/{status_insumos}', 'CategoriaController@index')->name('index')->middleware('can:categorias.index');
    Route::get('/search', 'CategoriaController@search')->name('search')->middleware('can:categorias.index');
    Route::get('/get_datos_categoria/{id}', 'CategoriaController@getDatosCategoria')->name('get.datos.categoria');
    Route::get('/create_platos/{id}', 'CategoriaController@create_platos')->name('create_platos')->middleware('can:categorias.create.platos');
    Route::post('/store', 'CategoriaController@store')->name('store')->middleware('can:categorias.create.platos');
    Route::get('/editar/{id}', 'CategoriaController@editar')->name('editar')->middleware('can:categorias.modificar');
    Route::post('/update', 'CategoriaController@update')->name('update')->middleware('can:categorias.modificar');
    Route::get('/create_platos_master/{id}', 'CategoriaController@create_platos_master')->name('create_platos_master')->middleware('can:categorias.create.platos.master');
    Route::post('/store/master', 'CategoriaController@store_master')->name('store.master')->middleware('can:categorias.create.platos.master');
    Route::get('/habilitar/{id}', 'CategoriaController@habilitar')->name('habilitar')->middleware('can:categorias.habilitar');
    Route::get('/deshabilitar/{id}', 'CategoriaController@deshabilitar')->name('deshabilitar')->middleware('can:categorias.habilitar');
    Route::get('/get_datos_categoria_insumos/{id}', 'CategoriaController@getDatosCategoriaInsumos')->name('get.datos.categoria.insumos');
    Route::get('/create_insumos_master/{id}', 'CategoriaController@create_insumos_master')->name('create_insumos_master')->middleware('can:categorias.create.insumos.master');
    Route::get('/create_insumos/{id}', 'CategoriaController@create_insumos')->name('create_insumos')->middleware('can:categorias.create.insumos');
    Route::get('/get_datos_subcategorias', 'CategoriaController@getDatosSubCategorias')->name('get.datos.subcategorias');
});