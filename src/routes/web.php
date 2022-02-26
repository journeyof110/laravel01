<?php

use App\Http\Controllers\TimeCardController;
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

// Route::view('/', 'home');
// Route::view('/home', 'home');

Route::middleware(['auth'])->group(function (){
    Route::view('/', 'home');

    Route::resource('time_card', TimeCardController::class)->missing([TimeCardController::class, 'missingError']);
    Route::controller(TimeCardController::class)->prefix('time_card')->name('time_card')->group(function (){
        Route::post('/start', 'start')->name('.start');
        Route::post('/end/{time_card?}', 'end')->name('.end')->missing([TimeCardController::class, 'missingError']);
    });
});

require __DIR__.'/auth.php';
