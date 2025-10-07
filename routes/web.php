<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\WorkSessionController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TrackingController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/



Route::redirect('/', '/login');

Route::middleware('guest')->group(function () {

    Route::view('/login', 'auth.login')->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('auth.login.post');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('auth.logout');

// Dashboard redirect sesuai role
Route::middleware('auth')->get('/dashboard', function () {
    $user = \Illuminate\Support\Facades\Auth::user();
    return match($user->role) {
        'admin'  => redirect()->route('dashboard.admin'),
        'staff'  => redirect()->route('dashboard.staff'),
        'driver' => redirect()->route('dashboard.driver'),
        'guide'  => redirect()->route('dashboard.guide'),
        default  => abort(403),
    };
})->name('dashboard');

// Dashboard tiap role
Route::middleware(['auth', 'role:admin'])->get('/dashboard/admin', [DashboardController::class, 'admin'])->name('dashboard.admin');
Route::middleware(['auth', 'role:staff'])->get('/dashboard/staff', [DashboardController::class, 'staff'])->name('dashboard.staff');
Route::middleware(['auth', 'role:driver'])->get('/dashboard/driver', [DashboardController::class, 'driver'])->name('dashboard.driver');
Route::middleware(['auth', 'role:guide'])->get('/dashboard/guide', [DashboardController::class, 'guide'])->name('dashboard.guide');


// ====================================================================
// Users
// ====================================================================
Route::middleware('auth')->group(function () {

    Route::get('/profile', [UserController::class, 'showProfile'])->name('profile');
    Route::post('/profile', [UserController::class, 'updateProfile'])->name('profile.update');


    Route::middleware('role:admin')->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');

        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');

        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    });
});



// ====================================================================
// Customers
// ====================================================================
Route::prefix('customers')->name('customers.')->middleware('auth')->group(function () {
    Route::middleware('role:admin|staff')->group(function () {
        Route::get('create', [CustomerController::class, 'create'])->name('create');
        Route::post('/', [CustomerController::class, 'store'])->name('store');
        Route::get('{customer}/edit', [CustomerController::class, 'edit'])->name('edit');
        Route::put('{customer}', [CustomerController::class, 'update'])->name('update');
        Route::delete('{customer}', [CustomerController::class, 'destroy'])->name('destroy');
    });

    Route::middleware('role:admin|staff')->group(function () {
        Route::get('/', [CustomerController::class, 'index'])->name('index');
        Route::get('search', [CustomerController::class, 'search'])->name('search');
        Route::get('{customer}', [CustomerController::class, 'show'])->name('show');
    });
});

// ====================================================================
// Vehicles
// ====================================================================
Route::prefix('vehicles')->name('vehicles.')->middleware('auth')->group(function () {
    Route::middleware('role:admin|staff')->group(function () {
        Route::get('create', [VehicleController::class, 'create'])->name('create');
        Route::post('/', [VehicleController::class, 'store'])->name('store');
        Route::get('{vehicle}/edit', [VehicleController::class, 'edit'])->name('edit');
        Route::put('{vehicle}', [VehicleController::class, 'update'])->name('update');
        Route::delete('{vehicle}', [VehicleController::class, 'destroy'])->name('destroy');
    });

    Route::middleware('role:admin|staff|driver|guide')->group(function () {
        Route::get('/', [VehicleController::class, 'index'])->name('index');
        Route::get('{vehicle}', [VehicleController::class, 'show'])->name('show');
    });
});

// ====================================================================
// Orders
// ====================================================================
Route::prefix('orders')->name('orders.')->middleware('auth')->group(function () {
    Route::middleware('role:admin|staff')->group(function () {
        Route::post('/', [OrderController::class, 'store'])->name('store');
        Route::get('create', [OrderController::class, 'create'])->name('create');
        Route::get('{order}/edit', [OrderController::class, 'edit'])->name('edit');
        Route::put('{order}', [OrderController::class, 'update'])->name('update');
        Route::delete('{order}', [OrderController::class, 'destroy'])->name('destroy');
    });

    Route::middleware('role:admin|staff|driver|guide')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::get('search', [OrderController::class, 'search'])->name('search');
        Route::get('{order}', [OrderController::class, 'show'])->name('show');
    });
});

// ====================================================================
// Assignments
// ====================================================================
Route::prefix('assignments')->name('assignments.')->middleware('auth')->group(function () {
    Route::middleware('role:admin|staff')->group(function () {
        Route::get('create', [AssignmentController::class, 'create'])->name('create');
        Route::post('/', [AssignmentController::class, 'store'])->name('store');
        Route::get('{assignment}/edit', [AssignmentController::class, 'edit'])->name('edit');
        Route::put('{assignment}', [AssignmentController::class, 'update'])->name('update');
        Route::delete('{assignment}', [AssignmentController::class, 'destroy'])->name('destroy');
    });

    Route::middleware('role:admin|staff|driver|guide')->group(function () {
        Route::get('/', [AssignmentController::class, 'index'])->name('index');
        Route::get('{assignment}', [AssignmentController::class, 'show'])->name('show');
    });
});

// ====================================================================
// Work Sessions (jam kerja driver/guide)
// ====================================================================
Route::prefix('work-sessions')->name('work-sessions.')->middleware('auth')->group(function () {
    Route::middleware('role:driver|guide')->group(function () {
        Route::get('create', [WorkSessionController::class, 'create'])->name('create');
        Route::post('/', [WorkSessionController::class, 'store'])->name('store');
        Route::get('{work_session}/edit', [WorkSessionController::class, 'edit'])->name('edit');
        Route::put('{work_session}', [WorkSessionController::class, 'update'])->name('update');
        Route::delete('{work_session}', [WorkSessionController::class, 'destroy'])->name('destroy');

        Route::post('/work-sessions/{assignment}/start', [WorkSessionController::class, 'start'])->name('start');
        Route::post('/work-sessions/{workSession}/stop', [WorkSessionController::class, 'stop'])->name('stop');
    });
    Route::middleware('role:admin|staff|driver|guide')->group(function () {
        Route::get('/', [WorkSessionController::class, 'index'])->name('index');
        Route::get('{work_session}', [WorkSessionController::class, 'show'])->name('show');
    });
});

// ====================================================================
// Notifications
// ====================================================================
Route::prefix('notifications')->name('notifications.')->middleware('auth')->group(function () {
    Route::middleware('role:admin|staff|driver|guide')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::get('{notification}', [NotificationController::class, 'show'])->name('show');
        Route::post('{notification}/read', [NotificationController::class, 'markAsRead'])->name('markAsRead');
        Route::post('{notification}/approve', [NotificationController::class, 'approve'])->name('approve');
        Route::post('{notification}/decline', [NotificationController::class, 'decline'])->name('decline');
    });
});

// ====================================================================
// Tracking (driver locations) - untuk real-time tracking
// ====================================================================
Route::prefix('tracking')->name('tracking.')->middleware('auth')->group(function () {
    Route::middleware('role:driver|guide')->group(function () {
        Route::post('/tracking/{workSession}', [TrackingController::class, 'store'])
        ->middleware('auth')
        ->name('tracking.store');

    });
});
