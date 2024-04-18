<?php

Route::prefix('comprobantef')->name('comprobantef.')->middleware(['auth:sanctum',config('jetstream.auth_session'),'verified',])->group(function () {
    //Route::get('/index-after', 'ComprobanteController@indexAfter')->name('indexAfter')->middleware('can:comprobante.index');
    Route::get('/{empresa_id}', 'ComprobanteFController@index')->name('index')->middleware('can:comprobantef.index');
    Route::get('/search/{empresa_id}', 'ComprobanteFController@search')->name('search')->middleware('can:comprobantef.index');
    Route::get('/create/{empresa_id}', 'ComprobanteFController@create')->name('create')->middleware('can:comprobantef.create');
    Route::get('/tiene_auxiliar/{plan_cuenta_id}', 'ComprobanteFController@tieneAuxiliar')->name('tiene_auxiliar')->middleware('can:comprobantef.create');
    Route::post('/store', 'ComprobanteFController@store')->name('store')->middleware('can:comprobantef.create');
    Route::get('/show/{id}', 'ComprobanteFController@show')->name('show')->middleware('can:comprobantef.show');
    Route::get('/editar/{id}', 'ComprobanteFController@editar')->name('editar')->middleware('can:comprobantef.editar');
    Route::get('/eliminar_registro/{comprobante_id}', 'ComprobanteFController@eliminarRegistro')->name('eliminar_registro')->middleware('can:comprobantef.editar');
    Route::post('/update', 'ComprobanteFController@update')->name('update')->middleware('can:comprobantef.editar');
    Route::get('/aprobar/{comprobante_id}', 'ComprobanteFController@aprobar')->name('aprobar')->middleware('can:comprobantef.aprobar');
    Route::get('/anular/{comprobante_id}', 'ComprobanteFController@anular')->name('anular')->middleware('can:comprobantef.anular');
    Route::get('/pdf/{comprobante_id}', 'ComprobanteFController@pdf')->name('pdf')->middleware('can:comprobantef.pdf');
});