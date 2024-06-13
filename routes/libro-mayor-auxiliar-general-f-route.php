<?php

Route::prefix('libro-mayor-auxiliar-general-f')->name('libro.mayor.auxiliar.general.f.')->middleware(['auth:sanctum',config('jetstream.auth_session'),'verified',])->group(function () {
    //Route::get('/index-after', 'LibroMayorAuxiliarGeneralFController@indexAfter')->name('indexAfter')->middleware('can:libro.mayor.auxiliar.general.f.index');
    Route::get('/', 'LibroMayorAuxiliarGeneralFController@index')->name('index')->middleware('can:libro.mayor.auxiliar.general.f.index');
    Route::get('/get_plancuentasauxiliares', 'LibroMayorAuxiliarGeneralFController@getPlanCuentasAuxiliares')->name('get.plancuentasauxiliares')->middleware('can:libro.mayor.auxiliar.general.f.index');
    Route::get('/search', 'LibroMayorAuxiliarGeneralFController@search')->name('search')->middleware('can:libro.mayor.auxiliar.general.f.index');
    Route::get('/excel', 'LibroMayorAuxiliarGeneralFController@excel')->name('excel')->middleware('can:libro.mayor.auxiliar.general.f.index');
    Route::get('/pdf', 'LibroMayorAuxiliarGeneralFController@pdf')->name('pdf')->middleware('can:libro.mayor.auxiliar.general.f.index');
});
