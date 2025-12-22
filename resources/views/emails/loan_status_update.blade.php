<!DOCTYPE html>
<html>
<head><title>Update Status Peminjaman</title></head>
<body>
    <h3>Halo, {{ $loan->user->name ?? 'Peminjam' }}</h3>
    
    <p>Status permohonan peminjaman dokumen Anda telah diperbarui menjadi:</p>
    
    <h2 style="color: #8F835A; border-bottom: 2px solid #ddd; padding-bottom: 10px;">
        {{ $loan->status }}
    </h2>

    <div style="background: #f4f4f4; padding: 15px; border-radius: 5px; margin: 15px 0;">
        <p><strong>Pesan dari Custody:</strong></p>
        <p style="font-size: 16px; font-weight: 500;">
            "{{ $statusMessage }}"
        </p>
    </div>

    <p><strong>Detail Dokumen:</strong></p>
    <ul>
        <li>Dokumen: {{ $loan->document_name }}</li>
        <li>Kategori: {{ $loan->document_category }}</li>
        <li>Tanggal Update: {{ date('d-m-Y H:i') }}</li>
    </ul>

    <p>Silakan cek dashboard aplikasi LendCore untuk detail lebih lanjut.</p>
    
    <br>
    <p>Terima kasih,<br>Tim Custody LendCore</p>
</body>
</html>