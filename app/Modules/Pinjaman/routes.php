<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Pinjaman\Controllers\PinjamanController;

Route::controller(PinjamanController::class)->middleware(['web','auth'])->name('pinjaman.')->group(function(){
	Route::get('/pinjaman', 'index')->name('index');
	Route::get('/pinjaman/data', 'data')->name('data.index');
	Route::get('/pinjaman/create', 'create')->name('create');
	Route::post('/pinjaman', 'store')->name('store');
	Route::get('/pinjaman/{pinjaman}', 'show')->name('show');
	Route::get('/pinjaman/{pinjaman}/edit', 'edit')->name('edit');
	Route::patch('/pinjaman/{pinjaman}', 'update')->name('update');
	Route::get('/pinjaman/{pinjaman}/delete', 'destroy')->name('destroy');
});