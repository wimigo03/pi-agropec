<?php

Route::prefix('plan-cuentas')->name('plan_cuentas.')->middleware(['auth:sanctum',config('jetstream.auth_session'),'verified',])->group(function () {
    Route::get('/index-after', 'PlanCuentaController@indexAfter')->name('indexAfter')->middleware('can:plan.cuentas.index');
    Route::get('/{empresa_id}/{status}', 'PlanCuentaController@index')->name('index')->middleware('can:plan.cuentas.index');
    Route::get('/get_datos_plan_cuenta/{empresa_id}/{id}', 'PlanCuentaController@getDatosPlanCuenta')->name('get.datos');
    Route::get('/create/{empresa_id}', 'PlanCuentaController@create')->name('create')->middleware('can:plan.cuentas.create');
    Route::post('/store', 'PlanCuentaController@store')->name('store')->middleware('can:plan.cuentas.create');
    Route::get('/create-sub/{empresa_id}/{id}', 'PlanCuentaController@create_sub')->name('create_sub')->middleware('can:plan.cuentas.create');
    Route::post('/store-sub', 'PlanCuentaController@store_sub')->name('store_sub')->middleware('can:plan.cuentas.create');
    Route::get('/habilitar/{empresa_id}/{id}', 'PlanCuentaController@habilitar')->name('habilitar')->middleware('can:plan.cuentas.habilitar');
    Route::get('/deshabilitar/{empresa_id}/{id}', 'PlanCuentaController@deshabilitar')->name('deshabilitar')->middleware('can:plan.cuentas.habilitar');
    Route::get('/editar/{empresa_id}/{id}', 'PlanCuentaController@editar')->name('editar')->middleware('can:plan.cuentas.editar');
    Route::post('/update', 'PlanCuentaController@update')->name('update')->middleware('can:plan.cuentas.editar');
});
