<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DocumentLoan;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ApproverController extends Controller
{
    public function index()
    {
        // Mendapatkan email user yang sedang login (Atasan)
        $userEmail = Auth::user()->email; 
        
        // 1. Ambil Permohonan yang statusnya 'Submitted' DAN email approver-nya cocok
        $pendingLoans = DocumentLoan::where('approver_email', $userEmail)
                                    ->where('status', 'Submitted')
                                    ->orderBy('created_at', 'desc')
                                    ->get();

        // 2. Ambil Riwayat (Approved/Rejected) untuk history
        $historyLoans = DocumentLoan::where('approver_email', $userEmail)
                                    ->whereIn('status', ['Approved', 'Rejected'])
                                    ->orderBy('updated_at', 'desc')
                                    ->get();

        return view('dashboard.approver', compact('pendingLoans', 'historyLoans'));
    }

    public function review($id)
    {
        $loan = DocumentLoan::findOrFail($id);

        // Keamanan: Cek apakah user ini benar-benar atasan dari dokumen tsb
        if($loan->approver_email !== Auth::user()->email) {
            abort(403, 'Unauthorized action.');
        }

        $requestor = User::find($loan->user_id);

        return view('dashboard.approver_review', compact('loan', 'requestor'));
    }

    public function update(Request $request, $id)
    {
        $loan = DocumentLoan::findOrFail($id);
        
        $action = $request->input('action'); 
        $reason = $request->input('reason');

        if ($action == 'reject') {
            $request->validate(['reason' => 'required'], ['reason.required' => 'Alasan penolakan wajib diisi.']);
            $loan->status = 'Rejected';
            $loan->rejection_reason = $reason;
        } else {
            $loan->status = 'Approved'; 
            $loan->rejection_reason = $reason; 
        }

        $loan->save();

        return redirect()->route('dashboard.approver')->with('success', 'Status permohonan berhasil diperbarui menjadi ' . $loan->status);
    }
}