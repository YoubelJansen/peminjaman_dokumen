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
        $loans = DocumentLoan::whereIn('status', ['Approved', 'Document Ready', 'Borrowed', 'Returned', 'Not Returned', 'Booked', 'Rejected'])
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

        // --- 1. LOGIKA TOMBOL REJECT (BARU) ---
        if ($action == 'reject') {
            $loan->status = 'Rejected';
            // Mengambil alasan jika ada input 'note', jika tidak default text
            $loan->rejection_reason = $request->input('note') ?? 'Ditolak oleh Custody saat verifikasi fisik.';
            
            $emailSubject = 'Permohonan Ditolak - LendCore';
            $emailMessage = 'Mohon maaf, permohonan peminjaman dokumen Anda ditolak oleh Custody.';
            if($loan->rejection_reason) {
                $emailMessage .= ' Alasan: ' . $loan->rejection_reason;
            }
            $sendGeneralEmail = true;
        }

        // --- 2. LOGIKA TOMBOL APPROVE / SAVE ---
        // Menangani tombol "Approve" (yang juga berfungsi sebagai Save perubahan dropdown)
        elseif ($action == 'approve' || $action == 'save') {
            
            // Cek apakah Dropdown dipilih dan berbeda dari status database?
            if ($statusDropdown && $statusDropdown != $loan->status) {
                $loan->status = $statusDropdown;

                // A. Jika Status diubah jadi Document Ready
                if ($statusDropdown == 'Document Ready') {
                    $emailSubject = 'Dokumen Siap Diambil - LendCore';
                    $emailMessage = 'Dokumen fisik telah disiapkan oleh tim Custody. Silakan datang ke ruangan untuk pengambilan.';
                    $sendGeneralEmail = true;
                } 
                // B. Jika Status diubah jadi Returned (Dikembalikan)
                elseif ($statusDropdown == 'Returned') {
                    $loan->return_date = now(); // Catat tanggal kembali aktual
                    $emailSubject = 'Pengembalian Berhasil - LendCore';
                    $emailMessage = 'Terima kasih, dokumen fisik telah kami terima kembali. Status peminjaman selesai.';
                    $sendGeneralEmail = true;
                }
                // C. Jika Status diubah jadi Not Returned (Telat)
                elseif ($statusDropdown == 'Not Returned') {
                    $emailSubject = 'PERINGATAN: Dokumen Belum Kembali - LendCore';
                    $emailMessage = 'Status dokumen Anda ditandai BELUM KEMBALI. Harap segera mengembalikan dokumen atau hubungi Custody.';
                    $sendGeneralEmail = true;
                }
            } 
            // Jika Dropdown TIDAK berubah, tapi tombol Approve ditekan
            // (Biasanya untuk Approval pertama kali dari Booked -> Document Ready)
            elseif ($loan->status == 'Booked' || $loan->status == 'Approved') {
                $loan->status = 'Document Ready';
                
                $emailSubject = 'Dokumen Siap Diambil - LendCore';
                $emailMessage = 'Permohonan Anda telah disetujui dan diproses. Dokumen fisik siap untuk diambil di ruang Custody.';
                $sendGeneralEmail = true;
            }
        }
        
        // --- 3. LOGIKA TOMBOL RECEIVE (Barang Diambil Peminjam) ---
        elseif ($action == 'receive') {
            $loan->status = 'Borrowed';
            $sendReceiveEmail = true; // Pakai email khusus DocumentReceivedMail
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
                // Log error agar app tidak crash jika mail server bermasalah
                // \Log::error("Gagal kirim email: " . $e->getMessage());
            }
        }

        // Redirect kembali dengan pesan sukses
        if ($loan->status == 'Rejected') {
            return redirect()->route('dashboard.custody')->with('error', 'Dokumen telah ditolak (Rejected).');
        }

        return redirect()->route('dashboard.custody')->with('success', 'Status berhasil diperbarui menjadi ' . $loan->status);
    }
}