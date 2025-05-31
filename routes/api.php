<?php

use App\Exports\KuesionerExport;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KuisionerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;





Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function() {
    Route::prefix('/kuesioner')->group(function () {
        Route::post('create', [KuisionerController::class, 'create']);
        Route::get('show', [KuisionerController::class, 'show']);
    });

    Route::post('antrian/', [KuisionerController::class, 'getAntrian']);
    Route::get('antrian/show/{user_id}', [KuisionerController::class, 'seeAntrian']);
});



Route::get('/export-kuesioner', function () {
    return Excel::download(new KuesionerExport, 'kuesioner.xlsx');
});