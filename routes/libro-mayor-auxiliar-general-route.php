<?php

Route::prefix('libro-mayor-auxiliar-general')->name('libro.mayor.auxiliar.general.')->middleware(['auth:sanctum',config('jetstream.auth_session'),'verified',])->group(function () {
    //Route::get('/index-after', 'LibroMayorAuxiliarGeneralController@indexAfter')->name('indexAfter')->middleware('can:libro.mayor.auxiliar.general.index');
    Route::get('/', 'LibroMayorAuxiliarGeneralController@index')->name('index')->middleware('can:libro.mayor.auxiliar.general.index');
    Route::get('/get_plancuentasauxiliares', 'LibroMayorAuxiliarGeneralController@getPlanCuentasAuxiliares')->name('get.plancuentasauxiliares')->middleware('can:libro.mayor.auxiliar.general.index');
    Route::get('/search', 'LibroMayorAuxiliarGeneralController@search')->name('search')->middleware('can:libro.mayor.auxiliar.general.index');
    Route::get('/excel', 'LibroMayorAuxiliarGeneralController@excel')->name('excel')->middleware('can:libro.mayor.auxiliar.general.index');
    Route::get('/pdf', 'LibroMayorAuxiliarGeneralController@pdf')->name('pdf')->middleware('can:libro.mayor.auxiliar.general.index');
});
