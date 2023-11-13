<?php

use App\Http\Controllers\Common\BookController;
use App\Http\Controllers\Common\BorrowController;
use Illuminate\Support\Facades\Route;

Route::get('/all-books', [BookController::class, 'bookList']);
Route::post('/book-add', [BookController::class, 'bookAdd']);
Route::post('/book-update', [BookController::class, 'bookUpdate']);
Route::post('/book-delete', [BookController::class, 'bookDelete']);
Route::post('/book-status', [BookController::class, 'bookStatus']);
Route::post('/book-restore', [BookController::class, 'bookRestore']);

Route::get('/request-list', [BorrowController::class, 'requestList']);
Route::post('/request-status', [BorrowController::class, 'requestStatus']);

Route::post('/borrow-request-search', [BorrowController::class, 'borrowRequestSearch']);