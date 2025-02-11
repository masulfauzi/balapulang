<?php

use Illuminate\Support\Facades\Route;
use App\Modules\BankGaji\Controllers\BankGajiController;

Route::controller(BankGajiController::class)->middleware(['web','auth'])->name('bankgaji.')->group(function(){
	Route::get('/bankgaji', 'index')->name('index');
	Route::get('/bankgaji/data', 'data')->name('data.index');
	Route::get('/bankgaji/create', 'create')->name('create');
	Route::post('/bankgaji', 'store')->name('store');
	Route::get('/bankgaji/{bankgaji}', 'show')->name('show');
	Route::get('/bankgaji/{bankgaji}/edit', 'edit')->name('edit');
	Route::patch('/bankgaji/{bankgaji}', 'update')->name('update');
	Route::get('/bankgaji/{bankgaji}/delete', 'destroy')->name('destroy');
});