<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebmailController;
use App\Http\Controllers\DataController;
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

// Route::get('/', function () {
//     return view('webmail');
// });

Route::get('/', [WebmailController::class, 'index'])->name('webmail');

Route::get('/login', [WebmailController::class, 'login'])->name('login');
Route::post('/register', [AccountsController::class, 'register'])->name('register');
Route::post('/logging_in', [AccountsController::class, 'logging_in'])->name('logging_in');
Route::get('/logging_out', [AccountsController::class, 'logging_out'])->name('logging_out');
Route::post('/upload_profile_pic', [AccountsController::class, 'upload_profile_pic'])->name('upload_profile_pic');
Route::get('/webmail', [WebmailController::class, 'index'])->name('webmail');
Route::get('/test', [WebmailController::class, 'test'])->name('test');
Route::get('/get_data', [DataController::class, 'get_data'])->name('get_data');
Route::post('/send_mail', [DataController::class, 'send_mail'])->name('send_mail');
Route::post('/upload_attachment', [DataController::class, 'upload_attachment'])->name('upload_attachment');
Route::get('/check_attachment', [DataController::class, 'check_attachment'])->name('check_attachment');
Route::get('/download_attachment', [DataController::class, 'download_attachment'])->name('download_attachment');
Route::post('/remove_attachment', [DataController::class, 'remove_attachment'])->name('remove_attachment');
Route::get('/toggle_data', [DataController::class, 'toggle_data'])->name('toggle_data');
Route::get('/toggle_many_data', [DataController::class, 'toggle_many_data'])->name('toggle_many_data');
Route::get('/delete_mail', [DataController::class, 'delete_mail'])->name('delete_mail');
Route::get('/delete_mail_forever', [DataController::class, 'delete_mail_forever'])->name('delete_mail_forever');
Route::get('/move_to_inbox', [DataController::class, 'move_to_inbox'])->name('move_to_inbox');
Route::post('/labels', [DataController::class, 'labels'])->name('labels');
Route::get('/labels/add', [DataController::class, 'labels_add'])->name('labels_add');
Route::get('/labels/remove', [DataController::class, 'labels_remove'])->name('labels_remove');
Route::get('/ids', [DataController::class, 'ids'])->name('ids');

Route::get('/gmailAPItest', function () {
  return view('gmailAPItest');
});

Route::get('/oauth/gmail', function (){
  return LaravelGmail::redirect();
});

Route::get('/oauth/gmail/callback', function (){
  LaravelGmail::makeToken();
  return redirect()->to('/webmail');
});

Route::get('/oauth/gmail/logout', function (){
  LaravelGmail::logout(); //It returns exception if fails
  return redirect()->to('/webmail');
});
