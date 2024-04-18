<?php

Route::prefix('estado-resultado-f')->name('estado.resultado.f.')->middleware(['auth:sanctum',config('jetstream.auth_session'),'verified',])->group(function () {
    Route::get('/{empresa_id}', 'EstadoResultadoFController@index')->name('index')->middleware('can:estado.resultado.f.index');
    Route::get('/search/{empresa_id}', 'EstadoResultadoFController@search')->name('search')->middleware('can:estado.resultado.f.index');
    Route::get('/excel/{empresa_id}', 'EstadoResultadoFController@excel')->name('excel')->middleware('can:estado.resultado.f.index');
    Route::get('/pdf/{empresa_id}', 'EstadoResultadoFController@pdf')->name('pdf')->middleware('can:estado.resultado.f.index');
});