<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AreaController;
use App\Http\Controllers\Admin\VehicleVerificationController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\AuditLogController;
use App\Http\Controllers\Admin\RecentOrdersController;
use App\Http\Controllers\Staff\StaffController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\Api\RiderLocationController;
use App\Http\Controllers\Customer\ProfileController;
use App\Http\Controllers\Customer\OrderController as CustomerOrderController;
use App\Http\Controllers\Rider\RiderController;
use App\Http\Controllers\Customer\PointsController;
use App\Http\Controllers\JobApplicationController;

Route::post('/claim-welcome-points', [App\Http\Controllers\Customer\PointsController::class, 'claimWelcomePoints'])->name('customer.claim.welcome');

// Payment routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/payment/{order}', [App\Http\Controllers\PaymentController::class, 'show'])->name('payment.show');
    Route::post('/payment/{order}/process', [App\Http\Controllers\PaymentController::class, 'process'])->name('payment.process');
    Route::post('/payment/apply-discount', [App\Http\Controllers\DiscountController::class, 'applyDiscount'])->name('payment.apply.discount');
});

// WELCOME PAGE
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');
    
    Route::get('/register', function () {
        return view('auth.register');
    })->name('register');
    
    Route::get('/forgot-password', function () {
        return view('auth.forgot-password');
    })->name('password.request');
});

// Public apply routes
Route::get('/apply', [JobApplicationController::class, 'showForm'])->name('apply.form');
Route::post('/apply', [JobApplicationController::class, 'submit'])->name('apply.submit');

// Authenticated user routes
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Order routes
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/create', [OrderController::class, 'create'])->name('create');
        Route::post('/store', [OrderController::class, 'store'])->name('store');
        Route::get('/{order}', [OrderController::class, 'show'])->name('show');
        
        Route::post('/{order}/send-to-rider', [OrderController::class, 'sendToRider'])->name('send-to-rider');
        Route::post('/{order}/accept', [OrderController::class, 'acceptOrder'])->name('accept');
        Route::post('/{order}/approve-after-accept', [OrderController::class, 'approveAfterAccept'])->name('approve-after-accept');
        
        Route::post('/{order}/pickup', [OrderController::class, 'pickUp'])->name('pickup');
        Route::post('/{order}/deliver', [OrderController::class, 'deliver'])->name('deliver');
        Route::post('/{order}/cancel', [OrderController::class, 'cancel'])->name('cancel');
    });
    
    // Rating routes
    Route::get('/ratings/create/{order}', [RatingController::class, 'create'])->name('ratings.create');
    Route::post('/ratings/store/{order}', [RatingController::class, 'store'])->name('ratings.store');
    Route::get('/ratings/rider/{rider}', [RatingController::class, 'show'])->name('ratings.rider');
    
    // Customer Profile Routes
    Route::get('/profile', [ProfileController::class, 'show'])->name('customer.profile');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('customer.profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('customer.password.update');
    Route::post('/birthday/claim', [ProfileController::class, 'claimBirthday'])->name('customer.birthday.claim');
    
    // Customer Order Routes
    Route::prefix('customer')->name('customer.')->group(function () {
        Route::get('/orders', [CustomerOrderController::class, 'index'])->name('orders');
        Route::get('/orders/{order}', [CustomerOrderController::class, 'show'])->name('order.details');
    });
    
    // Points routes
    Route::prefix('points')->name('customer.points.')->group(function () {
        Route::get('/', [PointsController::class, 'index'])->name('index');
        Route::post('/redeem', [PointsController::class, 'redeem'])->name('redeem');
    });
    
    // Discount routes
    Route::prefix('discounts')->name('customer.discounts.')->group(function () {
        Route::get('/', [PointsController::class, 'myDiscounts'])->name('index');
        Route::post('/apply', [PointsController::class, 'applyDiscount'])->name('apply');
        Route::get('/available', [App\Http\Controllers\Customer\PointsController::class, 'getAvailableDiscounts'])->name('available');
    });
});

// ADMIN ROUTES
Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified'])->group(function () {
    
    Route::resource('users', UserController::class);
    Route::post('/users/{user}/verify-rider', [UserController::class, 'verifyRider'])->name('users.verify-rider');
    
    Route::resource('areas', AreaController::class);
    Route::post('/areas/{area}/assign-staff', [AreaController::class, 'assignStaff'])->name('areas.assign-staff');
    
    Route::get('/vehicles', [VehicleVerificationController::class, 'index'])->name('vehicles.index');
    Route::post('/vehicles/{vehicle}/verify', [VehicleVerificationController::class, 'verify'])->name('vehicles.verify');
    Route::delete('/vehicles/{vehicle}/reject', [VehicleVerificationController::class, 'reject'])->name('vehicles.reject');
    
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/orders', [ReportController::class, 'orders'])->name('reports.orders');
    Route::get('/reports/earnings', [ReportController::class, 'earnings'])->name('reports.earnings');
    
    Route::get('/earnings', [ReportController::class, 'earnings'])->name('earnings');
    
    Route::get('/audit-logs', [AuditLogController::class, 'index'])->name('audit-logs.index');
    Route::get('/audit-logs/{auditLog}', [AuditLogController::class, 'show'])->name('audit-logs.show');
    
    Route::get('/recent-orders', [RecentOrdersController::class, 'index'])->name('recent-orders.index');
    
    // Job Applications
    Route::get('/applications', [JobApplicationController::class, 'index'])->name('applications.index');
    Route::get('/applications/{application}', [JobApplicationController::class, 'show'])->name('applications.show');
    Route::post('/applications/{application}/approve', [JobApplicationController::class, 'approve'])->name('applications.approve');
    Route::post('/applications/{application}/reject', [JobApplicationController::class, 'reject'])->name('applications.reject');
});

// STAFF ROUTES
Route::prefix('staff')->name('staff.')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/pending-orders', [StaffController::class, 'pendingOrders'])->name('pending-orders');
    Route::get('/ready-to-send', [StaffController::class, 'readyToSend'])->name('ready-to-send');
    Route::post('/send-to-rider/{order}', [StaffController::class, 'sendToRider'])->name('send-to-rider');
    Route::get('/accepted-orders', [StaffController::class, 'acceptedOrders'])->name('accepted-orders');
    Route::post('/approve-accepted/{order}', [StaffController::class, 'approveAccepted'])->name('approve-accepted');
    Route::get('/ready-to-assign', [StaffController::class, 'readyToAssign'])->name('ready-to-assign');
    Route::get('/available-riders', [StaffController::class, 'availableRiders'])->name('available-riders');
});

// RIDER ROUTES
Route::prefix('rider')->name('rider.')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [RiderController::class, 'dashboard'])->name('dashboard');
    Route::get('/pending-orders', [RiderController::class, 'pendingOrders'])->name('pending-orders');
    Route::post('/accept-order/{order}', [RiderController::class, 'acceptOrder'])->name('accept-order');
    Route::post('/decline-order/{order}', [RiderController::class, 'declineOrder'])->name('decline-order');
    Route::get('/order-details/{order}', [RiderController::class, 'getOrderDetails'])->name('order-details');
    Route::get('/deliveries', [RiderController::class, 'deliveries'])->name('deliveries');
    Route::get('/history', [RiderController::class, 'history'])->name('history');
    Route::get('/earnings', [RiderController::class, 'earnings'])->name('earnings');
    Route::post('/toggle-availability', [RiderController::class, 'toggleAvailability'])->name('toggle-availability');
    Route::get('/profile', [RiderController::class, 'profile'])->name('profile');
    Route::put('/profile/update', [RiderController::class, 'updateProfile'])->name('profile.update');
});

// API routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/rider/location', [RiderLocationController::class, 'update']);
});

// Custom logout - redirect to welcome page
Route::post('/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout');

// Tracking routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/tracking/{order}', [App\Http\Controllers\TrackingController::class, 'show'])->name('tracking.show');
    Route::get('/api/rider/{rider}/location', [App\Http\Controllers\TrackingController::class, 'getRiderLocation'])->name('tracking.rider.location');
});