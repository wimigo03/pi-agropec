<?php

Route::prefix('horarios')->name('horarios.')->middleware(['auth:sanctum',config('jetstream.auth_session'),'verified',])->group(function () {
    Route::get('/detalle/{horario_id}', 'HorarioController@detalleHorario')->name('detalle');//->middleware('can:users.index');
    //Route::get('/', 'PersonalController@index')->name('index');//->middleware('can:users.index');
    //Route::get('/create', 'PersonalController@create')->name('create');//->middleware('can:users.index');
    //Route::post('/store', 'CargoController@store')->name('store');//->middleware('can:users.index');
    //Route::get('/editar/{id}', 'CargoController@editar')->name('editar');//->middleware('can:users.index');
    //Route::post('/update', 'CargoController@update')->name('update');//->middleware('can:users.index');
});