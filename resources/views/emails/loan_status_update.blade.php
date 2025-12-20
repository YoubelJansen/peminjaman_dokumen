<!DOCTYPE html>
<html>
<head>
    <title>Update Status Peminjaman</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; border: 1px solid #ddd; padding: 20px; border-radius: 8px; }
        .header { background-color: #8F835A; color: white; padding: 10px 20px; border-radius: 8px 8px 0 0; }
        .status-box { background-color: #f8f9fa; padding: 15px; border-left: 5px solid #8F835A; margin: 20px 0; }
        .footer { font-size: 12px; color: #777; margin-top: 20px; border-top: 1px solid #eee; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>LendCore Notification</h2>
        </div>
        
        <p>Halo, <strong>{{ $loan->user->name }}</strong></p>
        
        <p>Kami ingin menginformasikan adanya pembaruan status pada permohonan peminjaman dokumen Anda.</p>

        <div class="status-box">
            <p><strong>Dokumen:</strong> {{ $loan->document_name }}</p>
            <p><strong>Status Terbaru:</strong> <span style="color: #8F835A; font-weight:bold;">{{ $loan->status }}</span></p>
            
            @if($customMessage)
                <p><strong>Pesan Tambahan:</strong><br> {{ $customMessage }}</p>
            @endif

            @if($loan->rejection_reason)
                <p><strong>Catatan Petugas:</strong><br> {{ $loan->rejection_reason }}</p>
            @endif
        </div>

        <p>Silakan cek dashboard aplikasi LendCore untuk detail lebih lanjut.</p>

        <div class="footer">
            <p>Email ini dikirim otomatis oleh sistem LendCore. Mohon tidak membalas email ini.</p>
        </div>
    </div>
</body>
</html>