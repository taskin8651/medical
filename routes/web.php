<?php
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductVariantController;
use App\Http\Controllers\Admin\TierPricingController;
use App\Http\Controllers\Admin\HeroController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Custom\CartController;
use App\Http\Controllers\Custom\CheckoutController;


Route::redirect('/', '/login');
Route::get('/home', function () {
    if (session('status')) {
        return redirect()->route('admin.home')->with('status', session('status'));
    }

    return redirect()->route('admin.home');
});
 
Auth::routes();

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => ['auth']], function () {
    Route::get('/', 'HomeController@index')->name('home');
    // Permissions
    Route::delete('permissions/destroy', 'PermissionsController@massDestroy')->name('permissions.massDestroy');
    Route::resource('permissions', 'PermissionsController');

    // Roles
    Route::delete('roles/destroy', 'RolesController@massDestroy')->name('roles.massDestroy');
    Route::resource('roles', 'RolesController');

    // Users
    Route::delete('users/destroy', 'UsersController@massDestroy')->name('users.massDestroy');
    Route::resource('users', 'UsersController');

    // Audit Logs
    Route::resource('audit-logs', 'AuditLogsController', ['except' => ['create', 'store', 'edit', 'update', 'destroy']]);

    

    // ----------------------------------------------------------------
    // PRODUCTS
    // ----------------------------------------------------------------
    Route::resource('products', ProductController::class);
 
    // AJAX helpers on products
    Route::post('products/{product}/toggle-status',  [ProductController::class, 'toggleStatus'])->name('products.toggleStatus');
    Route::delete('products/{product}/media/{media}', [ProductController::class, 'deleteMedia'])->name('products.media.delete');
    Route::post('products/{product}/media/{media}/set-primary',[ProductController::class, 'setPrimaryImage'])->name('products.media.setPrimary');
    Route::post('products/{product}/media/reorder',  [ProductController::class, 'reorderMedia'])->name('products.media.reorder');
    Route::get('products/{category}/subcategories',  [ProductController::class, 'getSubcategories'])->name('products.subcategories');
    Route::get('products/{product}/media',           [ProductController::class, 'getMedia'])->name('products.media.index');
    Route::post('products/{product}/media/upload',   [ProductController::class, 'uploadMedia'])->name('products.media.upload');

    // ----------------------------------------------------------------
    // CATEGORIES
    // ----------------------------------------------------------------
    Route::resource('categories', 'CategoryController');

    // ----------------------------------------------------------------
    // SUBCATEGORIES
    // ----------------------------------------------------------------
    Route::resource('subcategories', 'SubcategoryController');

    // ----------------------------------------------------------------
    // BRANDS
    // ----------------------------------------------------------------
    Route::resource('brands', 'BrandController');

    // ----------------------------------------------------------------
    // CONTACTS
    // ----------------------------------------------------------------
    Route::resource('contacts', ContactController::class)->only(['index', 'show', 'destroy']);
 
    // ----------------------------------------------------------------
    // PRODUCT VARIANTS (nested under products)
    // ----------------------------------------------------------------
    Route::prefix('products/{product}/variants')->name('products.variants.')->group(function () {
        Route::get('/',          [ProductVariantController::class, 'index'])->name('index');
        Route::get('/create',    [ProductVariantController::class, 'create'])->name('create');
        Route::post('/',         [ProductVariantController::class, 'store'])->name('store');
        Route::get('/{variant}', [ProductVariantController::class, 'edit'])->name('edit');
        Route::put('/{variant}', [ProductVariantController::class, 'update'])->name('update');
        Route::delete('/{variant}', [ProductVariantController::class, 'destroy'])->name('destroy');
        Route::post('/{variantId}/restore', [ProductVariantController::class, 'restore'])->name('restore');
 
        // AJAX
        Route::post('/{variant}/stock',        [ProductVariantController::class, 'adjustStock'])->name('stock');
        Route::post('/{variant}/batch',        [ProductVariantController::class, 'updateBatch'])->name('batch');
        Route::get('/{variant}/check-price',   [ProductVariantController::class, 'checkPrice'])->name('checkPrice');
    });
 
    // ----------------------------------------------------------------
    // TIER PRICING (nested under variant)
    // ----------------------------------------------------------------
    Route::prefix('variants/{variant}/tiers')->name('tiers.')->group(function () {
        Route::get('/',                [TierPricingController::class, 'index'])->name('index');
        Route::post('/',               [TierPricingController::class, 'store'])->name('store');
        Route::put('/{tier}',          [TierPricingController::class, 'update'])->name('update');
        Route::delete('/{tier}',       [TierPricingController::class, 'destroy'])->name('destroy');
        Route::post('/bulk-replace',   [TierPricingController::class, 'bulkReplace'])->name('bulkReplace');
    });
 
    // ----------------------------------------------------------------
    // ORDERS
    // ----------------------------------------------------------------
    Route::resource('orders', OrderController::class);
 
    Route::post('orders/{order}/status',          [OrderController::class, 'updateStatus'])->name('orders.status');
    Route::post('orders/{order}/payment',         [OrderController::class, 'recordPayment'])->name('orders.payment');
    Route::get('orders/{order}/invoice',          [OrderController::class, 'generateInvoice'])->name('orders.invoice');
    Route::get('orders/stats',                    [OrderController::class, 'stats'])->name('orders.stats');

    Route::resource('hero', HeroController::class);
     Route::resource('gallery', GalleryController::class);
     Route::resource('blog', BlogController::class);
     Route::resource('testimonial', TestimonialController::class);
     Route::get('settings', [SettingController::class,'index'])->name('settings.index');
    Route::post('settings', [SettingController::class,'update'])->name('settings.update');
});
Route::group(['prefix' => 'profile', 'as' => 'profile.', 'namespace' => 'Auth', 'middleware' => ['auth']], function () {
    // Change password
    if (file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php'))) {
        Route::get('password', 'ChangePasswordController@edit')->name('password.edit');
        Route::post('password', 'ChangePasswordController@update')->name('password.update');
        Route::post('profile', 'ChangePasswordController@updateProfile')->name('password.updateProfile');
        Route::post('profile/destroy', 'ChangePasswordController@destroy')->name('password.destroyProfile');
    }
});


// Frontend routes
Route::get('/', [App\Http\Controllers\Custom\HomeController::class, 'index'])->name('home');
Route::get('/shop', [App\Http\Controllers\Custom\ShopController::class, 'index'])->name('shop');
Route::get('/shop/filter', [App\Http\Controllers\Custom\ShopController::class, 'filter'])->name('shop.filter');
Route::get('/shop/{slug}', [App\Http\Controllers\Custom\ShopController::class, 'show'])->name('shop.show');
// Category routes
Route::get('/categories', [App\Http\Controllers\Custom\CategoryController::class, 'index'])->name('categories');
Route::get('/category/{slug}', [App\Http\Controllers\Custom\CategoryController::class, 'show'])->name('category.show');

Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/add/{product}', [CartController::class, 'add'])->name('add');
    Route::post('/update/{productId}', [CartController::class, 'update'])->name('update');
    Route::get('/remove/{productId}', [CartController::class, 'remove'])->name('remove');
    Route::get('/clear', [CartController::class, 'clear'])->name('clear');
});



Route::prefix('checkout')->name('checkout.')->group(function () {
    Route::get('/', [CheckoutController::class, 'index'])->name('index');
    Route::post('/place-order', [CheckoutController::class, 'placeOrder'])->name('placeOrder');
    Route::get('/success/{order}', [CheckoutController::class, 'success'])->name('success');
});