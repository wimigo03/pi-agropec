<?php

Route::prefix('balance-apertura-f')->name('balance.apertura.f.')->middleware(['auth:sanctum',config('jetstream.auth_session'),'verified',])->group(function () {
    Route::get('/', 'BalanceAperturaFController@index')->name('index')->middleware('can:balance.apertura.f.index');
    //Route::get('create/{empresa_id}', 'BalanceAperturaFController@create')->name('create')->middleware('can:balance.apertura.f.create');
    //Route::post('/store', 'BalanceAperturaFController@store')->name('store')->middleware('can:balance.apertura.f.create');
});
