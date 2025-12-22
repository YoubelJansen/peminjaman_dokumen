<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DocumentLoan;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\DocumentReceivedMail;   // Email Khusus saat Barang Diterima
use App\Mail\LoanStatusUpdateMail;   // Email Umum (Ready, Returned, Not Returned, Reject)

class CustodyController extends Controller
{
    public function index()
    {
        // Menampilkan list berdasarkan status yang relevan bagi Custody
        $loans = DocumentLoan::whereIn('status', ['Approved', 'Document Ready', 'Borrowed', 'Returned', 'Not Returned', 'Booked'])
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
        $requestor = User::find($loan->user_id); // Ambil data peminjam untuk kirim email

        $action = $request->input('action'); 
        $statusDropdown = $request->input('status_dropdown'); 

        // Variabel untuk Email
        $emailMessage = ''; 
        $emailSubject = '';
        $sendGeneralEmail = false;
        $sendReceiveEmail = false;

        // --- 1. LOGIKA TOMBOL SAVE (Menangani Dropdown) ---
        if ($action == 'save') {
            if ($statusDropdown && $statusDropdown != $loan->status) {
                
                $loan->status = $statusDropdown;
                
                // A. Jika Document Ready
                if ($statusDropdown == 'Document Ready') {
                    $emailSubject = 'Dokumen Siap Diambil - LendCore';
                    $emailMessage = 'Dokumen fisik telah disiapkan oleh tim Custody. Silakan datang ke ruangan untuk pengambilan.';
                    $sendGeneralEmail = true;
                } 
                // B. Jika Returned (Dikembalikan)
                elseif ($statusDropdown == 'Returned') {
                    $loan->return_date = now(); // Catat tanggal kembali aktual
                    $emailSubject = 'Pengembalian Berhasil - LendCore';
                    $emailMessage = 'Terima kasih, dokumen fisik telah kami terima kembali. Status peminjaman selesai.';
                    $sendGeneralEmail = true;
                }
                // C. Jika Not Returned (Belum Kembali/Telat)
                elseif ($statusDropdown == 'Not Returned') {
                    $emailSubject = 'PERINGATAN: Dokumen Belum Kembali - LendCore';
                    $emailMessage = 'Status dokumen Anda ditandai BELUM KEMBALI. Harap segera mengembalikan dokumen atau hubungi Custody.';
                    $sendGeneralEmail = true;
                }

            } else {
                return redirect()->back()->with('info', 'Tidak ada perubahan status.');
            }
        }
        
        // --- 2. LOGIKA TOMBOL RECEIVE (Barang Diambil Peminjam) ---
        elseif ($action == 'receive') {
            $loan->status = 'Borrowed';
            $sendReceiveEmail = true; // Pakai email khusus DocumentReceivedMail
        }

        // --- 3. LOGIKA TOMBOL REJECT ---
        elseif ($action == 'reject') {
            $loan->status = 'Rejected';
            $loan->rejection_reason = $request->input('note');
            
            $emailSubject = 'Permohonan Ditolak - LendCore';
            $emailMessage = 'Mohon maaf, permohonan peminjaman dokumen Anda ditolak. Alasan: ' . $loan->rejection_reason;
            $sendGeneralEmail = true;
        }

        // --- 4. LOGIKA TOMBOL DOCUMENT READY (Tombol Approve Coklat) ---
        elseif ($action == 'approve') {
            $loan->status = 'Document Ready';
            
            $emailSubject = 'Dokumen Siap Diambil - LendCore';
            $emailMessage = 'Permohonan Anda telah diproses. Dokumen fisik siap untuk diambil di ruang Custody.';
            $sendGeneralEmail = true;
        }

        // Simpan Perubahan ke Database
        $loan->save();

        // --- EKSEKUSI PENGIRIMAN EMAIL ---
        if ($requestor && $requestor->email) {
            try {
                // Skenario 1: Email Khusus Receive (Borrowed)
                if ($sendReceiveEmail) {
                    Mail::to($requestor->email)->send(new DocumentReceivedMail($loan));
                } 
                // Skenario 2: Email Umum (Ready, Returned, Not Returned, Reject)
                elseif ($sendGeneralEmail) {
                    Mail::to($requestor->email)->send(new LoanStatusUpdateMail($loan, $emailMessage, $emailSubject));
                }
            } catch (\Exception $e) {
                // Log error jika email gagal, agar app tidak crash
                // \Log::error("Gagal kirim email ke " . $requestor->email . ": " . $e->getMessage());
            }
        }

        return redirect()->route('dashboard.custody')->with('success', 'Status updated to ' . $loan->status . '. Email notification sent.');
    }
}