<?php

Route::prefix('configuracion')->name('configuracion.')->middleware(['auth:sanctum',config('jetstream.auth_session'),'verified',])->group(function () {
    Route::get('/index-after', 'ConfiguracionController@indexAfter')->name('indexAfter')->middleware('can:configuracion.index');
    Route::get('/{empresa_id}', 'ConfiguracionController@index')->name('index')->middleware('can:configuracion.index');
    Route::get('/search/{empresa_id}', 'ConfiguracionController@search')->name('search')->middleware('can:configuracion.index');
    Route::get('/create/{empresa_id}', 'ConfiguracionController@create')->name('create')->middleware('can:configuracion.create');
    Route::post('/store', 'ConfiguracionController@store')->name('store')->middleware('can:configuracion.create');
    Route::get('/show/{configuracion_id}', 'ConfiguracionController@show')->name('show')->middleware('can:configuracion.show');
    //Route::post('/inicio_mes_fiscal_create_store', 'ConfiguracionController@inicioMesFiscalCreateStore')->name('inicio.mes.fiscal.store')->middleware('can:configuracion.show');
});
