<?php

use App\Http\Controllers\JopController;
use App\Http\Controllers\ProfileController;
use App\Livewire\Chat\Chat;
use App\Livewire\Chat\Index;
use App\Livewire\Chat\Users;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('website.jobs.all-jobs');
// });

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
Route::middleware('auth')->group(function (){

    Route::get("/chat" , Index::class)->name("chat.index");
    Route::get("/chat/{id}" , Chat::class)->name("chat.chat");
    Route::get("/users" , Users::class)->name("chat.user");
    Route::get('/jobs' , [JopController::class , "index"])->name('allJop');
    Route::get('/jobs/upload/jop/' , [JopController::class , "create"])->name('createJop');
    Route::post('/jobs/store' , [JopController::class , "store"])->name('storeJop');

});
