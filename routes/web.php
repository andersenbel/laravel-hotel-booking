<?php

use App\Http\Controllers\MainController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


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

Route::get('/', [MainController::class, 'roomslist']);

Route::get('/search', [MainController::class, 'search']);

Route::get('/room/{id}',  [MainController::class, 'oneroom']);

Route::post('/bookin',  [MainController::class, 'bookin']);
