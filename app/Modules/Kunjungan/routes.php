<?php

use App\Modules\Kunjungan\Controllers\KunjunganController;
use Illuminate\Support\Facades\Route;

Route::controller(KunjunganController::class)->middleware(['web', 'auth'])->name('kunjungan.')->group(function () {
    // routing custom
    Route::get('/kunjungan/validasi/{kunjungan}', 'validasi')->name('validasi.update');
    Route::get('/kunjungan/tolak_validasi/{kunjungan}', 'tolak_validasi')->name('tolak_validasi.update');

    Route::get('/kunjungan', 'index')->name('index');
    Route::get('/kunjungan/data', 'data')->name('data.index');
    Route::get('/kunjungan/create', 'create')->name('create');
    Route::post('/kunjungan', 'store')->name('store');
    Route::get('/kunjungan/{kunjungan}', 'show')->name('show');
    Route::get('/kunjungan/{kunjungan}/edit', 'edit')->name('edit');
    Route::patch('/kunjungan/{kunjungan}', 'update')->name('update');
    Route::get('/kunjungan/{kunjungan}/delete', 'destroy')->name('destroy');
});
