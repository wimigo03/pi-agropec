<?php

Route::prefix('libro-mayor-auxiliar-general')->name('libro.mayor.auxiliar.general.')->middleware(['auth:sanctum',config('jetstream.auth_session'),'verified',])->group(function () {
    Route::get('/index-after', 'LibroMayorAuxiliarGeneralController@indexAfter')->name('indexAfter')->middleware('can:libro.mayor.auxiliar.general.index');
    Route::get('/{empresa_id}', 'LibroMayorAuxiliarGeneralController@index')->name('index')->middleware('can:libro.mayor.auxiliar.general.index');
    Route::get('/search/{empresa_id}', 'LibroMayorAuxiliarGeneralController@search')->name('search')->middleware('can:libro.mayor.auxiliar.general.index');
    Route::get('/excel/{empresa_id}', 'LibroMayorAuxiliarGeneralController@excel')->name('excel')->middleware('can:libro.mayor.auxiliar.general.index');
    Route::get('/pdf/{empresa_id}', 'LibroMayorAuxiliarGeneralController@pdf')->name('pdf')->middleware('can:libro.mayor.auxiliar.general.index');
});
