<?php

Route::prefix('comprobantes')->name('comprobante.')->middleware(['auth:sanctum',config('jetstream.auth_session'),'verified',])->group(function () {
    Route::get('/', 'ComprobanteController@index')->name('index')->middleware('can:comprobante.index');
    Route::get('/get_usuarios', 'ComprobanteController@getUsuarios')->name('get.usuarios')->middleware('can:comprobante.index');
    Route::get('/search', 'ComprobanteController@search')->name('search')->middleware('can:comprobante.index');
    Route::get('/excel', 'ComprobanteController@excel')->name('excel')->middleware('can:comprobante.excel');
    Route::get('/create', 'ComprobanteController@create')->name('create')->middleware('can:comprobante.create');
    Route::get('/get_centros', 'ComprobanteController@getCentros')->name('get.centros')->middleware('can:comprobante.create');
    Route::get('/get_plancuentas', 'ComprobanteController@getPlanCuentas')->name('get.plancuentas')->middleware('can:comprobante.create');
    Route::get('/get_plancuentasauxiliares', 'ComprobanteController@getPlanCuentasAuxiliares')->name('get.plancuentasauxiliares')->middleware('can:comprobante.create');
    Route::get('/get_subcentros', 'ComprobanteController@getSubCentros')->name('get.subcentros')->middleware('can:comprobante.create');
    Route::get('/tiene_auxiliar/{plan_cuenta_id}', 'ComprobanteController@tieneAuxiliar')->name('tiene_auxiliar')->middleware('can:comprobante.create');
    Route::post('/store', 'ComprobanteController@store')->name('store')->middleware('can:comprobante.create');
    Route::get('/show/{id}', 'ComprobanteController@show')->name('show')->middleware('can:comprobante.show');
    Route::get('/editar/{id}', 'ComprobanteController@editar')->name('editar')->middleware('can:comprobante.editar');
    Route::get('/eliminar_registro/{comprobante_id}', 'ComprobanteController@eliminarRegistro')->name('eliminar_registro')->middleware('can:comprobante.editar');
    Route::post('/update', 'ComprobanteController@update')->name('update')->middleware('can:comprobante.editar');
    Route::get('/aprobar/{comprobante_id}', 'ComprobanteController@aprobar')->name('aprobar')->middleware('can:comprobante.aprobar');
    Route::get('/anular/{comprobante_id}', 'ComprobanteController@anular')->name('anular')->middleware('can:comprobante.anular');
    Route::get('/pdf/{comprobante_id}', 'ComprobanteController@pdf')->name('pdf')->middleware('can:comprobante.pdf');
});
