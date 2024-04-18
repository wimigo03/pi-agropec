<?php

Route::prefix('roles')->name('roles.')->middleware(['auth:sanctum',config('jetstream.auth_session'),'verified',])->group(function () {
    Route::get('/index-after', 'RoleController@indexAfter')->name('indexAfter')->middleware('can:roles.index');
    Route::get('/{empresa_id}', 'RoleController@index')->name('index')->middleware('can:roles.index');
    Route::get('/search/{empresa_id}', 'RoleController@search')->name('search')->middleware('can:roles.index');
    Route::get('/get_datos_by_empresa', 'RoleController@getDatosByEmpresa')->name('get.datos.by.empresa');
    Route::get('/create/{empresa_id}', 'RoleController@create')->name('create')->middleware('can:roles.create');
    Route::post('/store', 'RoleController@store')->name('store')->middleware('can:roles.create');
    Route::get('/editar_index/{id}', 'RoleController@editar_index')->name('editar_index')->middleware('can:roles.editar');
    Route::get('/editar/{empresa_id}', 'RoleController@editar')->name('editar')->middleware('can:roles.editar');
    Route::post('/update', 'RoleController@update')->name('update')->middleware('can:roles.editar');
});
