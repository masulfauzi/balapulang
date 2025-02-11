<?php

use Illuminate\Support\Facades\Route;
use App\Modules\StatusKunjungan\Controllers\StatusKunjunganController;

Route::controller(StatusKunjunganController::class)->middleware(['web','auth'])->name('statuskunjungan.')->group(function(){
	Route::get('/statuskunjungan', 'index')->name('index');
	Route::get('/statuskunjungan/data', 'data')->name('data.index');
	Route::get('/statuskunjungan/create', 'create')->name('create');
	Route::post('/statuskunjungan', 'store')->name('store');
	Route::get('/statuskunjungan/{statuskunjungan}', 'show')->name('show');
	Route::get('/statuskunjungan/{statuskunjungan}/edit', 'edit')->name('edit');
	Route::patch('/statuskunjungan/{statuskunjungan}', 'update')->name('update');
	Route::get('/statuskunjungan/{statuskunjungan}/delete', 'destroy')->name('destroy');
});