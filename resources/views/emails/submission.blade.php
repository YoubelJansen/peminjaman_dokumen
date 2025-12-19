<!DOCTYPE html>
<html>
<head>
    <title>Permohonan Peminjaman Dokumen</title>
</head>
<body style="font-family: Arial, sans-serif; padding: 20px;">

    <h2>Halo, Bapak/Ibu Atasan</h2>
    <p>Ada permohonan peminjaman dokumen baru yang memerlukan persetujuan Anda.</p>

    <table border="0" cellpadding="5">
        <tr>
            <td><strong>Nama Pemohon</strong></td>
            <td>: {{ $data['user_name'] }}</td>
        </tr>
        <tr>
            <td><strong>Divisi</strong></td>
            <td>: {{ $data['divisi'] }}</td>
        </tr>
        <tr>
            <td><strong>Total Dokumen</strong></td>
            <td>: {{ count($data['category']) }} Dokumen</td>
        </tr>
    </table>

    <br>
    <p>Silakan klik tombol di bawah ini untuk melihat detail dan melakukan Approval/Rejection:</p>
    
    <a href="{{ route('login') }}" style="background-color: #6D4C41; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">
        Login untuk Review
    </a>

    <p style="margin-top: 30px; font-size: 12px; color: gray;">Email ini dikirim otomatis oleh sistem LendCore.</p>

</body>
</html>