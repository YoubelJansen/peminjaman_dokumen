<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DocumentLoan;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\LoanStatusUpdateMail; // Email Umum (Save/Approve/Reject)
use App\Mail\DocumentReceivedMail; // Email Khusus (Receive)

class CustodyController extends Controller
{
    public function index()
    {
        $loans = DocumentLoan::whereIn('status', ['Approved', 'Document Ready', 'Borrowed', 'Returned', 'Not Returned'])
                            ->orderBy('updated_at', 'desc')
                            ->get();

        return view('dashboard.custody', compact('loans'));
    }

    public function review($id)
    {
        $loan = DocumentLoan::findOrFail($id);
        $requestor = User::find($loan->user_id);
        $approver = User::where('email', $loan->approver_email)->first();

        return view('dashboard.custody_review', compact('loan', 'requestor', 'approver'));
    }

    public function update(Request $request, $id)
    {
        $loan = DocumentLoan::findOrFail($id);
        $action = $request->input('action'); 
        $statusDropdown = $request->input('status_dropdown'); 

        $emailMessage = '';
        $isReceiveAction = false; // Penanda khusus untuk aksi Receive

        // 1. TOMBOL SAVE (Menangani perubahan dari Dropdown)
        if ($action == 'save') {
            // Cek apakah status di dropdown berbeda dengan database
            if ($statusDropdown && $statusDropdown != $loan->status) {
                
                $loan->status = $statusDropdown;
                
                // --- LOGIKA STATUS DROPDOWN ---
                
                // Jika diubah jadi Document Ready
                if ($statusDropdown == 'Document Ready') {
                    $emailMessage = 'Dokumen fisik telah disiapkan. Silakan datang ke ruangan Custody.';
                } 
                // Jika diubah jadi Returned (Dikembalikan)
                elseif ($statusDropdown == 'Returned') {
                    $emailMessage = 'Dokumen telah dikembalikan. Terima kasih.';
                    $loan->return_date = now(); // Simpan tanggal pengembalian aktual
                }
                // Jika diubah jadi Not Returned (Belum/Tidak Kembali)
                elseif ($statusDropdown == 'Not Returned') {
                    $emailMessage = 'Status dokumen ditandai BELUM KEMBALI (Not Returned). Harap hubungi Custody.';
                }

            } else {
                return redirect()->back()->with('info', 'Tidak ada perubahan status.');
            }
        }
        
        // 2. TOMBOL RECEIVE (Barang diambil user)
        elseif ($action == 'receive') {
            $loan->status = 'Borrowed';
            $isReceiveAction = true; // Set penanda true agar kirim email khusus
        }

        // 3. REJECT & APPROVE (Untuk tahap awal Approval)
        elseif ($action == 'reject') {
            $loan->status = 'Rejected';
            $loan->rejection_reason = $request->input('note');
            $emailMessage = 'Permohonan ditolak oleh Custody. Cek catatan untuk detailnya.';
        }
        elseif ($action == 'approve') {
            $loan->status = 'Document Ready';
            $emailMessage = 'Permohonan disetujui Custody. Status: Document Ready.';
        }

        // Simpan Perubahan ke Database
        $loan->save();

        // --- LOGIKA PENGIRIMAN EMAIL ---
        $requestor = User::find($loan->user_id);
        
        if ($requestor && $requestor->email) {
            try {
                if ($isReceiveAction) {
                    // A. Jika Action RECEIVE -> Kirim Email Khusus "DocumentReceivedMail"
                    Mail::to($requestor->email)->send(new DocumentReceivedMail($loan));
                } 
                elseif (!empty($emailMessage)) {
                    // B. Jika Action LAINNYA & ada pesan -> Kirim Email Umum "LoanStatusUpdateMail"
                    Mail::to($requestor->email)->send(new LoanStatusUpdateMail($loan, $emailMessage));
                }
            } catch (\Exception $e) {
                // Log error email jika perlu
            }
        }

        return redirect()->route('dashboard.custody')->with('success', 'Status berhasil diperbarui menjadi ' . $loan->status);
    }
}