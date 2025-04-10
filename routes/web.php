<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\DraftPekerjaanController;
use App\Http\Controllers\TransaksiDraftPekerjaanController;
use App\Http\Controllers\NeracaController;
use App\Http\Controllers\LaporanKeuanganController;
use App\Http\Controllers\ArusKasController;
use App\Http\Controllers\LabaRugiController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public
Route::redirect('/', '/login');

Route::middleware('guest')->group(function () {
    Route::view('/login', 'auth.login')->name('auth.login');
    Route::post('/login', [AuthController::class, 'login'])->name('auth.login.post');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('auth.logout');

// Dashboard
Route::middleware('auth')->get('/dashboard', function () {
    return match(auth()->user()->role) {
        'direktur' => redirect()->route('dashboard.direktur'),
        'admin'    => redirect()->route('dashboard.admin'),
        'akuntan'  => redirect()->route('dashboard.akuntan'),
        'pengawas' => redirect()->route('dashboard.pengawas'),
        default    => abort(403),
    };
})->name('dashboard');

Route::middleware(['auth', 'role:direktur'])->get('/dashboard/direktur', [DashboardController::class, 'direktur'])->name('dashboard.direktur');
Route::middleware(['auth', 'role:admin'])->get('/dashboard/admin', [DashboardController::class, 'admin'])->name('dashboard.admin');
Route::middleware(['auth', 'role:akuntan'])->get('/dashboard/akuntan', [DashboardController::class, 'akuntan'])->name('dashboard.akuntan');
Route::middleware(['auth', 'role:pengawas'])->get('/dashboard/pengawas', [DashboardController::class, 'pengawas'])->name('dashboard.pengawas');

Route::middleware(['auth', 'role:direktur|admin|akuntan|pengawas'])
    ->get('/dashboard/overview', [DashboardController::class, 'overview'])
    ->name('dashboard.overview');


// ========================== Fitur: Karyawan ==========================
Route::prefix('karyawan')->name('karyawan.')->middleware('auth')->group(function () {
    Route::middleware('role:akuntan')->group(function () {
        Route::get('create', [KaryawanController::class, 'create'])->name('create');
        Route::post('/', [KaryawanController::class, 'store'])->name('store');
        Route::get('{karyawan}/edit', [KaryawanController::class, 'edit'])->name('edit');
        Route::put('{karyawan}', [KaryawanController::class, 'update'])->name('update');
        Route::post('{karyawan}', [KaryawanController::class, 'destroy'])->name('destroy');
    });

    Route::middleware('role:direktur|admin|akuntan|pengawas')->group(function () {
        Route::get('/', [KaryawanController::class, 'index'])->name('index');
        Route::get('search', [KaryawanController::class, 'search'])->name('search');
        Route::get('{karyawan}', [KaryawanController::class, 'show'])->name('show');
    });
});

// ========================== Fitur: Draft Pekerjaan ==========================
Route::prefix('draft-pekerjaan')->name('draft-pekerjaan.')->middleware('auth')->group(function () {
    Route::middleware('role:akuntan|pengawas')->group(function () {
        Route::get('create', [DraftPekerjaanController::class, 'create'])->name('create');
        Route::post('store', [DraftPekerjaanController::class, 'store'])->name('store');
        Route::get('{draft_pekerjaan}/edit', [DraftPekerjaanController::class, 'edit'])->name('edit');
        Route::put('{draft_pekerjaan}', [DraftPekerjaanController::class, 'update'])->name('update');
        Route::post('{draft_pekerjaan}', [DraftPekerjaanController::class, 'destroy'])->name('destroy');
        Route::post('update-checkbox/{draft_pekerjaan}', [DraftPekerjaanController::class, 'updateCheckbox'])->name('update-checkbox');
    });

    Route::middleware('role:direktur|admin|akuntan|pengawas')->group(function () {
        Route::get('/', [DraftPekerjaanController::class, 'index'])->name('index');
        Route::get('search', [DraftPekerjaanController::class, 'search'])->name('search');
        Route::get('{draft_pekerjaan}', [DraftPekerjaanController::class, 'show'])->name('show');
    });
});

// ========================== Fitur: Transaksi Draft Pekerjaan ==========================
Route::prefix('transaksi-draft-pekerjaan')->name('transaksi-draft-pekerjaan.')->middleware('auth')->group(function () {
    Route::middleware('role:akuntan')->group(function () {
        Route::get('create', [TransaksiDraftPekerjaanController::class, 'create'])->name('create');
        Route::post('store', [TransaksiDraftPekerjaanController::class, 'store'])->name('store');
        Route::get('{transaksi_draft_pekerjaan}/edit', [TransaksiDraftPekerjaanController::class, 'edit'])->name('edit');
        Route::put('{transaksi_draft_pekerjaan}', [TransaksiDraftPekerjaanController::class, 'update'])->name('update');
        Route::post('{transaksi_draft_pekerjaan}', [TransaksiDraftPekerjaanController::class, 'destroy'])->name('destroy');
    });

    Route::middleware('role:direktur|admin|akuntan|pengawas')->group(function () {
        Route::get('/', [TransaksiDraftPekerjaanController::class, 'index'])->name('index');
        Route::get('search', [TransaksiDraftPekerjaanController::class, 'search'])->name('search');
        Route::get('{transaksi_draft_pekerjaan}', [TransaksiDraftPekerjaanController::class, 'show'])->name('show');
    });
});

// ========================== Fitur: Neraca ==========================
Route::prefix('data-neraca')->name('data-neraca.')->middleware('auth')->group(function () {
    Route::middleware('role:akuntan')->group(function () {
        Route::get('create', [NeracaController::class, 'create'])->name('create');
        Route::post('/', [NeracaController::class, 'store'])->name('store');
        Route::get('{id}/edit', [NeracaController::class, 'edit'])->name('edit');
        Route::put('{id}', [NeracaController::class, 'update'])->name('update');
        Route::post('{id}', [NeracaController::class, 'destroy'])->name('destroy');
        Route::post('update-checkbox/{id}', [NeracaController::class, 'updateCheckbox'])->name('update-checkbox');
    });

    Route::middleware('role:direktur|admin|akuntan|pengawas')->group(function () {
        Route::get('/', [NeracaController::class, 'index'])->name('index');
        Route::get('search', [NeracaController::class, 'search'])->name('search');
        Route::get('{id}', [NeracaController::class, 'show'])->name('show');
    });
});

// ========================== Fitur: Laporan Keuangan ==========================
Route::prefix('laporan-keuangan')->name('laporan-keuangan.')->middleware('auth')->group(function () {
    Route::middleware('role:akuntan')->group(function () {
        Route::get('create', [LaporanKeuanganController::class, 'create'])->name('create');
        Route::post('/', [LaporanKeuanganController::class, 'store'])->name('store');
        Route::get('{id}/edit', [LaporanKeuanganController::class, 'edit'])->name('edit');
        Route::put('{id}', [LaporanKeuanganController::class, 'update'])->name('update');
        Route::post('{id}', [LaporanKeuanganController::class, 'destroy'])->name('destroy');
    });

    Route::middleware('role:direktur')->put('{id}/status', [LaporanKeuanganController::class, 'updateStatus'])->name('update-status');

    Route::middleware('role:direktur|admin|akuntan|pengawas')->group(function () {
        Route::get('/', [LaporanKeuanganController::class, 'index'])->name('index');
        Route::get('search', [LaporanKeuanganController::class, 'search'])->name('search');
        Route::get('{id}', [LaporanKeuanganController::class, 'show'])->name('show');
    });
});

// ========================== Fitur: Arus Kas ==========================
Route::prefix('arus-kas')->name('arus-kas.')->middleware('auth')->group(function () {

    // 👨‍💼 Role: Akuntan - CRUD
    Route::middleware('role:akuntan')->group(function () {
        Route::get('create', [ArusKasController::class, 'create'])->name('create');
        Route::post('/', [ArusKasController::class, 'store'])->name('store');
        Route::get('{id}/edit', [ArusKasController::class, 'edit'])->name('edit');
        Route::put('{id}', [ArusKasController::class, 'update'])->name('update');
        Route::post('{id}', [ArusKasController::class, 'destroy'])->name('destroy');
    });

    // 📊 Role: Semua (yang diizinkan) - View & Search
    Route::middleware('role:akuntan')->group(function () {
        Route::get('/', [ArusKasController::class, 'index'])->name('index');
        Route::get('search', [ArusKasController::class, 'search'])->name('search');
        Route::get('{id}', [ArusKasController::class, 'show'])->name('show');
    });
});

// ========================== Fitur: Laba Rugi ==========================
Route::prefix('laba-rugi')->name('laba-rugi.')->middleware('auth')->group(function () {
    Route::middleware('role:akuntan')->group(function () {
        Route::get('create', [LabaRugiController::class, 'create'])->name('create');
        Route::post('/', [LabaRugiController::class, 'store'])->name('store');
        Route::get('{id}/edit', [LabaRugiController::class, 'edit'])->name('edit');
        Route::put('{id}', [LabaRugiController::class, 'update'])->name('update');
        Route::post('{id}', [LabaRugiController::class, 'destroy'])->name('destroy');
    });

    Route::middleware('role:direktur|admin|akuntan|pengawas')->group(function () {
        Route::get('/', [LabaRugiController::class, 'index'])->name('index');
        Route::get('search', [LabaRugiController::class, 'search'])->name('search');
        Route::get('{id}', [LabaRugiController::class, 'show'])->name('show');
    });
});
