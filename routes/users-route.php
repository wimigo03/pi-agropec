<?php

Route::prefix('users')->name('users.')->middleware(['auth:sanctum',config('jetstream.auth_session'),'verified',])->group(function () {
    Route::get('/index-after', 'UserController@indexAfter')->name('indexAfter')->middleware('can:user.index');
    Route::get('/{empresa_id}', 'UserController@index')->name('index')->middleware('can:users.index');
    Route::get('/search/{empresa_id}', 'UserController@search')->name('search')->middleware('can:users.index');
    Route::get('/editar/{id}', 'UserController@editar')->name('editar')->middleware('can:users.editar');
    Route::post('/update', 'UserController@update')->name('update')->middleware('can:users.editar');
    Route::get('/habilitar/{id}', 'UserController@habilitar')->name('habilitar')->middleware('can:users.habilitar');
    Route::get('/deshabilitar/{id}', 'UserController@deshabilitar')->name('deshabilitar')->middleware('can:users.habilitar');
    Route::get('/asignar/{id}', 'UserController@asignar')->name('asignar')->middleware('can:users.asignar');
    Route::post('/asignacion', 'UserController@asignacion')->name('asignacion')->middleware('can:users.asignar');
});
