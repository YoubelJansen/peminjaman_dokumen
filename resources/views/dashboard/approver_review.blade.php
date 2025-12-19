<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LendCore - Review Request</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background-color: #F4F6F8; }
        .card-custom { border: none; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        .form-control[readonly] { background-color: #F3F4F6; }
        .btn-approve { background-color: #8D6E63; color: white; border: none; }
        .btn-approve:hover { background-color: #6D4C41; color: white;}
        .btn-reject { background-color: #FF0000; color: white; border: none; }
        .btn-reject:hover { background-color: #CC0000; color: white; }
    </style>
</head>
<body>

<div class="container mt-5 mb-5">
    
    <a href="{{ route('dashboard.approver') }}" class="text-decoration-none text-secondary mb-3 d-block">
        <i class="fas fa-arrow-left"></i> Back to List
    </a>

    <div class="card card-custom p-4">
        <h5 class="fw-bold mb-4" style="color: #6D4C41;">Document Lending Form - Review</h5>

        <div class="row mb-3">
            <div class="col-md-4">
                <label class="form-label text-muted small fw-bold">Entity</label>
                <input type="text" class="form-control" value="{{ $loan->entity }}" readonly>
            </div>
            <div class="col-md-4">
                <label class="form-label text-muted small fw-bold">Tanggal Permohonan</label>
                <input type="text" class="form-control" value="{{ $loan->created_at->format('Y-m-d') }}" readonly>
            </div>
            <div class="col-md-4">
                <label class="form-label text-muted small fw-bold">Divisi Pemohon</label>
                <input type="text" class="form-control" value="{{ $requestor->divisi ?? '-' }}" readonly>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label text-muted small fw-bold">Nama Pemohon</label>
                <input type="text" class="form-control" value="{{ $requestor->name }}" readonly>
            </div>
            <div class="col-md-6">
                <label class="form-label text-muted small fw-bold">Email Pemohon</label>
                <input type="text" class="form-control" value="{{ $requestor->email }}" readonly>
            </div>
        </div>

        <div class="table-responsive mt-4 mb-4">
            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>Doc Name</th>
                        <th>Category</th>
                        <th>Company</th>
                        <th>Purpose</th>
                        <th>Return Date</th>
                        <th>Current Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $loan->document_name }}</td>
                        <td>{{ $loan->document_category }}</td>
                        <td>{{ $loan->entity }}</td> <td>{{ $loan->request_purpose }}</td>
                        <td>{{ $loan->return_date ?? '-' }}</td>
                        <td><span class="badge bg-secondary">{{ $loan->status }}</span></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <form action="{{ route('approver.update', $loan->id) }}" method="POST">
            @csrf
            
            <div class="mb-3">
                <label class="form-label fw-bold">Approval Description / Reject Reason</label>
                <textarea name="reason" class="form-control" rows="3" placeholder="Isi catatan persetujuan atau alasan penolakan..."></textarea>
            </div>

            <div class="d-flex justify-content-end gap-3">
                <button type="submit" name="action" value="reject" class="btn btn-reject px-5 py-2">
                    <i class="fas fa-times me-2"></i> Reject
                </button>

                <button type="submit" name="action" value="approve" class="btn btn-approve px-5 py-2">
                    <i class="fas fa-check me-2"></i> Approve
                </button>
            </div>
        </form>

    </div>
</div>

</body>
</html>