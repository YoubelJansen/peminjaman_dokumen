<!DOCTYPE html>
<html>
<head><title>Dokumen Diterima</title></head>
<body>
    <h3>Halo, {{ $loan->user->name ?? 'Peminjam' }}</h3>
    
    <p>Konfirmasi bahwa dokumen berikut telah diserahkan oleh tim Custody dan statusnya kini menjadi <strong>Borrowed (Sedang Dipinjam)</strong>.</p>
    
    <div style="background: #f4f4f4; padding: 15px; border-radius: 5px; margin: 10px 0;">
        <ul>
            <li><strong>Dokumen:</strong> {{ $loan->document_name }}</li>
            <li><strong>Kategori:</strong> {{ $loan->document_category }}</li>
            <li><strong>Tanggal Terima:</strong> {{ date('d-m-Y') }}</li>
            <li><strong>Wajib Kembali:</strong> {{ $loan->return_date ? \Carbon\Carbon::parse($loan->return_date)->format('d-m-Y') : 'Tidak Ada (Fotocopy)' }}</li>
        </ul>
    </div>

    <p>Mohon jaga dokumen ini dengan baik dan kembalikan tepat waktu (jika asli).</p>
    
    <p>Terima kasih,<br>Tim Custody LendCore</p>
</body>
</html>