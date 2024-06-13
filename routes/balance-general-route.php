<?php

Route::prefix('balance-general')->name('balance.general.')->middleware(['auth:sanctum',config('jetstream.auth_session'),'verified',])->group(function () {
    //Route::get('/index-after', 'BalanceGeneralController@indexAfter')->name('indexAfter')->middleware('can:balance.general.index');
    Route::get('/', 'BalanceGeneralController@index')->name('index')->middleware('can:balance.general.index');
    Route::get('/search', 'BalanceGeneralController@search')->name('search')->middleware('can:balance.general.index');
    Route::get('/excel', 'BalanceGeneralController@excel')->name('excel')->middleware('can:balance.general.index');
    Route::get('/pdf', 'BalanceGeneralController@pdf')->name('pdf')->middleware('can:balance.general.index');
});
