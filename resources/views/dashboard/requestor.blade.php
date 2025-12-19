<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LendCore - Requestor Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        body { background-color: #F4F6F8; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .navbar { background-color: #fff; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
        .brand-text { color: #6D4C41; font-weight: bold; font-size: 1.2rem; }
        
        /* Card Styling */
        .card-custom { border: none; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        .card-header-custom { background-color: transparent; border-bottom: 1px solid #eee; color: #6D4C41; font-weight: bold; padding: 15px 20px; }
        
        /* Form Inputs */
        .form-label { font-size: 0.85rem; color: #666; font-weight: 600; }
        .form-control, .form-select { background-color: #F9FAFB; border: 1px solid #E5E7EB; padding: 10px; font-size: 0.9rem; }
        .form-control:focus { border-color: #6D4C41; box-shadow: 0 0 0 0.2rem rgba(109, 76, 65, 0.25); }
        .form-control[readonly] { background-color: #E9ECEF; color: #6c757d; }

        /* Buttons */
        .btn-primary-custom { background-color: #8D6E63; border-color: #8D6E63; color: white; }
        .btn-primary-custom:hover { background-color: #6D4C41; border-color: #6D4C41; }
        
        /* Nav Tabs Custom */
        .nav-pills .nav-link { color: #6D4C41; font-weight: 600; margin-right: 10px; border-radius: 6px; border: 1px solid transparent; }
        .nav-pills .nav-link.active { background-color: #8D6E63; color: white; }
        .nav-pills .nav-link:hover { background-color: #e9ecef; color: #6D4C41; }
        
        /* Table History Header (Coklat) */
        .table-history-header th { background-color: #8D6E63 !important; color: white !important; font-weight: 500; border: none; vertical-align: middle; }
        
        /* Add Document Link */
        .add-doc-link { color: #8D6E63; text-decoration: none; font-weight: 600; cursor: pointer; font-size: 0.9rem; }
        .add-doc-link:hover { text-decoration: underline; color: #5D4037; }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-light px-4 py-3">
        <div class="container-fluid">
            <a class="navbar-brand brand-text" href="#"><i class="fas fa-file-alt me-2"></i>LendCore</a>
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-sm btn-light"><i class="fas fa-bell"></i></button>
                <div class="dropdown">
                    <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        {{ $user->username }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item">Log Out</button>
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
            <strong>Success</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <div class="d-flex align-items-center mb-4">
            <ul class="nav nav-pills" id="pills-tab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="pills-form-tab" data-bs-toggle="pill" data-bs-target="#pills-form" type="button">
                        <i class="fas fa-edit me-2"></i>Document Lending Form
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="pills-list-tab" data-bs-toggle="pill" data-bs-target="#pills-list" type="button">
                        <i class="fas fa-list me-2"></i>Document Lending List
                    </button>
                </li>
            </ul>
        </div>

        <div class="tab-content" id="pills-tabContent">
            
            <div class="tab-pane fade show active" id="pills-form" role="tabpanel">
                <div class="card card-custom p-3">
                    <div class="card-header-custom">
                        Document Lending Form
                    </div>
                    <div class="card-body">
                        <form id="lendingForm" action="{{ route('loan.store') }}" method="POST">
                            @csrf
                            
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label class="form-label">Entity *</label>
                                    <select class="form-select" name="entity">
                                        <option selected disabled>Select Entity...</option>
                                        <option value="Lippo Karawaci">Lippo Karawaci</option>
                                        <option value="Tanjung Bunga">Tanjung Bunga</option>
                                        <option value="Lippo Cikarang">Lippo Cikarang</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Tanggal Permohonan *</label>
                                    <input type="date" class="form-control" value="{{ date('Y-m-d') }}" readonly>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Divisi Pemohon *</label>
                                    <select class="form-select" name="divisi">
                                        <option selected disabled>Select Division...</option>
                                        <option value="Legal" {{ $user->divisi == 'Legal' ? 'selected' : '' }}>Legal</option>
                                        <option value="Finance" {{ $user->divisi == 'Finance' ? 'selected' : '' }}>Finance</option>
                                        <option value="IT Intern" {{ $user->divisi == 'IT Intern' ? 'selected' : '' }}>IT Intern</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Nama Pemohon *</label>
                                    <input type="text" class="form-control" value="{{ $user->name }}" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Email Pemohon *</label>
                                    <input type="email" class="form-control" value="{{ $user->email }}" readonly>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label class="form-label">Username *</label>
                                    <input type="text" class="form-control" value="{{ $user->username }}" readonly style="background-color: #E2E8F0;">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Email Superior *</label>
                                    <select class="form-select" name="email_superior">
                                        <option selected disabled>Select Superior...</option>
                                        <option value="oktaviaalifia5@gmail.com">oktaviaalifia5@gmail.com</option>
                                        <option value="youbeljansen5@gmail.com">youbeljansen5@gmail.com</option>  
                                    </select>
                                </div>
                            </div>

                            <div class="mb-2">
                                <label class="form-label">Selected Document *</label>
                            </div>
                            
                            <div class="table-responsive mb-3">
                                <table class="table" id="documentTable">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th><th>Cert No</th><th>Doc Name</th><th>Company</th>
                                            <th>Jenis Permohonan</th><th>Category</th><th>Purpose</th><th>Return Date</th><th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr id="emptyRow">
                                            <td colspan="9" class="text-danger text-center py-3">There must be at least one document in the request</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="text-center mb-5">
                                <span class="add-doc-link" data-bs-toggle="modal" data-bs-target="#searchModal">+ Add Document</span>
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="button" class="btn btn-primary-custom px-5" onclick="confirmSubmit()">
                                    <i class="fas fa-file-export me-2"></i> Submit
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="pills-list" role="tabpanel">
                <div class="card card-custom p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="fw-bold" style="color: #6D4C41;">Document Lending List</h4>
                        
                        <a href="{{ route('loan.export.excel') }}" class="btn btn-secondary btn-sm" style="background-color: #8D6E63; border:none;">
                            <i class="fas fa-file-excel me-1"></i> Export Excel
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-history-header">
                                <tr>
                                    <th>Entity</th>
                                    <th>Nama Pemohon</th>
                                    <th>Email Pemohon</th>
                                    <th>Divisi</th>
                                    <th>Tgl Permohonan</th>
                                    <th>Email Atasan</th>
                                    <th>Status</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($myLoans as $loan)
                                <tr>
                                    <td>{{ $loan->entity }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td><small>{{ $user->email }}</small></td>
                                    <td>{{ $user->divisi }}</td>
                                    <td>{{ $loan->created_at->format('d/m/Y') }}</td>
                                    <td><small>{{ $loan->approver_email }}</small></td>
                                    <td>
                                        @if($loan->status == 'Submitted')
                                            <span class="badge bg-warning text-dark">Submitted</span>
                                        @elseif($loan->status == 'Approved')
                                            <span class="badge bg-success">Approved</span>
                                        @elseif($loan->status == 'Rejected')
                                            <span class="badge bg-danger">Rejected</span>
                                        @elseif($loan->status == 'Borrowed')
                                            <span class="badge bg-primary">Borrowed</span>
                                        @else
                                            <span class="badge bg-secondary">{{ $loan->status }}</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-link text-secondary p-0" data-bs-toggle="modal" data-bs-target="#detailModal{{ $loan->id }}">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5 text-muted">Belum ada riwayat peminjaman.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div> 
    </div>

    @foreach($myLoans as $loan)
    <div class="modal fade" id="detailModal{{ $loan->id }}" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold" style="color: #6D4C41;">Detail Permohonan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert {{ $loan->status == 'Approved' ? 'alert-success' : ($loan->status == 'Rejected' ? 'alert-danger' : 'alert-warning') }}">
                        <strong>Status Terkini:</strong> {{ $loan->status }}
                    </div>

                    <table class="table table-sm table-borderless">
                        <tr><td width="35%"><strong>Entity</strong></td><td>: {{ $loan->entity }}</td></tr>
                        <tr><td><strong>Kategori</strong></td><td>: {{ $loan->document_category }}</td></tr>
                        <tr><td><strong>Nama Dokumen</strong></td><td>: {{ $loan->document_name }}</td></tr>
                        <tr><td><strong>Tujuan</strong></td><td>: {{ $loan->request_purpose }}</td></tr>
                        <tr><td><strong>Tgl Kembali</strong></td><td>: {{ $loan->return_date ?? '-' }}</td></tr>
                    </table>

                    <hr>
                    <h6 class="fw-bold">Catatan Atasan / Alasan:</h6>
                    <div class="p-3 bg-light rounded text-muted">
                        {{ $loan->rejection_reason ?? 'Tidak ada catatan khusus.' }}
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    @endforeach

    <div class="modal fade" id="searchModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Search Document</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex gap-2 mb-3">
                        <input type="text" class="form-control" placeholder="Search...">
                        <button class="btn btn-outline-secondary px-4">Search</button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th><input type="checkbox"></th>
                                    <th>Cert No</th>
                                    <th>Desa</th>
                                    <th>Doc Name</th>
                                    <th>Company Name</th>
                                </tr>
                            </thead>
                            <tbody id="searchResultTable">
                                <tr><td><input type="checkbox" class="doc-checkbox" value="951|Kelapa Dua|Kemang West 6|PT ADHI UTAMA"></td><td>951</td><td>Kelapa Dua</td><td>Kemang West 6</td><td>PT ADHI UTAMA</td></tr>
                                <tr><td><input type="checkbox" class="doc-checkbox" value="952|Cikarang|Delta Silicon 2|PT LIPPO CIKARANG"></td><td>952</td><td>Cikarang</td><td>Delta Silicon 2</td><td>PT LIPPO CIKARANG</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer border-0 justify-content-between">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary-custom" onclick="addSelectedDocuments()">Select</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="confirmModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body p-4">
                    <h5 class="fw-bold mb-3">Submit</h5>
                    <p class="text-muted">Are you sure the data entered is correct?</p>
                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <button type="button" class="btn btn-link text-danger text-decoration-none fw-bold" data-bs-dismiss="modal">No</button>
                        <button type="button" class="btn btn-primary-custom px-4" onclick="submitRealForm()">Yes</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function addSelectedDocuments() {
            const checkboxes = document.querySelectorAll('.doc-checkbox:checked');
            const tableBody = document.querySelector('#documentTable tbody');
            const emptyRow = document.getElementById('emptyRow');

            if(checkboxes.length > 0) {
                if(emptyRow) emptyRow.style.display = 'none';
                
                checkboxes.forEach((checkbox, index) => {
                    const data = checkbox.value.split('|');
                    const rowCount = tableBody.rows.length;
                    
                    const newRow = `
                        <tr>
                            <td>${rowCount}</td>
                            <td>${data[0]}</td>
                            <td>${data[2]}</td>
                            <td>${data[3]}</td>
                            <td>
                                <select class="form-select form-select-sm" name="jenis_permohonan[]">
                                    <option>Peminjaman</option>
                                    <option>Pengembalian</option>
                                </select>
                            </td>
                            <td>
                                <select class="form-select form-select-sm" name="category[]">
                                    <option>Sertifikat Asli</option>
                                    <option>Fotocopy</option>
                                </select>
                            </td>
                            <td>
                                <select class="form-select form-select-sm" name="purpose[]">
                                    <option>AJB</option>
                                    <option>Validasi</option>
                                </select>
                            </td>
                            <td>
                                <input type="date" class="form-control form-control-sm" name="return_date[]">
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm text-danger" onclick="removeRow(this)"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                    `;
                    tableBody.insertAdjacentHTML('beforeend', newRow);
                });

                var myModalEl = document.getElementById('searchModal');
                var modal = bootstrap.Modal.getInstance(myModalEl);
                modal.hide();

                checkboxes.forEach(chk => chk.checked = false);
            }
        }

        function removeRow(btn) {
            const row = btn.closest('tr');
            row.remove();
            const tableBody = document.querySelector('#documentTable tbody');
            if(tableBody.rows.length <= 1) { 
                document.getElementById('emptyRow').style.display = 'table-row';
            }
        }

        function confirmSubmit() {
            var myModal = new bootstrap.Modal(document.getElementById('confirmModal'));
            myModal.show();
        }

        function submitRealForm() {
            document.getElementById('lendingForm').submit();
        }
    </script>
</body>
</html>