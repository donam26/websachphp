<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\BookManagementController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\VNPayController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DiscountController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\LocationController;
use App\Http\Controllers\Admin\ProductvpController;
use App\Http\Controllers\CustomerController;

// Route cho khách

// Route xác thực
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Route cho sách
Route::prefix('books')->group(function () {
    Route::get('/', [BookController::class, 'index'])->name('books.index');
    Route::get('/{book}', [BookController::class, 'show'])->name('books.show');
    Route::get('/category/{slug}', [BookController::class, 'category'])->name('books.category');
});

// Route cho user đã đăng nhập
Route::middleware('auth')->group(function () {

    Route::get('/', [HomeController::class, 'index'])->name('home');


    // Thông tin cá nhân
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

// Route cho admin
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Quản lý danh mục
    Route::prefix('categories')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('categories.index');
        Route::post('/', [CategoryController::class, 'store'])->name('categories.store');
        Route::get('/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
        Route::put('/{category}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
    });

    // Quản lý sách
    Route::prefix('books')->group(function () {
        Route::get('/', [BookManagementController::class, 'index'])->name('books.index');
        Route::get('/create', [BookManagementController::class, 'create'])->name('books.create');
        Route::post('/', [BookManagementController::class, 'store'])->name('books.store');
        Route::get('/{book}/edit', [BookManagementController::class, 'edit'])->name('books.edit');
        Route::put('/{book}', [BookManagementController::class, 'update'])->name('books.update');
        Route::delete('/{book}', [BookManagementController::class, 'destroy'])->name('books.destroy');
    });

    // Quản lý đơn hàng
    Route::prefix('orders')->group(function () {
        Route::get('/', [AdminOrderController::class, 'index'])->name('orders.index');
        Route::get('/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
        Route::put('/{order}', [AdminOrderController::class, 'update'])->name('orders.update');
    });

    // Quản lý người dùng
    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('users.index');
        Route::post('/', [UserController::class, 'store'])->name('users.store');
        Route::get('/{user}', [UserController::class, 'show'])->name('users.show');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    });

    // Quản lý voucher
    Route::resource('discounts', DiscountController::class);

    Route::resource('employees', EmployeeController::class);

    Route::resource('products', ProductController::class);
    Route::delete('products/images/{id}', [ProductController::class, 'deleteImage'])->name('products.deleteImage');
    Route::resource('productsvp', ProductvpController::class);
    Route::resource('customer', CustomerController::class);

    // Routes cho địa chỉ
    Route::get('/provinces', [LocationController::class, 'getProvinces'])->name('provinces.index');
    Route::get('/districts/{province}', [LocationController::class, 'getDistricts'])->name('districts.index');
    Route::get('/wards/{district}', [LocationController::class, 'getWards'])->name('wards.index');
});

Route::get('/products/{id}', [App\Http\Controllers\HomeController::class, 'show'])->name('products.show');
