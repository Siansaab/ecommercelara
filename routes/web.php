<?php
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\Shopcontroler;
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
    Route::put('/admin/products/update/', [AdminController::class, 'product_update'])->name('admin.product.update');
    Route::delete('/admin/products/{id}/delete', [AdminController::class, 'product_delete'])->name('admin.product.delete');
    //products
});


Route::get('/', [HomeController::class, 'index'])->name('home.index');
Route::get('/shop', [Shopcontroler::class, 'index'])->name('shop.index');
Route::get('/shop/{product_slug}', [Shopcontroler::class, 'product_details'])->name('shop.product.details');

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add_to_cart'])->name('cart.add');
Route::get('/cart/remove/{rowId}', [CartController::class, 'remove_from_cart'])->name('cart.remove'); // Remove item
Route::delete('/cart/clear', [CartController::class, 'clear_cart'])->name('cart.empty'); // Remove item
Route::put('/cart/deacrease-qty/{rowId}', [CartController::class, 'decrease_cart_quantity'])->name('cart.qty.decrease'); // Remove item
Route::put('/cart/increase-qty/{rowId}', [CartController::class, 'increase_cart_quantity'])->name('cart.qty.increase'); // Remove item8