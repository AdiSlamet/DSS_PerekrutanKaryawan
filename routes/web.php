<?php

use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', function () {
    return view('login');
});

Route::get('/dashboard', function () {
    return view('dashboard/home');
})->name('dashboard');

Route::get('/dataKandidat', function () {
    return view('dashboard/data_kandidat');
})->name('icons');

Route::get('/penilaian', function () {
    return view('dashboard/penilaian');
})->name('map');

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