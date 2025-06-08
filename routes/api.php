<?php

use App\Exports\KuesionerExport;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KuisionerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function() {
    Route::prefix('/kuesioner')->group(function () {
        Route::post('create', [KuisionerController::class, 'create']);
    });

    Route::post('antrian/', [KuisionerController::class, 'getAntrian']);
    Route::get('antrian/show/{user_id}', [KuisionerController::class, 'seeAntrian']);
});
// POST ROUTE BUAT TAMU
Route::post('/tamu/send', [KuisionerController::class, 'createTamu']);


//  GET DATA FOR PUBLIC
Route::get('antrian/kelas/{kelas}', [KuisionerController::class, 'seeAntrianKelas']);
Route::get('/show/siswa', [KuisionerController::class, 'show']);
Route::get('/show/tamu', [KuisionerController::class, 'showTamu']);

Route::get('/export-kuesioner', function () {
    return Excel::download(new KuesionerExport, 'kuesioner.xlsx');
});

Route::get('/export-kuesioner-tamu', function () {
    return Excel::download(new KuesionerTamu, 'kuesioner-tamu.xlsx')
});

