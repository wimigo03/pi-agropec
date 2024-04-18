<?php

Route::prefix('sucursal')->name('sucursal.')->middleware(['auth:sanctum',config('jetstream.auth_session'),'verified',])->group(function () {
    Route::get('/index-after', 'SucursalController@indexAfter')->name('indexAfter')->middleware('can:sucursal.index');
    Route::get('/{empresa_id}', 'SucursalController@index')->name('index')->middleware('can:sucursal.index');
    Route::get('/search/{empresa_id}', 'SucursalController@search')->name('search')->middleware('can:sucursal.index');
    Route::get('/create/{empresa_id}', 'SucursalController@create')->name('create')->middleware('can:sucursal.create');
    Route::post('/store', 'SucursalController@store')->name('store')->middleware('can:sucursal.create');
    Route::get('/editar/{id}', 'SucursalController@editar')->name('editar')->middleware('can:sucursal.modificar');
    Route::post('/update', 'SucursalController@update')->name('update')->middleware('can:sucursal.modificar');
});