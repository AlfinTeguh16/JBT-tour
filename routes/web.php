<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\DraftPekerjaanController;
use App\Http\Controllers\NeracaController;
use App\Http\Controllers\LaporanKeuanganController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public
Route::redirect('/', '/login');

// Authentication
Route::middleware('guest')->group(function () {
    Route::view('/login', 'auth.login')->name('auth.login');
    Route::post('/login', [AuthController::class, 'login'])->name('auth.login.post');
});

// Logout
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('auth.logout');

// Dashboard redirect by role
Route::middleware('auth')->get('/dashboard', function () {
    return match(auth()->user()->role) {
        'direktur' => redirect()->route('dashboard.direktur'),
        'admin'    => redirect()->route('dashboard.admin'),
        'akuntan'  => redirect()->route('dashboard.akuntan'),
        'pengawas' => redirect()->route('dashboard.pengawas'),
        default    => abort(403),
    };
})->name('dashboard');




// Individual dashboard pages
Route::middleware(['auth','role:direktur'])->get('/dashboard/direktur', [DashboardController::class, 'direktur'])->name('dashboard.direktur');
Route::middleware(['auth','role:admin'])->get('/dashboard/admin', [DashboardController::class, 'admin'])->name('dashboard.admin');
Route::middleware(['auth','role:akuntan'])->get('/dashboard/akuntan', [DashboardController::class, 'akuntan'])->name('dashboard.akuntan');
Route::middleware(['auth','role:pengawas'])->get('/dashboard/pengawas', [DashboardController::class, 'pengawas'])->name('dashboard.pengawas');



// Shared overview
Route::middleware(['auth','role:direktur|admin|akuntan|pengawas'])
    ->get('/dashboard/overview', [DashboardController::class, 'overview'])
    ->name('dashboard.overview');

// full CRUD only for akuntan
Route::middleware(['auth','role:akuntan'])->group(function () {
    //karyawan
    Route::get('/karyawan/create', [KaryawanController::class, 'create'])->name('karyawan.create');
    Route::post('/karyawan', [KaryawanController::class, 'store'])->name('karyawan.store');
    Route::get('/karyawan/{karyawan}/edit', [KaryawanController::class, 'edit'])->name('karyawan.edit');
    Route::put('/karyawan/{karyawan}', [KaryawanController::class, 'update'])->name('karyawan.update');
    Route::post('/karyawan/{karyawan}', [KaryawanController::class, 'destroy'])->name('karyawan.destroy');
    
    //draft pekerjaan
    Route::get('/draft-pekerjaan/create', [DraftPekerjaanController::class, 'create'])->name('draft-pekerjaan.create');
    Route::post('/draft-pekerjaan/store', [DraftPekerjaanController::class, 'store'])->name('draft-pekerjaan.store');
    Route::get('/draft-pekerjaan/{draft_pekerjaan}/edit', [DraftPekerjaanController::class, 'edit'])
    ->where('draft_pekerjaan', '[0-9]+')
    ->name('draft-pekerjaan.edit');
    Route::put('/draft-pekerjaan/{karyawan}', [DraftPekerjaanController::class, 'update'])->name('draft-pekerjaan.update');
    Route::post('/draft-pekerjaan/{karyawan}', [DraftPekerjaanController::class, 'destroy'])->name('draft-pekerjaan.destroy');
    Route::post('/draft-pekerjaan/update-checkbox/{id}', [DraftPekerjaanController::class, 'updateCheckbox']);

    //neraca
    Route::get('/data-neraca/create', [NeracaController::class, 'create'])->name('data-neraca.create');
    Route::post('/data-neraca', [NeracaController::class, 'store'])->name('data-neraca.store');
    Route::get('/data-neraca/{id}/edit', [NeracaController::class, 'edit'])->name('data-neraca.edit');
    Route::put('/data-neraca/{id}', [NeracaController::class, 'update'])->name('data-neraca.update');
    Route::post('/data-neraca/{id}', [NeracaController::class, 'destroy'])->name('data-neraca.destroy');
    Route::post('/data-neraca/update-checkbox/{id}', [NeracaController::class, 'updateCheckbox']);

    //laporan keuangan
    Route::get('/laporan-keuangan/create', [LaporanKeuanganController::class, 'create'])->name('laporan-keuangan.create');
    Route::post('/laporan-keuangan', [LaporanKeuanganController::class, 'store'])->name('laporan-keuangan.store');
    Route::get('/laporan-keuangan/{id}/edit', [LaporanKeuanganController::class, 'edit'])->name('laporan-keuangan.edit');
    Route::put('/laporan-keuangan/{id}', [LaporanKeuanganController::class, 'update'])->name('laporan-keuangan.update');
    Route::post('/laporan-keuangan/{id}', [LaporanKeuanganController::class, 'destroy'])->name('laporan-keuangan.destroy');
});

// index & show for all roles
Route::middleware(['auth','role:direktur|admin|akuntan|pengawas'])->group(function () {
    //karyawan
    Route::get('/karyawan', [KaryawanController::class, 'index'])->name('karyawan.index');
    Route::get('/karyawan/search', [KaryawanController::class, 'search'])->name('karyawan.search');
    Route::get('/karyawan/{karyawan}', [KaryawanController::class, 'show'])
    ->where('karyawan', '[0-9]+') // Hanya menangkap angka
    ->name('karyawan.show');
    
    //draft pekerjaan
    Route::get('/draft-pekerjaan', [DraftPekerjaanController::class, 'index'])->name('draft-pekerjaan.index');
    Route::get('/draft-pekerjaan/search', [DraftPekerjaanController::class, 'search'])->name('draft-pekerjaan.search');
    Route::get('/draft-pekerjaan/{draft_pekerjaan}', [DraftPekerjaanController::class, 'show'])
    ->where('draft_pekerjaan', '[0-9]+')
    ->name('draft-pekerjaan.show');

    //neraca
    Route::get('/data-neraca', [NeracaController::class, 'index'])->name('data-neraca.index');
    Route::get('/data-neraca/search', [NeracaController::class, 'search'])->name('data-neraca.search');
    Route::get('/data-neraca/{id}', [NeracaController::class, 'show'])->name('data-neraca.show');
    
    //neraca
    Route::get('/laporan-keuangan', [LaporanKeuanganController::class, 'index'])->name('laporan-keuangan.index');
    Route::get('/laporan-keuangan/search', [LaporanKeuanganController::class, 'search'])->name('laporan-keuangan.search');
    Route::get('/laporan-keuangan/{id}', [LaporanKeuanganController::class, 'show'])->name('laporan-keuangan.show');

});


// full CRUD only for direkrue
Route::middleware(['auth','role:direktur'])->group(function () {
    Route::put('/laporan-keuangan/{id}/status', [LaporanKeuanganController::class, 'updateStatus'])->name('laporan-keuangan.update-status');
});


// full CRUD only for pengawas
Route::middleware(['auth','role:pengawas'])->group(function () {

    //draft pekerjaan
    Route::get('/draft-pekerjaan/create', [DraftPekerjaanController::class, 'create'])->name('draft-pekerjaan.create');
    Route::post('/draft-pekerjaan/store', [DraftPekerjaanController::class, 'store'])->name('draft-pekerjaan.store');
    Route::get('/draft-pekerjaan/{draft_pekerjaan}/edit', [DraftPekerjaanController::class, 'edit'])
    ->where('draft_pekerjaan', '[0-9]+')
    ->name('draft-pekerjaan.edit');
    Route::put('/draft-pekerjaan/{karyawan}', [DraftPekerjaanController::class, 'update'])->name('draft-pekerjaan.update');
    Route::post('/draft-pekerjaan/{karyawan}', [DraftPekerjaanController::class, 'destroy'])->name('draft-pekerjaan.destroy');
    Route::post('/draft-pekerjaan/update-checkbox/{id}', [DraftPekerjaanController::class, 'updateCheckbox']);

});

