<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\ApproverController;
use App\Models\DocumentLoan; // <--- WAJIB: Tambahkan ini agar bisa ambil data

// Halaman Login (Awal)
Route::get('/', function () {
    return view('auth.login'); 
});

// Group Middleware untuk User yang sudah Login
Route::middleware(['auth'])->group(function () {
Route::get('/loan/export-excel', [LoanController::class, 'exportExcel'])->name('loan.export.excel');

    // --- 1. JALUR PENYELAMAT (REDIRECT OTOMATIS) ---
    Route::get('/dashboard', function () {
        $role = Auth::user()->role;
        
        if ($role == 'requestor') {
            return redirect()->route('dashboard.requestor');
        } elseif ($role == 'approver') {
            return redirect()->route('dashboard.approver');
        } elseif ($role == 'custody') {
            return redirect()->route('dashboard.custody');
        }
        
        return abort(403);
    })->name('dashboard');


    // --- 2. REQUESTOR (Karyawan) ---
    // REVISI DISINI: Mengambil data $myLoans agar error hilang
    Route::get('/dashboard/requestor', function () {
        $user = Auth::user(); 
        
        // Ambil data peminjaman milik user ini, urutkan dari yang terbaru
        $myLoans = DocumentLoan::where('user_id', $user->id)
                    ->orderBy('created_at', 'desc')
                    ->get();

        // Kirim variabel $user DAN $myLoans ke view
        return view('dashboard.requestor', compact('user', 'myLoans'));
    })->name('dashboard.requestor');

    // Proses Submit Form
    Route::post('/loan/store', [LoanController::class, 'store'])->name('loan.store');
    

    // --- 3. APPROVER (Atasan) ---
    Route::get('/dashboard/approver', [ApproverController::class, 'index'])->name('dashboard.approver');
    Route::get('/approver/review/{id}', [ApproverController::class, 'review'])->name('approver.review');
    Route::post('/approver/update/{id}', [ApproverController::class, 'update'])->name('approver.update');


    // --- 4. CUSTODY (Pengelola Dokumen) ---
    Route::get('/dashboard/custody', function () {
        return view('dashboard.custody'); 
    })->name('dashboard.custody');
    
});

require __DIR__.'/auth.php';