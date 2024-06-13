<?php

Route::prefix('pi-clientes')->name('pi.clientes.')->middleware(['auth:sanctum',config('jetstream.auth_session'),'verified',])->group(function () {
    Route::get('/', 'PiClienteController@index')->name('index')->middleware('can:pi.clientes.index');
    Route::get('/search', 'PiClienteController@search')->name('search')->middleware('can:pi.clientes.index');
    Route::get('/create', 'PiClienteController@create')->name('create')->middleware('can:pi.clientes.create');
    Route::get('/store', 'PiClienteController@store')->name('store')->middleware('can:pi.clientes.create');
});
