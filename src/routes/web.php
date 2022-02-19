<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TimeCardController;

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

Route::view('/', 'home');
Route::view('/home', 'home');

Route::controller(TimeCardController::class)->prefix('time_card')->name('time_card')->group(function() {
  Route::get('/', 'index');
  Route::post('/start', 'start')->name('.start');
  Route::post('/end/{timeCard?}', 'end')->name('.end')->missing('showError', ['action' => '終了']);
  Route::get('/create', 'create')->name('.create');
  Route::post('/create', 'store')->name('.store');
  Route::get('/show/{timeCard}', 'show')->name('.show');
  Route::get('/edit/{timeCard}', 'edit')->name('.edit');
  Route::post('/edit/{timeCard}', 'update')->name('.update');
  Route::get('/destroy/{timeCard}', 'destroy')->name('.destroy');
});
