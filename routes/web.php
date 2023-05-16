<?php

use App\Http\Controllers\API\AnggotaController;
use App\Http\Controllers\API\TemplateController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/template', function () {
    return view('backend.template');
});
Route::get('/anggota', function () {
    return view('backend.anggota');
});
Route::post('/anggota', function () {
    return view('backend.anggota');
});


Route::prefix('v1')->controller(TemplateController::class)->group(function () {
    Route::get('/template', 'getAllData');
    Route::post('/template/create', 'createData');
    Route::get('/project/get/{uuid}', 'getDataByUuid');
    Route::put('/project/update/{uuid}', 'updateDataByUuid');

});

Route::prefix('v2')->controller(AnggotaController::class)->group(function () {
    Route::get('/anggota', 'getAllData');
    Route::post('/anggota/create', 'store');
    Route::get('/anggota/pdf/{id}', 'getPdfUrl');
    Route::put('/project/update/{uuid}', 'updateDataByUuid');

});
