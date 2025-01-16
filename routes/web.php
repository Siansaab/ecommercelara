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
    Route::get('/admin/brands', [AdminController::class, 'brands'])->name('admin.brands');
    Route::get('/admin/brand/add/', [AdminController::class, 'add_brands'])->name('admin.brand-add');
    Route::post('/admin/brand/store/', [AdminController::class, 'brand_store'])->name('admin.brand.store');
    Route::get('/admin/brand/edit/{id}', [AdminController::class, 'brand_edit'])->name('admin.brand.edit');
    Route::put('/admin/brand/update/', [AdminController::class, 'brand_update'])->name('admin.brand.update');
    
    
});


Route::get('/', [HomeController::class, 'index'])->name('home.index');
