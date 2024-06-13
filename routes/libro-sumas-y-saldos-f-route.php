<?php

Route::prefix('libro-sumas-y-saldos-f')->name('libro.sumas.y.saldos.f.')->middleware(['auth:sanctum',config('jetstream.auth_session'),'verified',])->group(function () {
    Route::get('/', 'LibroSumasYSaldosFController@index')->name('index')->middleware('can:libro.sumas.y.saldos.f.index');
    Route::get('/search', 'LibroSumasYSaldosFController@search')->name('search')->middleware('can:libro.sumas.y.saldos.f.index');
    Route::get('/excel', 'LibroSumasYSaldosFController@excel')->name('excel')->middleware('can:libro.sumas.y.saldos.f.index');
    Route::get('/pdf', 'LibroSumasYSaldosFController@pdf')->name('pdf')->middleware('can:libro.sumas.y.saldos.f.index');
});
