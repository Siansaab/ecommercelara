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
    Route::delete('/admin/brand/{id}/delete', [AdminController::class, 'brand_delete'])->name('admin.brand.delete');
    //category
    Route::get('/admin/category', [AdminController::class, 'category'])->name('admin.category');
    Route::get('/admin/category/add/', [AdminController::class, 'add_category'])->name('admin.category-add');
    Route::post('/admin/category/store/', [AdminController::class, 'category_store'])->name('admin.category.store');
    Route::get('/admin/category/edit/{id}', [AdminController::class, 'category_edit'])->name('admin.category.edit');
    Route::put('/admin/category/update/', [AdminController::class, 'category_update'])->name('admin.category.update');
    Route::delete('/admin/category/{id}/delete', [AdminController::class, 'category_delete'])->name('admin.category.delete');
    //products
    Route::get('/admin/products/', [AdminController::class, 'products'])->name('admin.products');
    Route::get('admin/products/add',[AdminController::class,'add_products'])->name('admin.product-add');
    Route::post('/admin/products/store/', [AdminController::class, 'product_store'])->name('admin.product.store');
    Route::get('/admin/products/edit/{id}', [AdminController::class, 'product_edit'])->name('admin.product.edit');
});


Route::get('/', [HomeController::class, 'index'])->name('home.index');
