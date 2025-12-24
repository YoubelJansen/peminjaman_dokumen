<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>LendCore - Document Lending Form</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <style>
        * { box-sizing: border-box; font-family: 'Poppins', 'Inter', sans-serif; }

        body { margin: 0; background: #E1E9EF; }

        /* HEADER */
        .header {
            height: 74px; background: #FDFDFD;
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 32px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .logo { font-size: 20px; font-weight: 700; color: #743A34; }
        .account {
            background: #8F835A; color: #fff;
            padding: 8px 16px; border-radius: 5px;
            font-size: 14px; display: flex; align-items: center; gap: 8px;
        }

        /* MAIN */
        .main {
            background: #fff; padding: 32px;
            min-height: calc(100vh - 74px);
            margin: 24px; border-radius: 5px;
        }
        .page-title {
            font-size: 20px; font-weight: 700;
            color: #743A34; margin-bottom: 16px;
        }
        .divider { border-top: 1px solid #ABABAB; margin-bottom: 24px; }

        /* FORM GRID */
        .form-grid {
            display: grid; grid-template-columns: repeat(2, 1fr);
            gap: 20px 40px; margin-bottom: 30px;
        }
        .form-group label {
            font-size: 14px; color: #A7A3A3;
            font-weight: 600; margin-bottom: 6px; display: block;
        }
        .form-group input {
            width: 100%; height: 50px;
            border-radius: 5px; border: 1px solid #8A8A8A;
            background: #E2ECF4; padding: 0 12px;
            box-shadow: 0px 4px 4px rgba(0,0,0,0.25);
        }

        /* TABLE */
        .table { width: 100%; border-collapse: collapse; }
        .table th {
            text-align: left; font-size: 14px;
            color: #5E5D5D; padding: 12px 6px;
            border-bottom: 1px solid #ABABAB;
        }
        .table td {
            font-size: 14px; color: #747373;
            padding: 12px 6px; vertical-align: top;
        }

        select {
            width: 160px; height: 40px;
            border-radius: 5px; border: 1px solid #8A8A8A;
            background: #F8F9FA; padding: 0 10px;
        }

        /* RECEIVE BUTTON */
        .receive-btn {
            margin-top: 8px;
            width: 160px; height: 40px;
            border-radius: 5px; border: none;
            background: #8F835A; color: white;
            font-weight: 600; cursor: pointer;
            display: none;
        }

        /* ACTION BUTTONS CONTAINER */
        .save-container {
            display: flex; 
            justify-content: flex-end; 
            margin-top: 30px;
            gap: 20px; /* Jarak antar tombol */
        }

        /* APPROVE BUTTON (Default Save) */
        .save-btn {
            width: 313px; height: 47px;
            background: #8F835A; color: white;
            border: none; border-radius: 10px;
            font-size: 18px; font-weight: 600;
            cursor: pointer;
            transition: background 0.3s;
        }
        .save-btn:hover { background: #7a6f4a; }

        /* REJECT BUTTON (New) */
        .reject-btn {
            width: 313px; height: 47px;
            background: #9B0101; /* Merah */
            color: white;
            border: none; border-radius: 10px;
            font-size: 18px; font-weight: 600;
            cursor: pointer;
            transition: background 0.3s;
            /* Default display block, nanti diatur JS */
        }
        .reject-btn:hover { background: #7a0000; }

        /* NOTE */
        .note {
            margin-top: 12px; font-size: 13px;
            color: #5E5D5D; font-style: italic;
        }

        .status-red { color: #9B0101; font-weight: 700; border-color: #9B0101; }
    </style>
</head>

<body>

<div class="header">
    <div class="logo">LendCore</div>
    <div class="account">
        <i class="fas fa-user"></i>
        {{ Auth::user()->name }} (Custody)
    </div>
</div>

<div class="main">

    <div class="page-title">Document Lending Form</div>
    <div class="divider"></div>

    <div class="form-grid">
        <div class="form-group">
            <label>Entity*</label>
            <input type="text" value="{{ $loan->entity }}" readonly>
        </div>
        <div class="form-group">
            <label>Tanggal Permohonan*</label>
            <input type="text" value="{{ $loan->created_at->format('d/m/Y') }}" readonly>
        </div>
        <div class="form-group">
            <label>Nama Pemohon*</label>
            <input type="text" value="{{ $requestor->name }}" readonly>
        </div>
        <div class="form-group">
            <label>Email Pemohon*</label>
            <input type="text" value="{{ $requestor->email }}" readonly>
        </div>
        <div class="form-group">
            <label>Username*</label>
            <input type="text" value="{{ $requestor->username }}" readonly>
        </div>
        <div class="form-group">
            <label>Email Superior*</label>
            <input type="text" value="{{ $loan->approver_email }}" readonly>
        </div>
    </div>

    <form action="{{ route('custody.update', $loan->id) }}" method="POST">
        @csrf

        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Certificate No</th>
                    <th>Document Name</th>
                    <th>Company Name</th>
                    <th>Jenis Permohonan</th>
                    <th>Document Category</th>
                    <th>Request Purpose</th>
                    <th>Return Date</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td>1</td>
                    <td>951</td>
                    <td>{{ $loan->document_name }}</td>
                    <td>PT ADHI UTAMA DINAMIKA</td>
                    <td>Peminjaman</td>
                    <td>{{ $loan->document_category }}</td>
                    <td>{{ $loan->request_purpose }}</td>
                    <td>{{ $loan->return_date ?? '-' }}</td>
                    <td>
                        <select name="status_dropdown"
                                id="statusSelect"
                                onchange="handleStatusChange()"
                                class="{{ $loan->status == 'Not Returned' ? 'status-red' : '' }}">
                            <option value="Booked" {{ $loan->status=='Booked'?'selected':'' }}>Booked</option>
                            <option value="Document Ready" {{ $loan->status=='Document Ready'?'selected':'' }}>Document Ready</option>
                            <option value="Borrowed" {{ $loan->status=='Borrowed'?'selected':'' }}>Borrowed</option>
                            <option value="Returned" {{ $loan->status=='Returned'?'selected':'' }}>Returned</option>
                            <option value="Not Returned" {{ $loan->status=='Not Returned'?'selected':'' }}>Not Returned</option>
                        </select>

                        <button type="button"
                                id="receiveBtn"
                                class="receive-btn"
                                onclick="receiveDocument()">
                            Receive
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="save-container">
            <button type="submit" 
                    id="rejectBtn"
                    name="action" 
                    value="reject" 
                    class="reject-btn" 
                    onclick="return confirm('Apakah Anda yakin ingin menolak (REJECT) dokumen ini?')">
                Reject
            </button>

            <button type="submit" name="action" value="approve" class="save-btn">
                Approve
            </button>
        </div>
    </form>

    <div class="note">
        NOTE: Tombol Receive hanya muncul ketika status Borrowed.
        Setelah klik Receive, status berubah menjadi Returned dan wajib klik Save untuk menyimpan.
    </div>

</div>

<script>
function handleStatusChange() {
    const status = document.getElementById('statusSelect').value;
    const receiveBtn = document.getElementById('receiveBtn');
    const rejectBtn = document.getElementById('rejectBtn'); // Ambil elemen tombol Reject

    // LOGIKA 1: Tombol Receive (Hanya muncul jika Borrowed)
    if (status === 'Borrowed') {
        receiveBtn.style.display = 'block';
    } else {
        receiveBtn.style.display = 'none';
    }

    // LOGIKA 2: Tombol Reject (Hanya muncul jika Booked)
    if (status === 'Booked') {
        rejectBtn.style.display = 'block';
    } else {
        rejectBtn.style.display = 'none';
    }
}

function receiveDocument() {
    alert('Dokumen fisik telah diterima kembali oleh Custody.');
    document.getElementById('statusSelect').value = 'Returned';
    document.getElementById('receiveBtn').style.display = 'none';
    // Setelah diubah jadi Returned, panggil handleStatusChange lagi untuk update tombol Reject/Receive
    handleStatusChange();
}

// Jalankan fungsi saat halaman pertama kali dimuat
document.addEventListener('DOMContentLoaded', handleStatusChange);
</script>

</body>
</html>