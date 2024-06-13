<?php

Route::prefix('libro-sumas-y-saldos')->name('libro.sumas.y.saldos.')->middleware(['auth:sanctum',config('jetstream.auth_session'),'verified',])->group(function () {
    Route::get('/', 'LibroSumasYSaldosController@index')->name('index')->middleware('can:libro.sumas.y.saldos.index');
    Route::get('/search', 'LibroSumasYSaldosController@search')->name('search')->middleware('can:libro.sumas.y.saldos.index');
    Route::get('/excel', 'LibroSumasYSaldosController@excel')->name('excel')->middleware('can:libro.sumas.y.saldos.index');
    Route::get('/pdf', 'LibroSumasYSaldosController@pdf')->name('pdf')->middleware('can:libro.sumas.y.saldos.index');
});
