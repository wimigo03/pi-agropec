<?php

Route::prefix('estado-resultado')->name('estado.resultado.')->middleware(['auth:sanctum',config('jetstream.auth_session'),'verified',])->group(function () {
    Route::get('/', 'EstadoResultadoController@index')->name('index')->middleware('can:estado.resultado.index');
    Route::get('/search', 'EstadoResultadoController@search')->name('search')->middleware('can:estado.resultado.index');
    Route::get('/excel', 'EstadoResultadoController@excel')->name('excel')->middleware('can:estado.resultado.index');
    Route::get('/pdf', 'EstadoResultadoController@pdf')->name('pdf')->middleware('can:estado.resultado.index');
});
