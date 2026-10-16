<?php
use App\Http\Controllers\{AuthController, CartController, CheckoutController, OrderController, ShopController};
use App\Http\Controllers\Admin\{CategoryController, DashboardController, OrderController as AdminOrders, ProductController};
use App\Http\Middleware\{RoleMiddleware, SetLocale};
use Illuminate\Support\Facades\Route;

Route::middleware(SetLocale::class)->group(function () {
    Route::get('/lang/{l}', fn ($l) => back()->withCookie(cookie('locale', $l === 'en' ? 'en' : 'ar', 525600)))->whereIn('l', ['ar', 'en']);

    Route::get('/', [ShopController::class, 'home'])->name('home');
    Route::get('/shop', [ShopController::class, 'index'])->name('shop');
    Route::get('/perfumes', fn (Illuminate\Http\Request $r) => app(ShopController::class)->index($r, 'perfume'))->name('perfumes');
    Route::get('/product/{product}', [ShopController::class, 'show'])->name('product');

    Route::get('/cart', [CartController::class, 'index'])->name('cart');
    Route::post('/cart/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/{product}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{product}', [CartController::class, 'remove'])->name('cart.remove');

    Route::get('/checkout', [CheckoutController::class, 'form'])->name('checkout');
    Route::post('/checkout', [CheckoutController::class, 'store'])->middleware('throttle:10,1');
    Route::get('/track', [OrderController::class, 'track'])->name('track')->middleware('throttle:20,1');

    Route::middleware('guest')->group(function () {
        Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
        Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');
        Route::get('/register', [AuthController::class, 'registerForm'])->name('register');
        Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:5,1');
    });
    Route::middleware('auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('/account/orders', [OrderController::class, 'mine'])->name('orders.mine');
    });

    // لوحة التحكم: admin و editor
    Route::prefix('admin')->name('admin.')->middleware(['auth', RoleMiddleware::class . ':admin,editor'])->group(function () {
        Route::get('/', DashboardController::class)->name('dashboard');
        Route::resource('products', ProductController::class)->except(['show', 'destroy']);
        Route::get('orders', [AdminOrders::class, 'index'])->name('orders.index');
        Route::get('orders/{order}', [AdminOrders::class, 'show'])->name('orders.show');
        Route::patch('orders/{order}', [AdminOrders::class, 'update'])->name('orders.update');

        // admin فقط: الحذف + الأقسام
        Route::middleware(RoleMiddleware::class . ':admin')->group(function () {
            Route::delete('products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
            Route::resource('categories', CategoryController::class)->except(['show', 'create']);
        });
    });
});
