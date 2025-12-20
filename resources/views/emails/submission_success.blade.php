<!DOCTYPE html>
<html>
<head><title>Submission Success</title></head>
<body>
    <h3>Halo, {{ $loanData['user_name'] }}</h3>
    <p>Permintaan peminjaman dokumen Anda telah berhasil disubmit ke sistem.</p>
    
    <p><strong>Detail:</strong></p>
    <ul>
        <li>Divisi: {{ $loanData['divisi'] }}</li>
        <li>Menunggu Approval dari: {{ $loanData['approver_email'] }}</li>
    </ul>

    <p>Terima kasih,<br>LendCore System</p>
</body>
</html>