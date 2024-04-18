<?php

Route::prefix('clientes')->name('clientes.')->middleware(['auth:sanctum',config('jetstream.auth_session'),'verified',])->group(function () {
    Route::get('/', 'ClienteController@index')->name('index')->middleware('can:clientes.index');
    Route::get('/create', 'ClienteController@create')->name('create')->middleware('can:clientes.create');
    Route::get('/store', 'ClienteController@store')->name('store')->middleware('can:clientes.create');
});