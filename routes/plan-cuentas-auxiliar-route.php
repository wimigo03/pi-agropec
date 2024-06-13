<?php

Route::prefix('plan-cuentas-auxiliar')->name('plan_cuentas.auxiliar.')->middleware(['auth:sanctum',config('jetstream.auth_session'),'verified',])->group(function () {
    //Route::get('/index-after', 'PlanCuentaAuxiliarController@indexAfter')->name('indexAfter')->middleware('can:plan.cuentas.auxiliar.index');
    Route::get('/', 'PlanCuentaAuxiliarController@index')->name('index')->middleware('can:plan.cuentas.auxiliar.index');
    Route::get('/search', 'PlanCuentaAuxiliarController@search')->name('search')->middleware('can:plan.cuentas.auxiliar.index');
    Route::get('/create', 'PlanCuentaAuxiliarController@create')->name('create')->middleware('can:plan.cuentas.auxiliar.create');
    Route::post('/store', 'PlanCuentaAuxiliarController@store')->name('store')->middleware('can:plan.cuentas.auxiliar.create');
    Route::get('/habilitar/{id}', 'PlanCuentaAuxiliarController@habilitar')->name('habilitar')->middleware('can:plan.cuentas.auxiliar.habilitar');
    Route::get('/deshabilitar/{id}', 'PlanCuentaAuxiliarController@deshabilitar')->name('deshabilitar')->middleware('can:plan.cuentas.auxiliar.habilitar');
    Route::get('/editar/{id}', 'PlanCuentaAuxiliarController@editar')->name('editar')->middleware('can:plan.cuentas.auxiliar.editar');
    Route::post('/update', 'PlanCuentaAuxiliarController@update')->name('update')->middleware('can:plan.cuentas.auxiliar.editar');
});
