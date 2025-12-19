<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response; 
use App\Models\DocumentLoan;
use App\Mail\SubmissionMail;

class LoanController extends Controller
{
    // Fungsi Simpan Peminjaman (Tetap sama, tidak berubah)
    public function store(Request $request)
    {
        $request->validate([
            'email_superior' => 'required|email',
            'category' => 'required|array',
        ]);

        $categories = $request->category;
        
        foreach($categories as $index => $cat) {
            DocumentLoan::create([
                'user_id' => Auth::id(),
                'entity' => $request->entity,
                'document_category' => $cat, 
                'document_name' => 'Dokumen ' . ($index + 1), 
                'request_purpose' => $request->purpose[$index] ?? '-',
                'return_date' => $request->return_date[$index] ?? null,
                'approver_email' => $request->email_superior,
                'status' => 'Submitted'
            ]);
        }

        $emailData = [
            'user_name' => Auth::user()->name,
            'divisi' => Auth::user()->divisi,
            'category' => $request->category,
        ];

        Mail::to($request->email_superior)->send(new SubmissionMail($emailData));

        return redirect()->back()->with('success', 'Document lending submitted successfully.');
    }

    // --- FUNGSI EXPORT EXCEL (CSV) ---
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

        $columns = ['Tanggal', 'Entity', 'Kategori', 'Tujuan', 'Email Atasan', 'Status', 'Catatan'];

        $callback = function() use($loans, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($loans as $loan) {
                fputcsv($file, [
                    $loan->created_at->format('Y-m-d'),
                    $loan->entity,
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