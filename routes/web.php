<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/students', [StudentController::class, 'index']);
Route::get('/students/create', [StudentController::class, 'create']);
Route::post('/students/store', [StudentController::class, 'store']);

Route::get('/students/show/{id}', [StudentController::class, 'show']);

Route::get('/students/edit/{id}', [StudentController::class, 'edit']);
Route::post('/students/update/{id}', [StudentController::class, 'update']);

Route::get('/students/delete/{id}', [StudentController::class, 'delete']);
Route::get('/students/restore/{id}', [StudentController::class, 'restore']);
Route::get('/students/force-delete/{id}', [StudentController::class, 'forceDelete']);

Route::get('/students/trash', [StudentController::class, 'trash']);

Route::get('/students/print', [StudentController::class, 'printView']);

Route::post('/students/bulk-action', [StudentController::class, 'bulkAction']);

Route::get('/students/check-email', [StudentController::class, 'checkEmail']);
Route::get('/students/check-email-edit', [StudentController::class, 'checkEmailEdit']);

Route::get('/students/export', [StudentController::class, 'export']);

Route::get('/students/import', [StudentController::class, 'importForm']);
Route::post('/students/import', [StudentController::class, 'import']);
