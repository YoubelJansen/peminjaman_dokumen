<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LendCore - Custody Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        body { background-color: #E1E9EF; font-family: 'Inter', sans-serif; }
        .navbar { background-color: #FFF; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
        .brand-text { color: #743A34; font-weight: 700; font-size: 20px; }
        
        /* Card & Table Styling */
        .card-custom { border: none; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); background: white; padding: 20px; }
        .table-custom-header th { background-color: #8F835A !important; color: white !important; font-weight: 600; border: none; padding: 12px; }
        .table-row td { padding: 12px; border-bottom: 1px solid #eee; vertical-align: middle; color: #555; }
        
        /* Button */
        .btn-brown { background-color: #8F835A; color: white; border: none; padding: 5px 15px; border-radius: 5px; font-size: 14px; }
        .btn-brown:hover { background-color: #7a6f4a; color: white; }
    </style>
</head>
<body>

    <nav class="navbar px-4 py-3">
        <div class="container-fluid">
            <span class="brand-text">LendCore</span>
            <div class="d-flex align-items-center gap-3">
                <span class="text-muted small">{{ Auth::user()->name }} (Custody)</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-danger">Log Out</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <h4 class="mb-4" style="color: #4A4228; font-weight: 700;">Document Lending List</h4>

        <div class="card card-custom">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-custom-header">
                        <tr>
                            <th>Date</th>
                            <th>Entity</th>
                            <th>Doc Name</th>
                            <th>Requestor</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($loans as $item)
                        <tr class="table-row">
                            <td>{{ $item->created_at->format('d/m/Y') }}</td>
                            <td>{{ $item->entity }}</td>
                            <td>{{ $item->document_name }}</td>
                            <td>{{ \App\Models\User::find($item->user_id)->name ?? '-' }}</td>
                            <td>
                                @if($item->status == 'Approved')
                                    <span class="badge bg-warning text-dark">Perlu Proses</span>
                                @elseif($item->status == 'Document Ready')
                                    <span class="badge bg-info text-dark">Siap Diambil</span>
                                @elseif($item->status == 'Borrowed')
                                    <span class="badge bg-primary">Dipinjam</span>
                                @elseif($item->status == 'Returned')
                                    <span class="badge bg-success">Selesai</span>
                                @elseif($item->status == 'Not Returned')
                                    <span class="badge bg-danger">Belum Kembali</span>
                                @else
                                    <span class="badge bg-secondary">{{ $item->status }}</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('custody.review', $item->id) }}" class="btn btn-brown">
                                    Process <i class="fas fa-arrow-right ms-1"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                Tidak ada dokumen yang perlu diproses saat ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>