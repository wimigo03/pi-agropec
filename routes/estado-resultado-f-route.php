<?php

Route::prefix('estado-resultado-f')->name('estado.resultado.f.')->middleware(['auth:sanctum',config('jetstream.auth_session'),'verified',])->group(function () {
    Route::get('/', 'EstadoResultadoFController@index')->name('index')->middleware('can:estado.resultado.f.index');
    Route::get('/search', 'EstadoResultadoFController@search')->name('search')->middleware('can:estado.resultado.f.index');
    Route::get('/excel', 'EstadoResultadoFController@excel')->name('excel')->middleware('can:estado.resultado.f.index');
    Route::get('/pdf', 'EstadoResultadoFController@pdf')->name('pdf')->middleware('can:estado.resultado.f.index');
});
