<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\{
    AccessRightController,
    AuthController,
    CategoryController,
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

Route::group(['middleware' => ['guest']], function() {
    Route::view('/login', 'pages.LoginIndex')->name('auth.login');
    Route::post('login', [AuthController::class, 'attempt'])->name('auth.login-attempt');
});

Route::group(['middleware' => ['auth']], function() {
    Route::get('/', function () {
        return view('App');
    })->name('app');

    Route::get('/logout', [AuthController::class, 'logout'])->name('auth.logout');

    Route::view('kursus-langsung', 'pages.LiveCourseIndex');

    Route::get('/hak-akses/detail/{id}', [AccessRightController::class, 'show'])->name('access-right.show');
    Route::delete('/hak-akses/{id}', [AccessRightController::class, 'destroy'])->name('access-right.destroy');
    Route::patch('/hak-akses/{id}', [AccessRightController::class, 'update'])->name('access-right.update');
    Route::post('/hak-akses', [AccessRightController::class, 'store'])->name('access-right.store');
    Route::get('/hak-akses', [AccessRightController::class, 'index'])->name('access-right.index');

    Route::get('kategori/list', [CategoryController::class, 'list'])->name('category.list');
    Route::delete('kategori/{id}', [CategoryController::class, 'destroy'])->name('category.destroy');
    Route::patch('/kategori/{id}', [CategoryController::class, 'update'])->name('category.update');
    Route::get('kategori/{id}', [CategoryController::class, 'show'])->name('category.show');
    Route::get('kategori', [CategoryController::class, 'index'])->name('category.index');
    Route::post('kategori', [CategoryController::class, 'store'])->name('category.store');
});