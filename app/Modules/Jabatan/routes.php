<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Jabatan\Controllers\JabatanController;

Route::controller(JabatanController::class)->middleware(['web','auth'])->name('jabatan.')->group(function(){
	Route::get('/jabatan', 'index')->name('index');
	Route::get('/jabatan/data', 'data')->name('data.index');
	Route::get('/jabatan/create', 'create')->name('create');
	Route::post('/jabatan', 'store')->name('store');
	Route::get('/jabatan/{jabatan}', 'show')->name('show');
	Route::get('/jabatan/{jabatan}/edit', 'edit')->name('edit');
	Route::patch('/jabatan/{jabatan}', 'update')->name('update');
	Route::get('/jabatan/{jabatan}/delete', 'destroy')->name('destroy');
});