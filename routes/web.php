<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebmailController;
use App\Http\Controllers\DummyDataController;
use App\Http\Controllers\AccountsController;

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

Route::get('/', function () {
    return view('welcome');
});
Route::get('/login', [WebmailController::class, 'login'])->name('login');
Route::post('/register', [AccountsController::class, 'register'])->name('register');
Route::post('/logging_in', [AccountsController::class, 'logging_in'])->name('logging_in');
Route::get('/logging_out', [AccountsController::class, 'logging_out'])->name('logging_out');
Route::get('/webmail', [WebmailController::class, 'index'])->name('webmail');
Route::get('/test', [WebmailController::class, 'test'])->name('test');
Route::get('/get_dummy_data', [DummyDataController::class, 'get_dummy_data'])->name('get_dummy_data');
Route::get('/toggle_dummy_data', [DummyDataController::class, 'toggle_dummy_data'])->name('toggle_dummy_data');
Route::get('/toggle_many_dummy_data', [DummyDataController::class, 'toggle_many_dummy_data'])->name('toggle_many_dummy_data');
