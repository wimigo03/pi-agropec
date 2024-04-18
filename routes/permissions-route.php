<?php

Route::prefix('permissions')->name('permissions.')->middleware(['auth:sanctum',config('jetstream.auth_session'),'verified',])->group(function () {
    Route::get('/index-after', 'PermissionController@indexAfter')->name('indexAfter')->middleware('can:permissions.index');
    Route::get('/{empresa_id}', 'PermissionController@index')->name('index')->middleware('can:permissions.index');
    Route::get('/search/{empresa_id}', 'PermissionController@search')->name('search')->middleware('can:permissions.index');
    Route::get('/create/{empresa_id}', 'PermissionController@create')->name('create')->middleware('can:permissions.create');
    Route::post('/store', 'PermissionController@store')->name('store')->middleware('can:permissions.create');
    Route::get('/editar/{permission_id}', 'PermissionController@editar')->name('editar')->middleware('can:permissions.editar');
    Route::post('/update', 'PermissionController@update')->name('update')->middleware('can:permissions.editar');
});
