<?php

Route::prefix('personal')->name('personal.')->middleware(['auth:sanctum',config('jetstream.auth_session'),'verified',])->group(function () {
    Route::get('/index-after', 'PersonalController@indexAfter')->name('indexAfter')->middleware('can:personal.index');
    Route::get('/{empresa_id}', 'PersonalController@index')->name('index')->middleware('can:personal.index');
    Route::get('/search/{empresa_id}', 'PersonalController@search')->name('search')->middleware('can:personal.index');
    Route::get('/create/{empresa_id}', 'PersonalController@create')->name('create')->middleware('can:personal.create');
    Route::post('/store', 'PersonalController@store')->name('store')->middleware('can:personal.create');
    Route::get('/editar/{id}', 'PersonalController@editar')->name('editar')->middleware('can:personal.modificar');
    Route::post('/update', 'PersonalController@update')->name('update')->middleware('can:personal.modificar');
    Route::get('/show/{id}', 'PersonalController@show')->name('show')->middleware('can:personal.show');
    Route::get('/retirar/{personal}/{tipo}', 'PersonalController@retirar')->name('retirar')->middleware('can:personal.retirar');
    Route::post('/destroy', 'PersonalController@destroy')->name('destroy')->middleware('can:personal.retirar');
    Route::get('/file_contrato/{id}', 'PersonalController@file_contrato')->name('file.contrato')->middleware('can:personal.cargar.contrato');
    Route::post('/file_contrato_store', 'PersonalController@file_contrato_store')->name('file.contrato.store')->middleware('can:personal.cargar.contrato');
});