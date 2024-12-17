<?php

use App\Http\Controllers\KuisionerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('/kuisioner')->group(function () {
    Route::post('create', [KuisionerController::class, 'create']);
    Route::get('show', [KuisionerController::class, 'show']);
});
