<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KandidatController;
use App\Http\Controllers\KriteriaController;
use App\Http\Controllers\SubKriteriaController;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/kandidat/{id}/penilaian', function ($id) {
    return view('kandidat.penilaian', ['id' => $id]);
})->name('penilaian.form');

Route::get('/', function () {
    return view('login');
});

// routes/web.php
Route::resource('kandidat', KandidatController::class);

// Tambahkan route untuk kriteria
Route::resource('kriteria', KriteriaController::class);

    Route::get('/dashboard/kriteria/{kriteria_id}/sub', [SubKriteriaController::class, 'index'])->name('subkriteria.index');

Route::get('/dashboard', function () {
    return view('dashboard/home');
})->name('dashboard');

Route::get('/dataKandidat', function () {
    return view('dashboard/data_kandidat');
})->name('icons');

Route::get('/penilaian', function () {
    return view('dashboard/penilaian');
})->name('map');

Route::get('/kriteria', function () {
    return view('dashboard/kriteria');
})->name('kriteria');

Route::get('/sub-kriteria', function () {
    return view('dashboard/sub-kriteria');
})->name('sub-kriteria');

Route::get('/bobot', function () {
    return view('dashboard/bobot');
})->name('notifications');

Route::get('/perhitungan', function () {
    return view('dashboard/perhitungan');
})->name('tables');

Route::get('/typography', function () {
    return view('dashboard.typography');
})->name('typography');

Route::get('/upgrade', function () {
    return view('dashboard.upgrade');
})->name('upgrade');

Route::get('/profile', function () {
    return view('dashboard.profile');
})->name('profile');

Route::get('/logout', function () {
    return view('dashboard.logout');
})->name('logout');