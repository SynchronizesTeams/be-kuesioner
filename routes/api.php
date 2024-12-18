<?php

use App\Exports\KuesionerExport;
use App\Http\Controllers\KuisionerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;




Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('/kuisioner')->group(function () {
    Route::post('create', [KuisionerController::class, 'create']);
    Route::get('show', [KuisionerController::class, 'show']);
});

Route::get('/export-kuesioner', function () {
    return Excel::download(new KuesionerExport, 'kuesioner.xlsx');
});