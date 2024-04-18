<?php

Route::prefix('balance-general-f')->name('balance.general.f.')->middleware(['auth:sanctum',config('jetstream.auth_session'),'verified',])->group(function () {
    Route::get('/index-after', 'BalanceGeneralFController@indexAfter')->name('indexAfter')->middleware('can:balance.general.f.index');
    Route::get('/{empresa_id}', 'BalanceGeneralFController@index')->name('index')->middleware('can:balance.general.f.index');
    Route::get('/search/{empresa_id}', 'BalanceGeneralFController@search')->name('search')->middleware('can:balance.general.f.index');
    Route::get('/excel/{empresa_id}', 'BalanceGeneralFController@excel')->name('excel')->middleware('can:balance.general.f.index');
    Route::get('/pdf/{empresa_id}', 'BalanceGeneralFController@pdf')->name('pdf')->middleware('can:balance.general.f.index');
});
