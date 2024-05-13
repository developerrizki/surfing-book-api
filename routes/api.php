<?php

use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\CountryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => 'api.token.verify'], function (){
    Route::get('countries', [CountryController::class, 'index'])->name('country.index');
    Route::get('country/{uuid}', [CountryController::class, 'show'])->name('country.show');
    Route::post('country', [CountryController::class, 'store'])->name('country.store');
    Route::put('country/{uuid}', [CountryController::class, 'update'])->name('country.update');
    Route::delete('country/{uuid}', [CountryController::class, 'destroy'])->name('country.destroy');

    Route::get('books', [BookController::class, 'index'])->name('book.index');
    Route::get('book/{uuid}', [BookController::class, 'show'])->name('book.show');
    Route::post('book', [BookController::class, 'store'])->name('book.store');
    Route::post('book/{uuid}', [BookController::class, 'update'])->name('book.update');
    Route::delete('book/{uuid}', [BookController::class, 'destroy'])->name('book.destroy');
});
