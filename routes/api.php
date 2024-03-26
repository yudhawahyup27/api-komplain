<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PengaduansController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::post('/login', [AuthController::class, 'loginapi']);

Route::post('/create-pengaduan', [PengaduansController::class, 'createPengaduan']);
Route::get('/get-all-pengaduan', [PengaduansController::class, 'getAllPengaduan']);
Route::get('/detail-pengaduan/{id}', [PengaduansController::class, 'getPengaduanById']);
Route::put('/pengaduan/{id}/rating', [PengaduansController::class, 'addRating']);


