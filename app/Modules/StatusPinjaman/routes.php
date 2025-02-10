<?php

use Illuminate\Support\Facades\Route;
use App\Modules\StatusPinjaman\Controllers\StatusPinjamanController;

Route::controller(StatusPinjamanController::class)->middleware(['web','auth'])->name('statuspinjaman.')->group(function(){
	Route::get('/statuspinjaman', 'index')->name('index');
	Route::get('/statuspinjaman/data', 'data')->name('data.index');
	Route::get('/statuspinjaman/create', 'create')->name('create');
	Route::post('/statuspinjaman', 'store')->name('store');
	Route::get('/statuspinjaman/{statuspinjaman}', 'show')->name('show');
	Route::get('/statuspinjaman/{statuspinjaman}/edit', 'edit')->name('edit');
	Route::patch('/statuspinjaman/{statuspinjaman}', 'update')->name('update');
	Route::get('/statuspinjaman/{statuspinjaman}/delete', 'destroy')->name('destroy');
});