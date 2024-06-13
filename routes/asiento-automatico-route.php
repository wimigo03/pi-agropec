<?php

Route::prefix('asiento-automatico')->name('asiento.automatico.')->middleware(['auth:sanctum',config('jetstream.auth_session'),'verified',])->group(function () {
    //Route::get('/index-after', 'AsientoAutomaticoController@indexAfter')->name('indexAfter')->middleware('can:asiento.automatico.index');
    Route::get('/', 'AsientoAutomaticoController@index')->name('index')->middleware('can:asiento.automatico.index');
    Route::get('/search', 'AsientoAutomaticoController@search')->name('search')->middleware('can:asiento.automatico.index');
    Route::get('/create', 'AsientoAutomaticoController@create')->name('create')->middleware('can:asiento.automatico.create');
    Route::get('/get_plancuentas', 'AsientoAutomaticoController@getPlanCuentas')->name('get.plancuentas')->middleware('can:asiento.automatico.create');
    Route::post('/store', 'AsientoAutomaticoController@store')->name('store')->middleware('can:asiento.automatico.create');
    Route::get('/editar/{asiento_automatico_id}', 'AsientoAutomaticoController@editar')->name('editar')->middleware('can:asiento.automatico.editar');
    Route::get('/eliminar_registro/{asiento_automatico_detalle_id}', 'AsientoAutomaticoController@eliminarRegistro')->name('eliminar_registro')->middleware('can:asiento.automatico.editar');
    Route::post('/update', 'AsientoAutomaticoController@update')->name('update')->middleware('can:asiento.automatico.editar');
    Route::get('/habilitar/{asiento_automatico_id}', 'AsientoAutomaticoController@habilitar')->name('habilitar')->middleware('can:asiento.automatico.habilitar');
    Route::get('/deshabilitar/{asiento_automatico_id}', 'AsientoAutomaticoController@deshabilitar')->name('deshabilitar')->middleware('can:asiento.automatico.habilitar');
    Route::get('/show/{asiento_automatico_id}', 'AsientoAutomaticoController@show')->name('show')->middleware('can:asiento.automatico.show');
});
