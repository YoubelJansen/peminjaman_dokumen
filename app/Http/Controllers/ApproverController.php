<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DocumentLoan;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail; 
use App\Mail\CustodyNewTaskMail; 

class ApproverController extends Controller
{
    public function index()
    {
        $userEmail = Auth::user()->email; 
        
        $pendingLoans = DocumentLoan::where('approver_email', $userEmail)
                                    ->where('status', 'Submitted')
                                    ->orderBy('created_at', 'desc')
                                    ->get();

        $historyLoans = DocumentLoan::where('approver_email', $userEmail)
                                    ->whereIn('status', ['Approved', 'Rejected'])
                                    ->orderBy('updated_at', 'desc')
                                    ->get();

        return view('dashboard.approver', compact('pendingLoans', 'historyLoans'));
    }

    public function review($id)
    {
        $loan = DocumentLoan::findOrFail($id);

        // Security check
        if($loan->approver_email !== Auth::user()->email) {
            abort(403, 'Unauthorized action.');
        }

        $requestor = User::find($loan->user_id);

        // --- REVISI: MENAMBAHKAN VARIABEL APPROVER ---
        // Kita ambil data user approver berdasarkan email yang tersimpan di loan
        $approver = User::where('email', $loan->approver_email)->first();

        // Jangan lupa masukkan 'approver' ke dalam compact
        return view('dashboard.approver_review', compact('loan', 'requestor', 'approver'));
    }

    public function update(Request $request, $id)
    {
        $loan = DocumentLoan::findOrFail($id);
        
        $action = $request->input('action'); 
        $reason = $request->input('reason'); 

        if ($action == 'reject') {
            $request->validate(['reason' => 'required'], ['reason.required' => 'Wajib isi alasan.']);
            $loan->status = 'Rejected';
            $loan->rejection_reason = $reason;
            
        } else {
            // --- JIKA KLIK APPROVE ---
            $loan->status = 'Approved'; 
            $loan->rejection_reason = $reason; 

            // --- EKSEKUSI KIRIM EMAIL KE CUSTODY ---
            // Pastikan konfigurasi .env mailer sudah benar
            try {
                Mail::to('renaldval09@gmail.com')->send(new CustodyNewTaskMail($loan));
            } catch (\Exception $e) {
                // Opsional: Log error jika email gagal, tapi proses approve tetap jalan
                // \Log::error('Gagal kirim email: ' . $e->getMessage());
            }
        }

        $loan->save();

        return redirect()->route('dashboard.approver')->with('success', 'Status berhasil diperbarui.');
    }
}