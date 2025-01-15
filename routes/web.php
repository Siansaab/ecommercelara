<?php
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\authadmin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

 
Auth::routes();
Route::middleware(['auth'])->group(function(){
    Route::get('/account-dashboard', [UserController::class, 'user'])->name('user.index');
});
Route::middleware(['auth',authadmin::class])->group(function(){
    Route::get('/admin', [AdminController::class, 'admin'])->name('admin.index');
});


Route::get('/', [HomeController::class, 'index'])->name('home.index');
