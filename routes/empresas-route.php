<?php

Route::prefix('empresas')->name('empresas.')->middleware(['auth:sanctum',config('jetstream.auth_session'),'verified',])->group(function () {
    Route::get('/{cliente_id}', 'EmpresaController@index')->name('index')->middleware('can:empresas.index');
    Route::get('/search', 'EmpresaController@search')->name('search')->middleware('can:empresas.index');
    Route::get('/create/{cliente_id}', 'EmpresaController@create')->name('create')->middleware('can:empresas.create');
    Route::post('/store', 'EmpresaController@store')->name('store')->middleware('can:empresas.create');
    Route::get('/editar/{empresa_id}', 'EmpresaController@editar')->name('editar')->middleware('can:empresas.editar');
    Route::post('/update', 'EmpresaController@update')->name('update')->middleware('can:empresas.editar');
    Route::get('/habilitar/{empresa_modulo_id}', 'EmpresaController@modulo_habilitar')->name('modulo.habilitar')->middleware('can:empresas.editar');
    Route::get('/deshabilitar/{empresa_modulo_id}', 'EmpresaController@modulo_deshabilitar')->name('modulo.deshabilitar')->middleware('can:empresas.editar');
});
