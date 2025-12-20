<!DOCTYPE html>
<html>
<head><title>Permintaan Baru</title></head>
<body>
    <h3>Halo Tim Custody,</h3>
    <p>Ada permohonan peminjaman baru yang telah <strong>DISETUJUI (APPROVED)</strong> oleh Atasan dan menunggu proses penyiapan dokumen.</p>
    
    <div style="background: #f4f4f4; padding: 15px; border-radius: 5px;">
        <p><strong>Detail Permohonan:</strong></p>
        <ul>
            <li><strong>Peminjam:</strong> {{ $loan->user->name ?? 'Nama Tidak Dikenal' }} ({{ $loan->user->divisi ?? '-' }})</li>
            
            <li><strong>Dokumen:</strong> {{ $loan->document_name }}</li>
            <li><strong>Kategori:</strong> {{ $loan->document_category }}</li>
            <li><strong>Tanggal Request:</strong> {{ $loan->created_at->format('d/m/Y') }}</li>
        </ul>
    </div>

    <p>Silakan login ke Dashboard Custody untuk memproses status menjadi <strong>Document Ready</strong>.</p>
    
    <a href="{{ url('/login') }}" style="background-color: #8F835A; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">Login Custody</a>
</body>
</html>