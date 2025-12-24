<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KandidatController;
use App\Http\Controllers\KriteriaController;
use App\Http\Controllers\SubKriteriaController;
use App\Http\Controllers\BobotController;
use App\Http\Controllers\PenilaianController;
use App\Http\Controllers\DetailPenilaianController;
use App\Http\Controllers\DashboardController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', [UserController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [UserController::class, 'logout']);
});

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index']);
    
    // Kandidat
    Route::apiResource('kandidat', KandidatController::class);
    Route::get('/kandidat/{id}/form-penilaian', [KandidatController::class, 'formPenilaian']);
    
    // Kriteria
    Route::apiResource('kriteria', KriteriaController::class);
    
    // Sub Kriteria
    Route::get('/kriteria/{id}/sub', [SubKriteriaController::class, 'getByKriteria']);
    Route::apiResource('sub-kriteria', SubKriteriaController::class)->except(['index']); 
    
    // Bobot
    Route::apiResource('bobot', BobotController::class);
    
    // Penilaian
    Route::apiResource('penilaian', PenilaianController::class);
    Route::post('/penilaian/{id}/hitung', [DetailPenilaianController::class, 'hitungSMART']);
    
    // Hasil
    Route::get('/hasil', [DetailPenilaianController::class, 'index']);
    Route::get('/hasil/statistik', [DetailPenilaianController::class, 'statistik']);
    Route::get('/hasil/ranking', [DetailPenilaianController::class, 'ranking']);
    Route::get('/hasil/distribusi', [DetailPenilaianController::class, 'distribusi']);