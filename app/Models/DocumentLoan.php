<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentLoan extends Model
{
    use HasFactory;

    // Tambahkan bagian ini agar Laravel mengizinkan input data
    protected $fillable = [
        'user_id',
        'entity',
        'document_category',
        'document_name',
        'request_purpose',
        'return_date',
        'approver_email',
        'status',
        'rejection_reason', // Tambahkan juga ini untuk persiapan nanti
    ];
}