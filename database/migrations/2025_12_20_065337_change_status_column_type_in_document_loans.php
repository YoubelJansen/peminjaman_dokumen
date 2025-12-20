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
        Schema::table('document_loans', function (Blueprint $table) {
            // Ubah tipe kolom status jadi string biasa dengan panjang 50 karakter
            // Ini akan menerima 'Not Returned', 'Document Ready', dll tanpa error
            $table->string('status', 50)->change(); 
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('document_loans', function (Blueprint $table) {
            //
        });
    }
};
