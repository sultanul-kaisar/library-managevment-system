<?php

use App\Http\Controllers\Common\BookController;
use App\Http\Controllers\Common\BorrowController;
use Illuminate\Support\Facades\Route;


Route::get('/books', [BookController::class, 'booksList']);
Route::get('book-view', [BookController::class, 'bookView']);

Route::get('/borrow-requests', [BorrowController::class, 'borrowRequestList']);
Route::post('/add-borrow-request', [BorrowController::class, 'borrowRequest']);
Route::post('/update-borrow-request', [BorrowController::class, 'borrowRequestUpdate']);
Route::post('/delete-borrow-request', [BorrowController::class, 'borrowRequestDelete']);
Route::get('/edit-history', [BorrowController::class, 'editHistory']);