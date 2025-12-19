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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            // Menambahkan Role sesuai audiens sasaran: Requestor, Approver, Custody [cite: 57-60]
            $table->enum('role', ['requestor', 'approver', 'custody'])->default('requestor');
            // Menambahkan Divisi (Legal, PSAS, Corsec, dll) [cite: 172]
            $table->string('divisi')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
