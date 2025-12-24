<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LendCore - Review Request</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        body {
            background-color: #E1E9EF;
            font-family: 'Poppins', 'Inter', sans-serif;
        }

        /* HEADER */
        .navbar-custom {
            background-color: #FFFFFF;
            height: 70px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            padding: 0 30px;
        }
        .brand-text {
            color: #743A34;
            font-weight: 700;
            font-size: 20px;
        }
        .user-badge {
            background-color: #8F835A;
            color: white;
            padding: 8px 16px;
            border-radius: 5px;
            font-size: 14px;
            font-weight: 500;
        }

        /* BACK BUTTON */
        .btn-back-custom {
            background-color: #8F835A;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 10px 20px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
            text-decoration: none;
        }
        .btn-back-custom:hover {
            background-color: #7a6f4a;
            color: white;
        }

        /* CARD */
        .card-custom {
            border: none;
            border-radius: 6px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.02);
            padding: 30px;
            background: white;
        }
        .form-title {
            color: #743A34;
            font-weight: 700;
            font-size: 18px;
            border-bottom: 1px solid #E0E0E0;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }

        /* INPUTS GLOBAL */
        .form-label {
            color: #A7A3A3;
            font-weight: 600;
            font-size: 12px;
            margin-bottom: 5px;
        }
        .form-control {
            border: 1px solid #CED4DA;
            border-radius: 6px;
            padding: 10px 15px;
            font-size: 14px;
            color: #495057;
        }
        .form-control:read-only {
            background-color: #FFFFFF; 
        }
        .input-username {
            background-color: #E2ECF4 !important;
            border-color: #D6E0E8;
        }

        /* TABLE STYLES */
        .table-custom th {
            font-size: 12px;
            font-weight: 600;
            color: #6C757D;
            border-bottom: 2px solid #DEE2E6;
            padding-bottom: 10px;
        }
        .table-custom td {
            font-size: 13px;
            color: #212529;
            vertical-align: middle;
            padding: 15px 5px;
        }
        
        .table-input-readonly {
            background-color: #E9ECEF !important;
            border: 1px solid #CED4DA;
            border-radius: 5px;
            font-size: 13px;
            padding: 8px 10px;
            color: #495057;
            width: 100%;
        }
        
        .input-group-text-custom {
            background-color: #E9ECEF;
            border: 1px solid #CED4DA;
            border-left: none;
            border-radius: 0 5px 5px 0;
        }
        .date-input-custom {
            border-right: none;
            border-radius: 5px 0 0 5px;
            background-color: #E9ECEF !important;
        }

        /* BUTTONS */
        .reason-box {
            background-color: #F8F9FA;
            border: 1px solid #CED4DA;
            border-radius: 6px;
            font-size: 13px;
            resize: none;
        }
        .btn-reject {
            background-color: #FF0000;
            color: white;
            font-weight: 600;
            border-radius: 6px;
            border: none;
            padding: 10px 40px;
        }
        .btn-approve {
            background-color: #8F835A;
            color: white;
            font-weight: 600;
            border-radius: 6px;
            border: none;
            padding: 10px 40px;
        }

        /* LINK TRIGGER POPUP */
        .approval-list-trigger {
            color: #6C757D;
            font-weight: 700;
            font-size: 14px;
            text-decoration: none;
            cursor: pointer;
            transition: color 0.3s;
        }
        .approval-list-trigger:hover {
            color: #743A34;
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-custom d-flex justify-content-between align-items-center">
        <div class="brand-text">LendCore</div>
        <div class="user-badge">
            <i class="fas fa-user me-2"></i> {{ Auth::user()->name }} (Approver)
        </div>
    </nav>

    <div class="container-fluid px-5 mt-4 mb-5">
        
        <a href="{{ route('dashboard.approver') }}" class="btn-back-custom">
            <i class="fas fa-bars"></i> Document Lending List
        </a>

        <div class="card card-custom">
            <h5 class="form-title">Document Lending Form</h5>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Entity*</label>
                    <div class="input-group">
                        <input type="text" class="form-control" value="{{ $loan->entity }}" readonly style="background: white;">
                        <span class="input-group-text bg-white"><i class="fas fa-caret-down text-muted"></i></span>
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tanggal Permohonan*</label>
                    <div class="input-group">
                        <input type="text" class="form-control" value="{{ $loan->created_at->format('Y-m-d') }}" readonly>
                        <span class="input-group-text bg-white"><i class="far fa-calendar text-muted"></i></span>
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Divisi Pemohon*</label>
                    <div class="input-group">
                        <input type="text" class="form-control" value="{{ $requestor->divisi ?? '-' }}" readonly>
                        <span class="input-group-text bg-white"><i class="fas fa-caret-down text-muted"></i></span>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Nama Pemohon*</label>
                    <input type="text" class="form-control" value="{{ $requestor->name }}" readonly>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email Pemohon*</label>
                    <input type="text" class="form-control" value="{{ $requestor->email }}" readonly>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <label class="form-label">Username*</label>
                    <input type="text" class="form-control input-username" value="{{ $requestor->username ?? '-' }}" readonly>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email Superior*</label>
                    <div class="input-group">
                        <input type="text" class="form-control" value="{{ $loan->approver_email }}" readonly>
                        <span class="input-group-text bg-white"><i class="fas fa-caret-down text-muted"></i></span>
                    </div>
                </div>
            </div>

            <div class="mb-2">
                <label class="form-label" style="font-size: 14px; color: #A7A3A3;">Selected Document*</label>
            </div>

            <div class="table-responsive mb-4">
                <table class="table table-custom table-borderless">
                    <thead>
                        <tr>
                            <th style="width: 3%;">#</th>
                            <th style="width: 10%;">Certificate No</th>
                            <th style="width: 17%;">Document Name</th>
                            <th style="width: 15%;">Company Name</th>
                            <th style="width: 12%;">Jenis Permohonan*</th>
                            <th style="width: 12%;">Document Category*</th>
                            <th style="width: 10%;">Request Purpose*</th>
                            <th style="width: 12%;">Return Date</th>
                            <th style="width: 10%;">Status</th>
                            <th style="width: 3%;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>951</td>
                            <td>{{ $loan->document_name }}</td>
                            <td>{{ $loan->entity }}</td>
                            <td>
                                <input type="text" class="form-control table-input-readonly" value="Peminjaman" readonly>
                            </td>
                            <td>
                                <input type="text" class="form-control table-input-readonly" value="{{ $loan->document_category }}" readonly>
                            </td>
                            <td>
                                <input type="text" class="form-control table-input-readonly" value="{{ $loan->request_purpose }}" readonly>
                            </td>
                            <td>
                                <div class="input-group input-group-sm">
                                    <input type="text" class="form-control table-input-readonly date-input-custom" 
                                           value="{{ $loan->return_date ? \Carbon\Carbon::parse($loan->return_date)->format('d/m/Y') : '-' }}" 
                                           readonly>
                                    <span class="input-group-text input-group-text-custom">
                                        <i class="far fa-calendar-alt text-muted"></i>
                                    </span>
                                </div>
                            </td>
                            <td>
                                <input type="text" class="form-control table-input-readonly" value="{{ $loan->status }}" readonly>
                            </td>
                            <td class="text-center text-danger align-middle">
                                <i class="far fa-trash-alt cursor-pointer"></i>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <form action="{{ route('approver.update', $loan->id) }}" method="POST">
                @csrf
                <div class="row mt-4">
                    <div class="col-md-6 d-flex align-items-end">
                        <a href="#" class="approval-list-trigger mb-2" data-bs-toggle="modal" data-bs-target="#approvalListModal">
                            Approval List
                        </a>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <textarea name="reason" class="form-control reason-box" rows="3" placeholder="Approval description / Reject Reason"></textarea>
                        </div>
                        <div class="d-flex justify-content-end gap-3">
                            <button type="submit" name="action" value="reject" class="btn btn-reject">
                                <i class="fas fa-times me-2"></i> Reject
                            </button>
                            <button type="submit" name="action" value="approve" class="btn btn-approve">
                                <i class="fas fa-check me-2"></i> Approve
                            </button>
                        </div>
                    </div>
                </div>
            </form>

        </div>
    </div>

    <div class="modal fade" id="approvalListModal" tabindex="-1" aria-labelledby="approvalListLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold" id="approvalListLabel" style="color: #743A34; font-size: 16px;">Approval List</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-borderless align-middle" style="font-size: 13px;">
                            <thead style="border-bottom: 1px solid #E0E0E0;">
                                <tr class="text-muted fw-bold">
                                    <th scope="col">#</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Approval Date</th>
                                    <th scope="col">Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td class="fw-semibold">{{ $approver ? $approver->name : 'Approver' }}</td>
                                    <td>{{ $loan->approver_email }}</td>
                                    <td>
                                        @if($loan->status == 'Approved' || $loan->status == 'Document Ready' || $loan->status == 'Borrowed' || $loan->status == 'Returned')
                                            <span class="text-success fw-bold">Approved</span>
                                        @elseif($loan->status == 'Rejected')
                                            <span class="text-danger fw-bold">Rejected</span>
                                        @else
                                            <span class="text-warning fw-bold">Pending</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $loan->updated_at->format('d/m/Y') }}
                                    </td>
                                    <td>
                                        {{ $loan->rejection_reason ?? '-' }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>