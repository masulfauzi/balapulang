<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Dinas\Controllers\DinasController;

Route::controller(DinasController::class)->middleware(['web','auth'])->name('dinas.')->group(function(){
	Route::get('/dinas', 'index')->name('index');
	Route::get('/dinas/data', 'data')->name('data.index');
	Route::get('/dinas/create', 'create')->name('create');
	Route::post('/dinas', 'store')->name('store');
	Route::get('/dinas/{dinas}', 'show')->name('show');
	Route::get('/dinas/{dinas}/edit', 'edit')->name('edit');
	Route::patch('/dinas/{dinas}', 'update')->name('update');
	Route::get('/dinas/{dinas}/delete', 'destroy')->name('destroy');
});