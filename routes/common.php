<?php

use App\Http\Controllers\Notification\NotificationController;
use Illuminate\Support\Facades\Route;


Route::get('/', [NotificationController::class, 'getNotification']);
Route::get('/details', [NotificationController::class, 'notificationDetails']);

Route::post('/mark-all-read', [NotificationController::class, 'notificationMarkAllRead']);
Route::post('/mark-all-unread', [NotificationController::class, 'notificationMarkAsUnRead']);

Route::post('/delete', [NotificationController::class, 'deleteNotification']);
Route::post('/delete-all', [NotificationController::class, 'deleteAllNotification']);