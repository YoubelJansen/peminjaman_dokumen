<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>LendCore - Custody Review</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        body { background-color: #E1E9EF; font-family: 'Inter', sans-serif; }
        .top-bar { background: #FDFDFD; height: 74px; display: flex; align-items: center; padding-left: 34px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
        .brand-text { color: #743A34; font-size: 20px; font-weight: 700; }
        .main-card { background: white; border-radius: 8px; padding: 40px; margin-top: 30px; min-height: 800px; }
        .form-label-custom { color: #A7A3A3; font-size: 14px; font-weight: 600; margin-bottom: 5px; }
        .form-control-custom { background-color: #F8F9FA; border: 1px solid #8A8A8A; border-radius: 5px; height: 50px; color: #333; }
        
        .table-custom-header th { background-color: white; border-bottom: 2px solid #ddd; color: #555; font-size: 13px; font-weight: 700; padding: 15px; }
        .table-row td { padding: 15px; border-bottom: 1px solid #E6E6E6; vertical-align: middle; font-size: 14px; }
        
        .status-select { padding: 8px 12px; border-radius: 5px; border: 1px solid #ccc; background: white; width: 100%; font-size: 14px; cursor: pointer; }
        .btn-save-custom { background-color: #8F835A; color: white; border-radius: 5px; padding: 10px 40px; font-weight: 700; border: none; font-size: 16px; }
        .btn-save-custom:hover { background-color: #7a6f4a; color: white; }
        
        /* Tombol Receive kecil khusus */
        .btn-receive { background-color: #8F835A; color: white; border: none; border-radius: 4px; padding: 6px 15px; font-size: 12px; font-weight: 600; }
        
        .back-btn { background-color: #8F835A; color: white; border-radius: 5px; padding: 8px 20px; text-decoration: none; font-weight: bold; }
    </style>
</head>
<body>

    <div class="top-bar d-flex justify-content-between pe-5">
        <div class="brand-text">LendCore</div>
        <div class="d-flex align-items-center gap-3">
            <span style="color: #5E5433; font-size: 15px;">{{ Auth::user()->name }} (Custody)</span>
            <div style="width: 37px; height: 33px; background: #8F835A; border-radius: 5px; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-user text-white"></i>
            </div>
        </div>
    </div>

    <div class="container pb-5">
        <div class="mt-4"><a href="{{ route('dashboard.custody') }}" class="back-btn"><i class="fas fa-arrow-left me-2"></i> List</a></div>

        <div class="main-card">
            <h4 class="mb-4 fw-bold" style="color:#743A34">Document Lending Form</h4>

            <div class="row mb-4">
                <div class="col-md-4"><label class="form-label-custom">Entity*</label><input type="text" class="form-control form-control-custom" value="{{ $loan->entity }}" readonly></div>
                <div class="col-md-4"><label class="form-label-custom">Tanggal Permohonan*</label><input type="text" class="form-control form-control-custom" value="{{ $loan->created_at->format('d/m/Y') }}" readonly></div>
                <div class="col-md-4"><label class="form-label-custom">Divisi Pemohon*</label><input type="text" class="form-control form-control-custom" value="{{ $requestor->divisi ?? '-' }}" readonly></div>
            </div>
            <div class="row mb-4">
                <div class="col-md-6"><label class="form-label-custom">Nama Pemohon*</label><input type="text" class="form-control form-control-custom" value="{{ $requestor->name }}" readonly></div>
                <div class="col-md-6"><label class="form-label-custom">Email Pemohon*</label><input type="text" class="form-control form-control-custom" value="{{ $requestor->email }}" readonly></div>
            </div>
            <div class="row mb-4">
                <div class="col-md-6"><label class="form-label-custom">Username*</label><input type="text" class="form-control form-control-custom" value="{{ $requestor->username }}" readonly></div>
                <div class="col-md-6"><label class="form-label-custom">Email Superior*</label><input type="text" class="form-control form-control-custom" value="{{ $loan->approver_email }}" readonly></div>
            </div>

            <form action="{{ route('custody.update', $loan->id) }}" method="POST">
                @csrf
                <div class="mb-2 form-label-custom">Selected Document*</div>
                <div class="table-responsive mb-5">
                    <table class="table">
                        <thead class="table-custom-header">
                            <tr>
                                <th>#</th><th>Certificate No</th><th>Document Name</th><th>Company Name</th><th>Jenis</th><th>Category</th><th>Purpose</th><th>Return Date</th>
                                <th width="200px">Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="table-row">
                                <td>1</td>
                                <td>951</td>
                                <td>{{ $loan->document_name }}</td>
                                <td>PT ADHI UTAMA</td>
                                <td>Peminjaman</td>
                                <td>{{ $loan->document_category }}</td>
                                <td>{{ $loan->request_purpose }}</td>
                                <td>{{ $loan->return_date ?? '-' }}</td>
                                
                                <td>
                                    <select name="status_dropdown" class="status-select">
                                        <option value="{{ $loan->status }}" selected>{{ $loan->status }}</option>
                                        
                                        @if($loan->status == 'Approved')
                                            <option value="Document Ready">Document Ready</option>
                                        @endif

                                        @if($loan->status == 'Borrowed')
                                            <option value="Returned">Returned</option>
                                            <option value="Not Returned">Not Returned</option>
                                        @endif
                                    </select>
                                </td>

                                <td>
                                    @if($loan->status == 'Document Ready')
                                        <button type="submit" name="action" value="receive" class="btn btn-receive">
                                            Receive
                                        </button>
                                    @endif
                                    </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="row mt-5">
                    <div class="col-md-6"></div>
                    <div class="col-md-6 text-end">
                        @if($loan->status == 'Approved')
                            <textarea name="note" class="form-control mb-3" placeholder="Reject Reason..." rows="2"></textarea>
                            <button type="submit" name="action" value="reject" class="btn btn-danger me-2">Reject</button>
                            <button type="submit" name="action" value="approve" class="btn btn-save-custom">Approve</button>
                        @else
                            <button type="submit" name="action" value="save" class="btn btn-save-custom">
                                <i class="far fa-file-alt me-2"></i> Save
                            </button>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>
</body>
</html>