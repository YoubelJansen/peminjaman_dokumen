<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LendCore - Approver Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        body { background-color: #F4F6F8; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .navbar { background-color: #fff; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
        .brand-text { color: #6D4C41; font-weight: bold; font-size: 1.2rem; }
        
        /* Card & Table Styling */
        .card-custom { border: none; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        .card-header-custom { background-color: transparent; border-bottom: 1px solid #eee; color: #6D4C41; font-weight: bold; padding: 15px 20px; }
        
        .btn-primary-custom { background-color: #8D6E63; border-color: #8D6E63; color: white; }
        .btn-primary-custom:hover { background-color: #6D4C41; border-color: #6D4C41; }
        
        .badge-status-submitted { background-color: #FFF3CD; color: #856404; }
        .badge-status-approved { background-color: #D1E7DD; color: #0F5132; }
        .badge-status-rejected { background-color: #F8D7DA; color: #721C24; }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-light px-4 py-3">
        <div class="container-fluid">
            <a class="navbar-brand brand-text" href="#"><i class="fas fa-file-alt me-2"></i>LendCore</a>
            <div class="d-flex align-items-center gap-3">
                <div class="text-end me-2 d-none d-sm-block">
                    <span class="d-block fw-bold text-dark">{{ Auth::user()->name }}</span>
                    <span class="d-block text-muted small" style="font-size: 0.8rem;">Approver (Atasan)</span>
                </div>
                <div class="dropdown">
                    <button class="btn btn-sm btn-light rounded-circle" type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle fa-2x text-secondary"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger"><i class="fas fa-sign-out-alt me-2"></i>Log Out</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mt-4 mb-5">
        
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <div class="card card-custom mb-5">
            <div class="card-header-custom">
                <i class="fas fa-inbox me-2"></i> Incoming Requests
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Date</th>
                                <th>Doc Name</th>
                                <th>Requestor</th>
                                <th>Category</th>
                                <th>Purpose</th>
                                <th>Status</th>
                                <th class="text-end pe-4">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pendingLoans as $loan)
                            <tr>
                                <td class="ps-4">{{ $loan->created_at->format('d/m/Y') }}</td>
                                <td class="fw-bold text-dark">{{ $loan->document_name }}</td>
                                <td>
                                    {{ \App\Models\User::find($loan->user_id)->name ?? 'Unknown' }}
                                    <br><small class="text-muted">{{ \App\Models\User::find($loan->user_id)->divisi ?? '-' }}</small>
                                </td>
                                <td>{{ $loan->document_category }}</td>
                                <td>{{ $loan->request_purpose }}</td>
                                <td><span class="badge badge-status-submitted">Submitted</span></td>
                                <td class="text-end pe-4">
                                    <a href="{{ route('approver.review', $loan->id) }}" class="btn btn-sm btn-primary-custom px-3">
                                        Review <i class="fas fa-arrow-right ms-1"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="fas fa-clipboard-check fa-3x mb-3 text-secondary opacity-25"></i>
                                    <p>Tidak ada permohonan baru yang perlu diproses.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card card-custom">
            <div class="card-header-custom text-secondary">
                <i class="fas fa-history me-2"></i> Approval History
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Processed Date</th>
                                <th>Doc Name</th>
                                <th>Requestor</th>
                                <th>Status</th>
                                <th>Notes/Reason</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($historyLoans as $history)
                            <tr>
                                <td class="ps-4">{{ $history->updated_at->format('d/m/Y H:i') }}</td>
                                <td>{{ $history->document_name }}</td>
                                <td>{{ \App\Models\User::find($history->user_id)->name ?? '-' }}</td>
                                <td>
                                    @if($history->status == 'Approved')
                                        <span class="badge badge-status-approved">Approved</span>
                                    @else
                                        <span class="badge badge-status-rejected">Rejected</span>
                                    @endif
                                </td>
                                <td class="text-muted fst-italic">{{ $history->rejection_reason ?? '-' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted small">
                                    Belum ada riwayat persetujuan.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>