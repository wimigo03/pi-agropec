<?php

Route::prefix('balance-apertura')->name('balance.apertura.')->middleware(['auth:sanctum',config('jetstream.auth_session'),'verified',])->group(function () {
    //Route::get('/index-after', 'BalanceAperturaController@indexAfter')->name('indexAfter')->middleware('can:balance.apertura.index');
    Route::get('/', 'BalanceAperturaController@index')->name('index')->middleware('can:balance.apertura.index');
    Route::get('create', 'BalanceAperturaController@create')->name('create')->middleware('can:balance.apertura.create');
    Route::post('/store', 'BalanceAperturaController@store')->name('store')->middleware('can:balance.apertura.create');
});
