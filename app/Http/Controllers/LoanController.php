<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response; 
use App\Models\DocumentLoan;
use App\Mail\SubmissionMail;        // Email Notifikasi untuk ATASAN
use App\Mail\SubmissionSuccessMail; // Email Konfirmasi untuk KARYAWAN (Requestor)

class LoanController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'email_superior' => 'required|email',
            'category'       => 'required|array',
            'entity'         => 'required',
        ]);

        $categories = $request->category;
        
        // 2. Simpan Data ke Database
        foreach($categories as $index => $cat) {
            // Ambil nama dokumen jika ada inputnya, jika tidak pakai default
            $docName = isset($request->doc_name[$index]) ? $request->doc_name[$index] : ('Dokumen ' . ($index + 1));

            DocumentLoan::create([
                'user_id'           => Auth::id(),
                'entity'            => $request->entity,
                'document_category' => $cat, 
                'document_name'     => $docName, 
                'request_purpose'   => $request->purpose[$index] ?? '-',
                'return_date'       => $request->return_date[$index] ?? null,
                'approver_email'    => $request->email_superior,
                'status'            => 'Submitted'
            ]);
        }

        // 3. Siapkan Data untuk Email
        $emailData = [
            'user_name'      => Auth::user()->name,
            'divisi'         => Auth::user()->divisi,
            'category'       => $request->category,
            'approver_email' => $request->email_superior,
        ];

        // --- REVISI PENGIRIMAN EMAIL ---

        // A. Kirim Email ke KARYAWAN (Anda yang sedang login)
        // Pesannya: "Kamu telah submit permintaan..."
        if (Auth::user()->email) {
            try {
                Mail::to(Auth::user()->email)->send(new SubmissionSuccessMail($emailData));
            } catch (\Exception $e) {
                // Log error jika perlu
            }
        }

        // B. Kirim Email ke ATASAN (Superior)
        // Pesannya: "Halo Atasan, ada permintaan baru..."
        if ($request->email_superior) {
            try {
                Mail::to($request->email_superior)->send(new SubmissionMail($emailData));
            } catch (\Exception $e) {
                // Log error jika perlu
            }
        }

        return redirect()->back()->with('success', 'Permintaan peminjaman berhasil dikirim. Cek email Anda untuk konfirmasi.');
    }

    // --- FUNGSI EXPORT EXCEL (Tidak berubah) ---
    public function exportExcel()
    {
        $userId = Auth::id();
        $loans = DocumentLoan::where('user_id', $userId)->orderBy('created_at', 'desc')->get();
        $csvFileName = 'lending_history_' . date('Y-m-d_H-i') . '.csv';

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$csvFileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['Tanggal', 'Entity', 'Nama Dokumen', 'Kategori', 'Tujuan', 'Email Atasan', 'Status', 'Catatan'];

        $callback = function() use($loans, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($loans as $loan) {
                fputcsv($file, [
                    $loan->created_at->format('Y-m-d'),
                    $loan->entity,
                    $loan->document_name,
                    $loan->document_category,
                    $loan->request_purpose,
                    $loan->approver_email,
                    $loan->status,
                    $loan->rejection_reason
                ]);
            }
            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }
}