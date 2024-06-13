<?php

Route::prefix('balance-general-f')->name('balance.general.f.')->middleware(['auth:sanctum',config('jetstream.auth_session'),'verified',])->group(function () {
    //Route::get('/index-after', 'BalanceGeneralFController@indexAfter')->name('indexAfter')->middleware('can:balance.general.f.index');
    Route::get('/', 'BalanceGeneralFController@index')->name('index')->middleware('can:balance.general.f.index');
    Route::get('/search', 'BalanceGeneralFController@search')->name('search')->middleware('can:balance.general.f.index');
    Route::get('/excel', 'BalanceGeneralFController@excel')->name('excel')->middleware('can:balance.general.f.index');
    Route::get('/pdf', 'BalanceGeneralFController@pdf')->name('pdf')->middleware('can:balance.general.f.index');
});
