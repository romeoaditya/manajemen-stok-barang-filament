<?php

use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserDashboardController;

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/user-dashboard', [UserDashboardController::class, 'index']);
