<?php

Route::prefix('libro-mayor-auxiliar-general-f')->name('libro.mayor.auxiliar.general.f.')->middleware(['auth:sanctum',config('jetstream.auth_session'),'verified',])->group(function () {
    Route::get('/index-after', 'LibroMayorAuxiliarGeneralFController@indexAfter')->name('indexAfter')->middleware('can:libro.mayor.auxiliar.general.f.index');
    Route::get('/{empresa_id}', 'LibroMayorAuxiliarGeneralFController@index')->name('index')->middleware('can:libro.mayor.auxiliar.general.f.index');
    Route::get('/search/{empresa_id}', 'LibroMayorAuxiliarGeneralFController@search')->name('search')->middleware('can:libro.mayor.auxiliar.general.f.index');
    Route::get('/excel/{empresa_id}', 'LibroMayorAuxiliarGeneralFController@excel')->name('excel')->middleware('can:libro.mayor.auxiliar.general.f.index');
    Route::get('/pdf/{empresa_id}', 'LibroMayorAuxiliarGeneralFController@pdf')->name('pdf')->middleware('can:libro.mayor.auxiliar.general.f.index');
});
