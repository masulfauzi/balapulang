<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Nasabah\Controllers\NasabahController;

Route::controller(NasabahController::class)->middleware(['web','auth'])->name('nasabah.')->group(function(){
	Route::get('/nasabah', 'index')->name('index');
	Route::get('/nasabah/data', 'data')->name('data.index');
	Route::get('/nasabah/create', 'create')->name('create');
	Route::post('/nasabah', 'store')->name('store');
	Route::get('/nasabah/{nasabah}', 'show')->name('show');
	Route::get('/nasabah/{nasabah}/edit', 'edit')->name('edit');
	Route::patch('/nasabah/{nasabah}', 'update')->name('update');
	Route::get('/nasabah/{nasabah}/delete', 'destroy')->name('destroy');
});