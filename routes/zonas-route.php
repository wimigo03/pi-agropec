<?php

Route::prefix('zonas')->name('zonas.')->middleware(['auth:sanctum',config('jetstream.auth_session'),'verified',])->group(function () {
    Route::get('/index-after', 'ZonaController@indexAfter')->name('indexAfter')->middleware('can:zonas.index');
    Route::get('/index-empresa/{empresa_id}', 'ZonaController@indexEmpresa')->name('indexEmpresa')->middleware('can:zonas.index');
    Route::get('/index/{sucursal_id}', 'ZonaController@index')->name('index')->middleware('can:zonas.index');
    Route::get('/search/{sucursal_id}', 'ZonaController@search')->name('search')->middleware('can:zonas.index');
    Route::get('/searchByEmpresa/{empresa_id}', 'ZonaController@searchByEmpresa')->name('searchByEmpresa')->middleware('can:zonas.index');
    Route::get('/create/{sucursal_id}', 'ZonaController@create')->name('create')->middleware('can:zonas.create');
    Route::get('/create-by-sucursal/{empresa_id}', 'ZonaController@createBySucursal')->name('createBySucursal')->middleware('can:zonas.create');
    Route::post('/store', 'ZonaController@store')->name('store')->middleware('can:zonas.create');
    Route::post('/storeBySucursal', 'ZonaController@storeBySucursal')->name('storeBySucursal')->middleware('can:zonas.create');
    Route::get('/editar/{id}', 'ZonaController@editar')->name('editar')->middleware('can:zonas.editar');
    Route::post('/update', 'ZonaController@update')->name('update')->middleware('can:zonas.editar');
    Route::get('/habilitar/{id}', 'ZonaController@habilitar')->name('habilitar')->middleware('can:zonas.habilitar');
    Route::get('/deshabilitar/{id}', 'ZonaController@deshabilitar')->name('deshabilitar')->middleware('can:zonas.habilitar');
    Route::get('/habilitarBySucursal/{id}', 'ZonaController@habilitarBySucursal')->name('habilitarBySucursal')->middleware('can:zonas.habilitar');
    Route::get('/deshabilitarBySucursal/{id}', 'ZonaController@deshabilitarBySucursal')->name('deshabilitarBySucursal')->middleware('can:zonas.habilitar');
    Route::get('/get_datos_by_sucursal', 'ZonaController@getDatosBySucursal')->name('get.datos.by.sucursal');
});