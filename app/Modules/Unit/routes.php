<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Unit\Controllers\UnitController;

Route::controller(UnitController::class)->middleware(['web','auth'])->name('unit.')->group(function(){
	Route::get('/unit', 'index')->name('index');
	Route::get('/unit/data', 'data')->name('data.index');
	Route::get('/unit/create', 'create')->name('create');
	Route::post('/unit', 'store')->name('store');
	Route::get('/unit/{unit}', 'show')->name('show');
	Route::get('/unit/{unit}/edit', 'edit')->name('edit');
	Route::patch('/unit/{unit}', 'update')->name('update');
	Route::get('/unit/{unit}/delete', 'destroy')->name('destroy');
});