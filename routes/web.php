<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/insert', [StudentController::class, 'insert']);
// Route::get('/students', [StudentController::class, 'index']);
// Route::get('/student/{id}', [StudentController::class, 'single']);
// Route::get('/update/{id}', [StudentController::class, 'update']);
// Route::get('/delete/{id}', [StudentController::class, 'delete']);

Route::get('/students', [StudentController::class, 'index']);
Route::get('/students/create', [StudentController::class, 'create']);
Route::post('/students/store', [StudentController::class, 'store']);
Route::get('/students/edit/{id}', [StudentController::class, 'edit']);
Route::post('/students/update/{id}', [StudentController::class, 'update']);
Route::get('/students/delete/{id}', [StudentController::class, 'delete']);