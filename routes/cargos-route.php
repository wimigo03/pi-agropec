<?php

Route::prefix('cargos')->name('cargos.')->middleware(['auth:sanctum',config('jetstream.auth_session'),'verified',])->group(function () {
    Route::get('/index-after', 'CargoController@indexAfter')->name('indexAfter')->middleware('can:cargos.index');
    Route::get('/{empresa_id}', 'CargoController@index')->name('index')->middleware('can:cargos.index');
    Route::get('/get_datos_cargo/{id}', 'CargoController@getDatosCargo')->name('get.datos.cargo')->middleware('can:cargos.index');
    Route::get('/get_datos_cargo_by_empresa/{id}', 'CargoController@getDatosCargoByEmpresa')->name('get.datos.cargo.by.empresa');
    Route::get('/create/{id}', 'CargoController@create')->name('create')->middleware('can:cargos.create');
    Route::post('/store', 'CargoController@store')->name('store')->middleware('can:cargos.create');
    Route::get('/editar/{id}', 'CargoController@editar')->name('editar')->middleware('can:cargos.modificar');
    Route::post('/update', 'CargoController@update')->name('update')->middleware('can:cargos.modificar');
    Route::get('/habilitar/{id}', 'CargoController@habilitar')->name('habilitar')->middleware('can:cargos.habilitar');
    Route::get('/deshabilitar/{id}', 'CargoController@deshabilitar')->name('deshabilitar')->middleware('can:cargos.habilitar');
});