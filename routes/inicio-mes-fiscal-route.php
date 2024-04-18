<?php

Route::prefix('inicio-mes-fiscal')->name('inicio.mes.fiscal.')->middleware(['auth:sanctum',config('jetstream.auth_session'),'verified',])->group(function () {
    //Route::get('/index-after', 'ConfiguracionController@indexAfter')->name('indexAfter')->middleware('can:configuracion.index');
    Route::get('/{configuracion_id}', 'InicioMesFiscalController@index')->name('index')->middleware('can:configuracion.show');
    Route::get('/search', 'InicioMesFiscalController@search')->name('search')->middleware('can:configuracion.show');
    Route::get('/create/{configuracion_id}', 'InicioMesFiscalController@create')->name('create')->middleware('can:configuracion.show');
    Route::post('/store', 'InicioMesFiscalController@store')->name('store')->middleware('can:configuracion.show');
    //Route::get('/show/{configuracion_id}', 'ConfiguracionController@show')->name('show')->middleware('can:configuracion.show');
    //Route::post('/inicio_mes_fiscal_create_store', 'ConfiguracionController@inicioMesFiscalCreateStore')->name('inicio.mes.fiscal.store')->middleware('can:configuracion.show');
});
