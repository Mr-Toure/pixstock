<?php

use App\Http\Controllers\AlbumController;
use App\Http\Controllers\PhotoController;
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

Route::get('/', function () {
    return view('home.index')->with('title', config('app.name'));
});

Route::resource('albums', AlbumController::class);

Route::get('/', \App\Http\Controllers\HomeController::class)->name('home');

Route::get('photo/{photo}', [PhotoController::class,'show'])->name('photos.show');

Route::middleware(['auth', 'verified'])->group(function (){
    //authentifier et email verifier pour accéder à ces routes
    Route::get('photos/create/{album}',[PhotoController::class, 'create'])->name('photos.create');
    Route::post('photos/store/{album}',[PhotoController::class, 'store'])->name('photos.store');

});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';
