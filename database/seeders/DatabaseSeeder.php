<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        // 1. Akun Karyawan (Requestor)
        User::create([
            'name' => 'Karyawan IT',
            'email' => 'karyawan@lippo.com',
            'role' => 'requestor',
            'divisi' => 'IT Intern',
            'password' => Hash::make('password123'),
        ]);

        // 2. Akun Atasan (Approver)
        User::create([
            'name' => 'Pak Manager',
            'email' => 'manager@lippo.com',
            'role' => 'approver',
            'divisi' => 'IT Head',
            'password' => Hash::make('password123'),
        ]);

        // 3. Akun Custody (Pengelola Dokumen)
        User::create([
            'name' => 'Tim Custody',
            'email' => 'custody@lippo.com',
            'role' => 'custody',
            'divisi' => 'General Affair',
            'password' => Hash::make('password123'),
        ]);
    }
}
