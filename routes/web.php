<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\SiteSettingsController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\SocialMediaLinkController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\MenuItemController;
use App\Http\Controllers\Admin\ProductVariantController;
use App\Http\Controllers\Admin\LanguageController;
use App\Http\Controllers\Admin\CustomerController;

use App\Http\Controllers\StoreController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [StoreController::class, 'index'])->name('xylo.home');


Route::get('/login', function () {
    return view('admin.auth.login');
});

Auth::routes();



Route::get('/lang/{locale}', function ($locale) {

    if (in_array($locale, ['en', 'es', 'de', 'ar', 'fa', 'it', 'nl', 'pl', 'pt', 'tr','zh', 'fr', 'ru', 'ja', 'ko', 'th', 'vi','hi','id'])) {
        session(['locale' => $locale]);
        App::setLocale($locale);        
    }
    return redirect()->back();
});

Route::get('/test', function () {
    return view('test');
});


Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {

    Route::resource('categories', CategoryController::class);
    Route::resource('products', ProductController::class);

    Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index'])->name('dashboard');


    /* Brands */
    Route::get('/brands', [BrandController::class, 'index'])->name('brands.index');
    Route::get('admin/brands/getdata', [BrandController::class, 'getData'])->name('brands.getData');
    Route::get('brands/{id}/edit', [BrandController::class, 'edit'])->name('brands.edit');
    Route::put('brands/{brand}', [BrandController::class, 'update'])->name('brands.update'); 
    Route::get('brands/create', [BrandController::class, 'create'])->name('brands.create');
    Route::post('brands', [BrandController::class, 'store'])->name('brands.store');
    Route::delete('brands/{id}', [BrandController::class, 'destroy'])->name('brands.destroy');
    Route::post('brands/update-status', [BrandController::class, 'updateStatus'])->name('brands.updateStatus');

    Route::post('/change-language', [LanguageController::class, 'changeLanguage'])->name('change.language');



});

Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');

Route::post('/categories/data', [CategoryController::class, 'getCategories'])->name('categories.data');
Route::post('/admin/categories/update-status', [CategoryController::class, 'updateCategoryStatus'])->name('admin.categories.updateStatus');


Route::post('products/data', [ProductController::class, 'getProducts'])->name('products.data');
Route::post('admin/products/updateStatus', [ProductController::class, 'updateStatus'])->name('admin.products.updateStatus');


Route::get('site-settings', [SiteSettingsController::class, 'index'])->name('site-settings.index');
Route::get('site-settings/edit', [SiteSettingsController::class, 'edit'])->name('admin.site-settings.edit');
Route::put('site-settings/update', [SiteSettingsController::class, 'update'])->name('admin.site-settings.update');





Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('banners', BannerController::class);
    Route::post('banners/data', [BannerController::class, 'getData'])->name('banners.data');
    Route::put('/banners/toggle-status/{id}', [BannerController::class, 'toggleStatus'])->name('banners.toggleStatus');
    Route::post('/banners/update-status', [BannerController::class, 'updateStatus'])->name('banners.updateStatus');

}); 


Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::resource('admin/menus', MenuController::class);
    Route::post('menus/data', [MenuController::class, 'getData'])->name('menus.data');

});

Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    // Menu routes
    Route::resource('menus', MenuController::class);
    Route::post('menus/{menuId}/items/data', [MenuItemController::class, 'getData'])->name('menu.items.data');
   
    
    // Menu Item routes
    Route::prefix('menus/{menuId}/items')->name('menu.items.')->group(function () {
       
        Route::get('/', [MenuItemController::class, 'index'])->name('index');
        Route::get('create', [MenuItemController::class, 'create'])->name('create');
        Route::post('/', [MenuItemController::class, 'store'])->name('store');
        Route::get('{menuItemId}/edit', [MenuItemController::class, 'edit'])->name('edit');
        Route::put('{menuItemId}', [MenuItemController::class, 'update'])->name('update');
        Route::delete('{menuItemId}', [MenuItemController::class, 'destroy'])->name('destroy');
    });
}); 



Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::resource('social-media-links', SocialMediaLinkController::class);
    Route::post('social-media-links/data', [SocialMediaLinkController::class, 'getData'])->name('social-media-links.data');
});


Route::prefix('admin')->name('admin.')->middleware('auth')->group(function() {
    Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
    Route::delete('orders/{id}', [OrderController::class, 'destroy'])->name('orders.destroy');
    Route::post('orders/data', [OrderController::class, 'getData'])->name('orders.data');
});


Route::prefix('admin')->name('admin.')->middleware( 'auth')->group(function () {
    // Product Variant Routes
    Route::resource('product_variants', ProductVariantController::class);
    Route::post('/product_variants/data', [ProductVariantController::class, 'getData'])->name('product_variants.data');
});



Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('customers', CustomerController::class);
});

Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::resource('reviews', \App\Http\Controllers\Admin\ProductReviewController::class)->except(['create', 'store']);
});


