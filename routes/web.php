<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\AuthController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request as HttpRequest;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductAdminController;
use App\Http\Controllers\Admin\OrderAdminController;
use App\Http\Controllers\Admin\MessageAdminController;
use App\Http\Controllers\Auth\EmailVerificationController;
use Illuminate\Support\Facades\Mail;

Route::get('/', [ShopController::class, 'home'])->name('home');
Route::get('/home', function () {
    return redirect()->route('home');
});

// Static pages for navigation

// Shop routes (viewable by everyone, including admins)
Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/product/{slug}', [ProductController::class, 'show'])->name('product.show');

// Auth
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register.show');
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login.show');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    
    // Password Reset (Users Only)
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Email verification
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::post('/email/verify/send', [EmailVerificationController::class, 'send'])->name('verification.send');
Route::get('/email/verify/{token}', [EmailVerificationController::class, 'verify'])->name('verification.verify');

// Handle Laravel's default verification format for backward compatibility
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect()->route('home')->with('success', 'Email verified successfully!');
})->middleware(['auth', 'signed'])->name('verification.verify.default');

// Account
Route::middleware('auth')->group(function () {
    Route::get('/account', [AuthController::class, 'account'])->name('account');
    Route::post('/account', [AuthController::class, 'updateAccount'])->name('account.update');
    Route::post('/account/password', [AuthController::class, 'changePassword'])->name('account.password');
    Route::get('/orders', [AuthController::class, 'orders'])->name('account.orders');
    Route::get('/messages', [AuthController::class, 'messages'])->name('account.messages');
    // Notifications & messaging
    Route::post('/notifications/{id}/read', [\App\Http\Controllers\NotificationsController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::post('/messages/reply', [\App\Http\Controllers\NotificationsController::class, 'userReply'])->name('messages.reply');
});

// Cart (only regular users, not admins)
Route::middleware(['auth','role:user'])->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
    Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
    Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout.show');
    Route::post('/checkout', [CheckoutController::class, 'checkout'])->name('checkout');
    Route::get('/checkout/whatsapp', [CheckoutController::class, 'whatsapp'])->name('checkout.whatsapp');
    Route::get('/checkout/facebook', [CheckoutController::class, 'facebook'])->name('checkout.facebook');
});

// Admin
Route::middleware(['auth','role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', DashboardController::class)->name('dashboard');

    // Users (admins management included)
    Route::resource('users', \App\Http\Controllers\Admin\UserAdminController::class);
    Route::post('users/{user}/message', [\App\Http\Controllers\Admin\UserAdminController::class, 'sendMessage'])->name('users.message');

    // Products management (create/store/index/update)
    Route::resource('products', ProductAdminController::class)->only(['index','create','store','update']);
    
    // Perfume notes management
    Route::post('products/{product}/perfume-notes', [ProductAdminController::class, 'addPerfumeNote'])->name('products.perfume-notes.store');
    Route::patch('perfume-notes/{perfumeNote}', [ProductAdminController::class, 'updatePerfumeNote'])->name('perfume-notes.update');
    Route::delete('perfume-notes/{perfumeNote}', [ProductAdminController::class, 'deletePerfumeNote'])->name('perfume-notes.destroy');

    // Orders management (index/show/update/destroy)
    Route::get('orders', [OrderAdminController::class, 'index'])->name('orders.index');
    Route::get('orders/history', [OrderAdminController::class, 'history'])->name('orders.history');
    Route::get('orders/{order}', [OrderAdminController::class, 'show'])->name('orders.show');
    Route::patch('orders/{order}', [OrderAdminController::class, 'update'])->name('orders.update');
    Route::delete('orders/{order}', [OrderAdminController::class, 'destroy'])->name('orders.destroy');

    // Admin messages (from users to admins)
    Route::get('messages', [MessageAdminController::class, 'index'])->name('messages.index');
    Route::post('messages/{id}/read', [MessageAdminController::class, 'markAsRead'])->name('messages.read');
});

// Quick test mail route (temporary) — protected to admin only
Route::middleware(['auth','role:admin'])->get('/test-mail', function () {
    Mail::raw("Test email from L'essence via Gmail SMTP", function ($message) {
        $message->to('rafi.almahmud.007@gmail.com')->subject('SMTP Test');
    });
    return 'Test mail dispatched (check inbox/spam).';
});
