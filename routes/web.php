<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientPortalController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TechnicianPortalController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\TopologyController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WorkOrderController;
use Illuminate\Support\Facades\Route;

// Guest Routes
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::any('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/switch-role', [AuthController::class, 'switchRole'])->name('switch.role');

// Protected Routes
Route::middleware('auth')->group(function () {
    // 1. Dashboard (Overview & Analytics)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // 2. Data Topologi (Master Data)
    Route::get('/topologi', [TopologyController::class, 'index'])->name('topologi.index');
    Route::post('/topologi', [TopologyController::class, 'store'])->name('topologi.store');
    Route::get('/topologi/{topologyNode}/print', [TopologyController::class, 'printDocument'])->name('topologi.print');
    Route::post('/topologi/{topologyNode}/toggle', [TopologyController::class, 'toggleStatus'])->name('topologi.toggle');
    Route::delete('/topologi/{topologyNode}', [TopologyController::class, 'destroy'])->name('topologi.destroy');

    // 3. Logistik Material (Master Data)
    Route::get('/logistik', [MaterialController::class, 'index'])->name('logistik.index');
    Route::post('/logistik', [MaterialController::class, 'store'])->name('logistik.store');
    Route::post('/logistik/{material}/restock', [MaterialController::class, 'restock'])->name('logistik.restock');
    Route::get('/logistik/outbound/{outbound}/print', [MaterialController::class, 'printOutbound'])->name('logistik.outbound.print');
    Route::get('/logistik/material/{material}/print', [MaterialController::class, 'printMaterial'])->name('logistik.material.print');

    // 4. Keluhan & Tiket (Ticketing System)
    Route::get('/tiket', [TicketController::class, 'index'])->name('tiket.index');
    Route::post('/tiket', [TicketController::class, 'store'])->name('tiket.store');
    Route::get('/tiket/{ticket}/print', [TicketController::class, 'printDocument'])->name('tiket.print');
    Route::post('/tiket/{ticket}/dispatch', [TicketController::class, 'dispatchSpk'])->name('tiket.dispatch');

    // Work Orders Actions
    Route::post('/spk/{workOrder}/resolve', [WorkOrderController::class, 'resolve'])->name('spk.resolve');

    // 5. Laporan (Reports)
    Route::get('/laporan', [ReportController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/export-csv', [ReportController::class, 'exportCsv'])->name('laporan.export');
    Route::get('/laporan/print', [ReportController::class, 'printReport'])->name('laporan.print');

    // 6. Manajemen User (Admin Kantor)
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/print', [UserController::class, 'printDocument'])->name('users.print');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    // Role Portals & Riwayat
    Route::get('/klien/portal', [ClientPortalController::class, 'index'])->name('klien.portal');
    Route::get('/klien/riwayat', [ClientPortalController::class, 'history'])->name('klien.riwayat');
    Route::post('/klien/lapor', [ClientPortalController::class, 'storeComplaint'])->name('klien.lapor');
    Route::get('/klien/tiket/{ticket}/print', [ClientPortalController::class, 'printReceipt'])->name('klien.tiket.print');

    Route::get('/teknisi/portal', [TechnicianPortalController::class, 'index'])->name('teknisi.portal');
    Route::get('/teknisi/riwayat', [TechnicianPortalController::class, 'history'])->name('teknisi.riwayat');
});
