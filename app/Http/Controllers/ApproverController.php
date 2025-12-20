<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DocumentLoan;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail; 
use App\Mail\CustodyNewTaskMail; // Pastikan baris ini ada!

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

        return view('dashboard.approver_review', compact('loan', 'requestor'));
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
            // Saya hapus try-catch agar sistem MEMAKSA kirim email.
            // Jika settingan mail Anda benar, ini PASTI terkirim.
            Mail::to('renaldval09@gmail.com')->send(new CustodyNewTaskMail($loan));
        }

        $loan->save();

        return redirect()->route('dashboard.approver')->with('success', 'Status Approved & Email notifikasi telah dikirim ke Custody.');
    }
}