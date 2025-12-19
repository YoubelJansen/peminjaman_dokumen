<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('document_loans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users'); // Requestor
            
            // Data Dokumen
            $table->string('entity'); // Contoh: Lippo Karawaci, Tanjung Bunga [cite: 148]
            $table->string('document_category'); // Sertifikat Asli/Copy [cite: 194]
            $table->string('document_name'); // Hasil search dokumen [cite: 178]
            $table->text('request_purpose'); // Tujuan Peminjaman [cite: 202]
            $table->date('return_date')->nullable(); // Tanggal Pengembalian [cite: 211]
            
            // Approval System
            $table->string('approver_email'); // Email Atasan [cite: 167]
            
            // Status Peminjaman (Sesuai Manual)
            // Flow: Submitted -> Approved/Rejected -> Document Ready -> Borrowed -> Returned/Finished [cite: 240-245, 339, 366]
            $table->enum('status', [
                'Submitted', 'Approved', 'Rejected', 'Cancelled', 
                'Document Ready', 'Borrowed', 'Returned', 'Finished'
            ])->default('Submitted');

            $table->text('rejection_reason')->nullable(); // Alasan Penolakan [cite: 304]
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_loans');
    }
};
