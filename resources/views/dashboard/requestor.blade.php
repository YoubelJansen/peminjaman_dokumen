<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LendCore - Requestor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    
    <style>
        /* --- STYLE SESUAI FIGMA --- */
        body { 
            background-color: #E1E9EF; 
            font-family: 'Inter', sans-serif; 
        }

        /* Navbar */
        .top-bar {
            background: #FDFDFD;
            height: 74px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            padding: 0 34px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .brand-text {
            color: #743A34;
            font-size: 20px;
            font-weight: 700;
        }

        /* Dropdown Navigasi (Pengganti Judul Statis) */
        .nav-dropdown-btn {
            background-color: #8F835A;
            color: white;
            border-radius: 5px;
            padding: 10px 20px;
            font-weight: 700;
            border: none;
            display: flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);
            font-family: 'Inter', sans-serif;
            font-size: 16px;
        }
        .nav-dropdown-btn:hover, .nav-dropdown-btn:focus {
            background-color: #7a6f4a;
            color: white;
        }

        /* Main Card */
        .main-card {
            background: white;
            border-radius: 5px;
            padding: 40px;
            margin-top: 30px;
            min-height: 800px;
            position: relative;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        /* Form Styling */
        .form-label-custom {
            color: #A7A3A3;
            font-size: 15px;
            font-weight: 600;
            font-family: 'Poppins', sans-serif;
            margin-bottom: 8px;
        }
        .form-control-custom, .form-select-custom {
            background-color: #F8F9FA;
            border: 1px solid #8A8A8A;
            box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);
            border-radius: 5px;
            height: 50px;
            color: #333;
        }
        .input-readonly {
            background-color: #E1E9EF; /* Warna abu untuk readonly */
        }

        /* Button Submit */
        .btn-submit-custom {
            background-color: #8F835A;
            color: white;
            border-radius: 10px;
            padding: 10px 40px;
            font-weight: 600;
            font-family: 'Poppins', sans-serif;
            border: none;
            box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);
        }

        /* Table Styles */
        .table-custom thead th {
            background-color: #8F835A;
            color: white;
            font-family: 'Inter', sans-serif;
            font-weight: 700;
            font-size: 15px;
            padding: 15px;
            border: none;
            vertical-align: top;
        }
        .table-custom tbody td {
            font-family: 'Poppins', sans-serif;
            font-size: 13px;
            font-weight: 600;
            color: #747373;
            padding: 15px;
            vertical-align: top; /* Align top agar input other terlihat rapi */
            border-bottom: 1px solid #ABABAB;
        }

        /* Status Badges */
        .status-badge { padding: 5px 10px; border-radius: 5px; font-size: 11px; font-weight: 600; }
        .status-submitted { background-color: #FFF3CD; color: #856404; }
        .status-approved { background-color: #D1E7DD; color: #0F5132; }
        .status-rejected { background-color: #F8D7DA; color: #721C24; }
        .status-borrowed { background-color: #CFE2FF; color: #084298; }

        .add-doc-link { color: #8F835A; font-family: 'Poppins', sans-serif; font-weight: 600; cursor: pointer; text-decoration: none; }
        
        /* Helper Class untuk Other Input */
        .other-input-wrapper {
            margin-top: 8px;
        }
        .other-input {
            border: 1px solid #dc3545; /* Merah sesuai request 'Required' look */
            border-radius: 4px;
            padding: 5px;
            width: 100%;
            font-size: 12px;
        }
        .required-text {
            color: red; font-size: 10px; margin-top: 2px; display: block;
        }
    </style>
</head>
<body>

    <div class="top-bar">
        <div class="brand-text">LendCore</div>
        <div class="d-flex align-items-center gap-3">
            <span style="color: #5E5433; font-size: 15px; font-family: 'Poppins', sans-serif;">
                {{ Auth::user()->name }}
            </span>
            <div style="width: 37px; height: 33px; background: #8F835A; border-radius: 5px; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-user text-white"></i>
            </div>
            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-sm text-decoration-none" style="color: #743A34; font-weight:bold;">
                    <i class="fas fa-sign-out-alt"></i>
                </button>
            </form>
        </div>
    </div>

    <div class="container pb-5">
        
        @if(session('success'))
        <div class="alert alert-success mt-3 alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <div class="main-card">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="dropdown">
                    <button class="nav-dropdown-btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-bars me-2"></i> <span id="pageTitle">Document Lending Form</span>
                    </button>
                    <ul class="dropdown-menu shadow border-0 p-2">
                        <li><a class="dropdown-item py-2 fw-bold rounded" href="#" onclick="showSection('input')"><i class="fas fa-edit me-2"></i> Document Lending Form</a></li>
                        <li><a class="dropdown-item py-2 fw-bold rounded" href="#" onclick="showSection('list')"><i class="fas fa-history me-2"></i> Document Lending List</a></li>
                    </ul>
                </div>

                <a href="{{ route('loan.export.excel') }}" id="exportBtn" class="btn btn-submit-custom d-none" style="padding: 8px 20px; font-size: 14px;">
                    <i class="fas fa-file-excel me-2"></i> Export Excel
                </a>
            </div>

            <div id="section-input">
                <form id="lendingForm" action="{{ route('loan.store') }}" method="POST">
                    @csrf
                    
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label class="form-label-custom">Entity <span class="text-danger">*</span></label>
                            <select class="form-select form-select-custom" name="entity" required>
                                <option selected disabled value="">Select Entity...</option>
                                <option value="Lippo Karawaci">Lippo Karawaci</option>
                                <option value="Tanjung Bunga">Tanjung Bunga</option>
                                <option value="Lippo Cikarang">Lippo Cikarang</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label-custom">Tanggal Permohonan <span class="text-danger">*</span></label>
                            <input type="date" class="form-control form-control-custom" value="{{ date('Y-m-d') }}" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label-custom">Divisi Pemohon <span class="text-danger">*</span></label>
                            <select class="form-select form-select-custom" name="divisi" required>
                                <option selected disabled value="">Select Division...</option>
                                <option value="Legal" {{ Auth::user()->divisi == 'Legal' ? 'selected' : '' }}>Legal</option>
                                <option value="Finance" {{ Auth::user()->divisi == 'Finance' ? 'selected' : '' }}>Finance</option>
                                <option value="IT Intern" {{ Auth::user()->divisi == 'IT Intern' ? 'selected' : '' }}>IT Intern</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label-custom">Nama Pemohon <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-custom" value="{{ Auth::user()->name }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Email Pemohon <span class="text-danger">*</span></label>
                            <input type="email" class="form-control form-control-custom" value="{{ Auth::user()->email }}" readonly>
                        </div>
                    </div>

                    <div class="row mb-5">
                        <div class="col-md-6">
                            <label class="form-label-custom">Username <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-custom input-readonly" value="{{ Auth::user()->username }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Email Superior <span class="text-danger">*</span></label>
                            <select class="form-select form-select-custom" name="email_superior" required>
                                <option selected disabled value="">Select Superior...</option>
                                <option value="oktaviaalifia5@gmail.com">oktaviaalifia5@gmail.com</option>
                                <option value="youbeljansen5@gmail.com">youbeljansen5@gmail.com</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-2 form-label-custom">Selected Document <span class="text-danger">*</span></div>
                    <div class="table-responsive mb-4">
                        <table class="table table-hover align-middle" id="documentTable">
                            <thead class="table-custom">
                                <tr>
                                    <th>#</th><th>Certificate No</th><th>Document Name</th><th>Company Name</th>
                                    <th>Jenis Permohonan</th><th>Category</th><th>Purpose</th><th>Return Date</th><th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr id="emptyRow">
                                    <td colspan="9" class="text-danger fw-bold py-3 text-center" style="font-family:'Poppins';">
                                        There must be at least one document in the request
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-5">
                        <div style="flex: 1; text-align: center;">
                            <span class="add-doc-link" data-bs-toggle="modal" data-bs-target="#searchModal">
                                + Add Document
                            </span>
                        </div>
                        <div>
                            <button type="button" class="btn-submit-custom" onclick="confirmSubmit()">
                                <i class="fas fa-paper-plane me-2"></i> Submit
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <div id="section-list" style="display: none;">
                <div class="table-responsive">
                    <table class="table table-hover align-middle table-custom">
                        <thead>
                            <tr>
                                <th>Entity</th>
                                <th>Nama Pemohon</th>
                                <th>Email Pemohon</th>
                                <th>Divisi</th>
                                <th>Tanggal</th>
                                <th>Email Atasan</th>
                                <th>Status</th>
                                <th>Total Doc</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($myLoans as $loan)
                            <tr>
                                <td>{{ $loan->entity }}</td>
                                <td>{{ Auth::user()->name }}</td>
                                <td style="text-decoration: underline;">{{ Auth::user()->email }}</td>
                                <td>{{ Auth::user()->divisi }}</td>
                                <td>{{ $loan->created_at->format('d/m/Y') }}</td>
                                <td style="text-decoration: underline;">{{ $loan->approver_email }}</td>
                                <td>
                                    <span class="status-badge 
                                        {{ $loan->status == 'Approved' ? 'status-approved' : 
                                          ($loan->status == 'Rejected' ? 'status-rejected' : 'status-submitted') }}">
                                        {{ $loan->status }}
                                    </span>
                                </td>
                                <td>1</td> 
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">Belum ada riwayat peminjaman.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <div class="modal fade" id="searchModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold" style="color:#743A34">Search Document</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex gap-2 mb-3">
                        <input type="text" class="form-control" placeholder="Search...">
                        <button class="btn btn-secondary">Search</button>
                    </div>
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Select</th><th>Cert No</th><th>Desa</th><th>Doc Name</th><th>Company</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr><td><input type="checkbox" class="doc-checkbox" value="951|Kelapa Dua|Kemang West 6|PT ADHI UTAMA"></td><td>951</td><td>Kelapa Dua</td><td>Kemang West 6</td><td>PT ADHI UTAMA</td></tr>
                            <tr><td><input type="checkbox" class="doc-checkbox" value="952|Cikarang|Delta Silicon 2|PT LIPPO CIKARANG"></td><td>952</td><td>Cikarang</td><td>Delta Silicon 2</td><td>PT LIPPO CIKARANG</td></tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn-submit-custom" onclick="addSelectedDocuments()">Select</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="confirmModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body p-4 text-center">
                    <h5 class="fw-bold mb-3" style="color:#743A34">Submit Request?</h5>
                    <p class="text-muted">Are you sure the data entered is correct?</p>
                    <div class="d-flex justify-content-center gap-3 mt-4">
                        <button type="button" class="btn btn-link text-danger text-decoration-none fw-bold" data-bs-dismiss="modal">No</button>
                        <button type="button" class="btn-submit-custom px-4" onclick="submitRealForm()">Yes</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // --- NAVIGASI ANTARA FORM INPUT DAN LIST RIWAYAT ---
        function showSection(section) {
            const inputSec = document.getElementById('section-input');
            const listSec = document.getElementById('section-list');
            const title = document.getElementById('pageTitle');
            const exportBtn = document.getElementById('exportBtn');

            if (section === 'input') {
                inputSec.style.display = 'block';
                listSec.style.display = 'none';
                title.innerText = 'Document Lending Form';
                exportBtn.classList.add('d-none');
            } else {
                inputSec.style.display = 'none';
                listSec.style.display = 'block';
                title.innerText = 'Document Lending List';
                exportBtn.classList.remove('d-none');
            }
        }

        // --- TAMBAH DOKUMEN KE TABEL ---
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
                            <td>
                                ${data[2]}
                                <input type="hidden" name="doc_name[]" value="${data[2]}"> 
                            </td>
                            <td>${data[3]}</td>
                            
                            <td>
                                <select class="form-select form-select-sm" name="jenis_permohonan[]" required>
                                    <option>Peminjaman</option>
                                    <option>Pengembalian</option>
                                </select>
                            </td>

                            <td>
                                <select class="form-select form-select-sm" name="category[]" required onchange="checkCategory(this)">
                                    <option value="Sertifikat Asli">Sertifikat Asli</option>
                                    <option value="Fotocopy">Fotocopy</option>
                                </select>
                            </td>

                            <td>
                                <select class="form-select form-select-sm" name="purpose[]" required onchange="checkPurpose(this)">
                                    <option value="AJB">AJB</option>
                                    <option value="Validasi">Validasi</option>
                                    <option value="Other">Other</option>
                                </select>
                                <div class="other-input-wrapper d-none">
                                    <input type="text" name="other_purpose_text[]" class="other-input" placeholder="Other Purpose">
                                    <small class="required-text">Required</small>
                                </div>
                            </td>

                            <td>
                                <input type="date" class="form-control form-control-sm return-date-input" name="return_date[]" required>
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

        // --- LOGIKA 1: CEK KATEGORI (FOTOCOPY = NO RETURN DATE) ---
        function checkCategory(selectElement) {
            const row = selectElement.closest('tr'); // Ambil baris tabel terkait
            const returnDateInput = row.querySelector('.return-date-input'); // Cari input tanggal di baris itu
            
            if (selectElement.value === 'Fotocopy') {
                returnDateInput.value = ''; // Kosongkan
                returnDateInput.readOnly = true; // Matikan input
                returnDateInput.required = false; // Tidak wajib
                returnDateInput.style.backgroundColor = '#e9ecef'; // Warna abu
            } else {
                returnDateInput.readOnly = false; // Nyalakan input
                returnDateInput.required = true; // Wajib
                returnDateInput.style.backgroundColor = '#fff'; // Warna putih
            }
        }

        // --- LOGIKA 2: CEK PURPOSE (OTHER = MUNCUL TEXTBOX) ---
        function checkPurpose(selectElement) {
            const row = selectElement.closest('tr');
            const wrapper = row.querySelector('.other-input-wrapper');
            const textInput = wrapper.querySelector('.other-input');

            if (selectElement.value === 'Other') {
                wrapper.classList.remove('d-none'); // Munculkan kotak
                textInput.required = true; // Wajib diisi
            } else {
                wrapper.classList.add('d-none'); // Sembunyikan
                textInput.required = false; // Tidak wajib
                textInput.value = ''; // Bersihkan text
            }
        }

        function removeRow(btn) {
            const row = btn.closest('tr');
            row.remove();
            const tableBody = document.querySelector('#documentTable tbody');
            
            let visibleRows = 0;
            for(let i=0; i<tableBody.rows.length; i++){
                if(tableBody.rows[i].id !== 'emptyRow') visibleRows++;
            }
            
            if(visibleRows === 0) { 
                document.getElementById('emptyRow').style.display = 'table-row';
            }
        }

        function confirmSubmit() {
            const form = document.getElementById('lendingForm');
            if (form.checkValidity()) {
                var myModal = new bootstrap.Modal(document.getElementById('confirmModal'));
                myModal.show();
            } else {
                form.reportValidity(); // Memunculkan pesan error browser "Please fill out this field"
            }
        }

        function submitRealForm() {
            document.getElementById('lendingForm').submit();
        }
    </script>
</body>
</html>