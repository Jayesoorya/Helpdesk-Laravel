<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;

// Route::get('/get', 'App\Http\Controllers\AuthController@get');
// Route::get('test', function() {
//     return "Test api";
// }); 

Route::post('/register', [AuthController::class, 'register']);

Route::post('/login', [AuthController::class, 'login']);

//with middleware
Route::middleware('jwt.auth')->group(function () {

Route::post('/change-password', [DashboardController::class, 'changePassword']);

Route::get('/user', [DashboardController::class, 'getProfile']);

Route::get('/tickets', [DashboardController::class, 'getTickets']);

Route::get('/tickets/{id}', [DashboardController::class, 'ticketDetails']);

Route::post('/tickets', [DashboardController::class, 'createTicket']);

Route::post('/tickets/{id}', [DashboardController::class, 'updateTicket']);

Route::delete('/tickets/{id}', [DashboardController::class, 'deleteTicket']);

});
