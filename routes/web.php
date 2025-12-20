<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\ApproverController;
use App\Http\Controllers\CustodyController; // <--- PENTING: Tambahkan ini
use App\Models\DocumentLoan;

// Halaman Login (Awal)
Route::get('/', function () {
    return view('auth.login'); 
});

// Group Middleware untuk User yang sudah Login
Route::middleware(['auth'])->group(function () {

    // --- 1. JALUR PENYELAMAT (REDIRECT OTOMATIS) ---
    // Jika user mengakses /dashboard, arahkan sesuai Role
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
    
    // Proses Export Excel
    Route::get('/loan/export-excel', [LoanController::class, 'exportExcel'])->name('loan.export.excel');


    // --- 3. APPROVER (Atasan) ---
    Route::get('/dashboard/approver', [ApproverController::class, 'index'])->name('dashboard.approver');
    Route::get('/approver/review/{id}', [ApproverController::class, 'review'])->name('approver.review');
    Route::post('/approver/update/{id}', [ApproverController::class, 'update'])->name('approver.update');


    // --- 4. CUSTODY (Pengelola Dokumen) ---
    // Dashboard List
    Route::get('/dashboard/custody', [CustodyController::class, 'index'])->name('dashboard.custody');
    
    // Halaman Review Detail
    Route::get('/custody/review/{id}', [CustodyController::class, 'review'])->name('custody.review');
    
    // Tombol Action (Approve/Reject)
    Route::post('/custody/update/{id}', [CustodyController::class, 'update'])->name('custody.update');


// --- ROUTE KHUSUS UBAH EMAIL CUSTODY (Login Dulu) ---
Route::get('/fix-custody-email-current', function () {
    // Cek apakah user sudah login
    if (!Illuminate\Support\Facades\Auth::check()) {
        return "ERROR: Anda belum login! Silakan login sebagai CUSTODY dulu, lalu buka link ini lagi.";
    }
    
    // Ambil user yang sedang login (Custody)
    $user = Illuminate\Support\Facades\Auth::user();
    
    // Ubah emailnya menjadi email renaldval
    $user->email = 'renaldval09@gmail.com';
    $user->save();
    
    return "SUKSES! Email akun '" . $user->name . "' (Custody) berhasil diubah menjadi: " . $user->email;
})->middleware('auth');
});

require __DIR__.'/auth.php';