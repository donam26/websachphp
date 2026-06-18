<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\VNPayController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\BookManagementController;
use App\Http\Controllers\Admin\AuthorController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\StockImportController;
use App\Http\Controllers\Admin\PaymentMethodController;

/*
|--------------------------------------------------------------------------
| Public routes (mọi người xem được)
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');

// Sách (browse công khai)
Route::prefix('books')->name('books.')->group(function () {
    Route::get('/', [BookController::class, 'index'])->name('index');
    Route::get('/category/{slug}', [BookController::class, 'category'])->name('category');
    Route::get('/{book}', [BookController::class, 'show'])->name('show');
});

/*
|--------------------------------------------------------------------------
| Guest only (chưa đăng nhập)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Authenticated user routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    // Profile
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Cart
    Route::prefix('cart')->name('cart.')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('index');
        Route::post('/add', [CartController::class, 'add'])->name('add');
        Route::put('/{cartItem}', [CartController::class, 'update'])->name('update');
        Route::delete('/{cartItem}', [CartController::class, 'remove'])->name('remove');
        Route::delete('/', [CartController::class, 'clear'])->name('clear');
    });

    // Checkout / Orders
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('orders.checkout');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
    Route::post('/orders/{order}/repay', [VNPayController::class, 'createPayment'])->name('orders.repay');

    // VNPay return (khách quay về sau khi thanh toán)
    Route::get('/vnpay/return', [VNPayController::class, 'return'])->name('vnpay.return');

    // Đánh giá sách
    Route::post('/books/{book}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
});

// VNPay IPN (server-to-server, không cần auth/CSRF)
Route::match(['get', 'post'], '/vnpay/ipn', [VNPayController::class, 'ipn'])->name('vnpay.ipn');

/*
|--------------------------------------------------------------------------
| Admin routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Quản lý danh mục sách
    Route::prefix('categories')->name('categories.')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('index');
        Route::post('/', [CategoryController::class, 'store'])->name('store');
        Route::put('/{category}', [CategoryController::class, 'update'])->name('update');
        Route::delete('/{category}', [CategoryController::class, 'destroy'])->name('destroy');
    });

    // Quản lý sách
    Route::prefix('books')->name('books.')->group(function () {
        Route::get('/', [BookManagementController::class, 'index'])->name('index');
        Route::get('/create', [BookManagementController::class, 'create'])->name('create');
        Route::post('/', [BookManagementController::class, 'store'])->name('store');
        Route::get('/{book}', [BookManagementController::class, 'show'])->name('show');
        Route::get('/{book}/edit', [BookManagementController::class, 'edit'])->name('edit');
        Route::put('/{book}', [BookManagementController::class, 'update'])->name('update');
        Route::delete('/{book}', [BookManagementController::class, 'destroy'])->name('destroy');
    });

    // Quản lý tác giả
    Route::prefix('authors')->name('authors.')->group(function () {
        Route::get('/', [AuthorController::class, 'index'])->name('index');
        Route::post('/quick-store', [AuthorController::class, 'quickStore'])->name('quick-store');
        Route::post('/', [AuthorController::class, 'store'])->name('store');
        Route::put('/{author}', [AuthorController::class, 'update'])->name('update');
        Route::delete('/{author}', [AuthorController::class, 'destroy'])->name('destroy');
    });

    // Quản lý đơn hàng
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [AdminOrderController::class, 'index'])->name('index');
        Route::get('/{order}', [AdminOrderController::class, 'show'])->name('show');
        Route::put('/{order}', [AdminOrderController::class, 'update'])->name('update');
    });

    // Quản lý phương thức thanh toán
    Route::prefix('payment-methods')->name('payment-methods.')->group(function () {
        Route::get('/', [PaymentMethodController::class, 'index'])->name('index');
        Route::post('/', [PaymentMethodController::class, 'store'])->name('store');
        Route::put('/{paymentMethod}', [PaymentMethodController::class, 'update'])->name('update');
        Route::delete('/{paymentMethod}', [PaymentMethodController::class, 'destroy'])->name('destroy');
    });

    // Quản lý nhà cung cấp
    Route::prefix('suppliers')->name('suppliers.')->group(function () {
        Route::get('/', [SupplierController::class, 'index'])->name('index');
        Route::post('/', [SupplierController::class, 'store'])->name('store');
        Route::put('/{supplier}', [SupplierController::class, 'update'])->name('update');
        Route::delete('/{supplier}', [SupplierController::class, 'destroy'])->name('destroy');
    });

    // Quản lý phiếu nhập hàng
    Route::prefix('stock-imports')->name('stock-imports.')->group(function () {
        Route::get('/', [StockImportController::class, 'index'])->name('index');
        Route::get('/create', [StockImportController::class, 'create'])->name('create');
        Route::post('/', [StockImportController::class, 'store'])->name('store');
        Route::get('/{stockImport}', [StockImportController::class, 'show'])->name('show');
    });

    // Quản lý người dùng
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/{user}', [UserController::class, 'show'])->name('show');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{user}', [UserController::class, 'update'])->name('update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
    });
});
