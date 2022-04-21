<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\{
    CategoryController,
    AccessRightController
};

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

Route::get('/', function () {
    return view('App');
});

Route::view('kursus-langsung', 'pages.LiveCourseIndex');
Route::get('/hak-akses/detail/{id}', [AccessRightController::class, 'show'])->name('access-right.show');
Route::patch('/hak-akses/{id}', [AccessRightController::class, 'update'])->name('access-right.update');
Route::post('/hak-akses', [AccessRightController::class, 'store'])->name('access-right.store');
Route::get('/hak-akses', [AccessRightController::class, 'index'])->name('access-right.index');