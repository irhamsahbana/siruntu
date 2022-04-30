<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\{
    AccessRightController,
    AuthController,
    CategoryController,
    ClassroomController,
    CourseController,
    CourseMasterController,
    LearnerController,
    LecturerController,
    Select2Controller,
};
use App\Models\CourseMaster;

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

    Route::get('hak-akses/detail/{id}', [AccessRightController::class, 'show'])->name('access-right.show');
    Route::delete('hak-akses/{id}', [AccessRightController::class, 'destroy'])->name('access-right.destroy');
    Route::patch('hak-akses/{id}', [AccessRightController::class, 'update'])->name('access-right.update');
    Route::post('hak-akses', [AccessRightController::class, 'store'])->name('access-right.store');
    Route::get('hak-akses', [AccessRightController::class, 'index'])->name('access-right.index');

    Route::get('kategori/list', [CategoryController::class, 'list'])->name('category.list');
    Route::delete('kategori/{id}', [CategoryController::class, 'destroy'])->name('category.destroy');
    Route::patch('kategori/{id}', [CategoryController::class, 'update'])->name('category.update');
    Route::get('kategori/{id}', [CategoryController::class, 'show'])->name('category.show');
    Route::get('kategori', [CategoryController::class, 'index'])->name('category.index');
    Route::post('kategori', [CategoryController::class, 'store'])->name('category.store');

    Route::get('mata-kuliah/detail/{id}', [CourseController::class, 'show'])->name('course.show');
    Route::delete('mata-kuliah/{id}', [CourseController::class, 'destroy'])->name('course.destroy');
    Route::patch('mata-kuliah/{id}', [CourseController::class, 'update'])->name('course.update');
    Route::post('mata-kuliah', [CourseController::class, 'store'])->name('course.store');
    Route::get('mata-kuliah', [CourseController::class, 'index'])->name('course.index');

    Route::get('master-mata-kuliah/detail/{id}', [CourseMasterController::class, 'show'])->name('course-master.show');
    Route::delete('master-mata-kuliah/{id}', [CourseMasterController::class, 'destroy'])->name('course-master.destroy');
    Route::patch('master-mata-kuliah/{id}', [CourseMasterController::class, 'update'])->name('course-master.update');
    Route::post('master-mata-kuliah', [CourseMasterController::class, 'store'])->name('course-master.store');
    Route::get('master-mata-kuliah', [CourseMasterController::class, 'index'])->name('course-master.index');

    Route::get('dosen/detail/{id}', [LecturerController::class, 'show'])->name('lecturer.show');
    Route::delete('dosen/{id}', [LecturerController::class, 'destroy'])->name('lecturer.destroy');
    Route::patch('dosen/{id}', [LecturerController::class, 'update'])->name('lecturer.update');
    Route::post('dosen', [LecturerController::class, 'store'])->name('lecturer.store');
    Route::get('dosen', [LecturerController::class, 'index'])->name('lecturer.index');

    Route::get('mahasiswa/detail/{id}', [LearnerController::class, 'show'])->name('learner.show');
    Route::delete('mahasiswa/{id}', [LearnerController::class, 'destroy'])->name('learner.destroy');
    Route::patch('mahasiswa/{id}', [LearnerController::class, 'update'])->name('learner.update');
    Route::post('mahasiswa', [LearnerController::class, 'store'])->name('learner.store');
    Route::get('mahasiswa', [LearnerController::class, 'index'])->name('learner.index');

    Route::get('ruang-kelas/detail/{id}', [ClassroomController::class, 'show'])->name('classroom.show');
    Route::delete('ruang-kelas/{id}', [ClassroomController::class, 'destroy'])->name('classroom.destroy');
    Route::patch('ruang-kelas/{id}', [ClassroomController::class, 'update'])->name('classroom.update');
    Route::post('ruang-kelas', [ClassroomController::class, 'store'])->name('classroom.store');
    Route::get('ruang-kelas', [ClassroomController::class, 'index'])->name('classroom.index');

    Route::group(['prefix' => 'select2'], function() {
        Route::get('master-mata-kuliah/{id}', [Select2Controller::class, 'courseMaster'])->name('select2.course-master');
        Route::get('master-mata-kuliah', [Select2Controller::class, 'courseMasters'])->name('select2.course-masters');
        Route::get('mata-kuliah/{id}', [Select2Controller::class, 'course'])->name('select2.course');
        Route::get('mata-kuliah', [Select2Controller::class, 'courses'])->name('select2.courses');
        Route::get('mahasiswa', [Select2Controller::class, 'learners'])->name('select2.learners');
        Route::get('dosen', [Select2Controller::class, 'lecturers'])->name('select2.lecturers');
        Route::get('kategori/{id}', [Select2Controller::class, 'category'])->name('select2.category');
        Route::get('kategori', [Select2Controller::class, 'categories'])->name('select2.categories');
    });
});