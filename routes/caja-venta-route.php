<?php

Route::prefix('caja-venta')->name('caja.venta.')->middleware(['auth:sanctum',config('jetstream.auth_session'),'verified',])->group(function () {
    Route::get('/index-after', 'CajaVentaController@indexAfter')->name('indexAfter')->middleware('can:caja.venta.index');
    Route::get('/{empresa_id}', 'CajaVentaController@index')->name('index')->middleware('can:caja.venta.index');
    Route::get('/search/{empresa_id}', 'CajaVentaController@search')->name('search')->middleware('can:caja.venta.index');
    Route::get('/create/{empresa_id}', 'CajaVentaController@create')->name('create')->middleware('can:caja.venta.create');
    Route::post('/store', 'CajaVentaController@store')->name('store')->middleware('can:caja.venta.create');
    Route::get('/show/{caja_venta_id}', 'CajaVentaController@show')->name('show')->middleware('can:caja.venta.show');
    Route::get('/aprobar/{caja_venta_id}', 'CajaVentaController@aprobar')->name('aprobar')->middleware('can:caja.venta.aprobar');
    Route::get('/rechazar/{caja_venta_id}', 'CajaVentaController@rechazar')->name('rechazar')->middleware('can:caja.venta.aprobar');
});
