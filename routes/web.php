<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\postController;

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

Auth::routes();

// Apply the 'auth' middleware to the home route
Route::get('/', [postController::class, 'index'])->name('home')->middleware('auth');

// Group routes that require authentication
Route::middleware('auth')->group(function () {
    Route::resource('posts', postController::class);
});