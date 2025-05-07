<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ViewController;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [ViewController::class, 'loginview'])->name('login');
Route::get('/sign_up', [ViewController::class, 'register'])->name('register');
Route::get('/home', [ViewController::class, 'dashboard'])->name('home');
Route::get('/profile', [ViewController::class, 'profile'])->name('profile');
Route::get('/details/{id}', [ViewController::class, 'details'])->name('details');


