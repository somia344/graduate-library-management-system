<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class LibrarianSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('librarians')->insert([
            'name' => 'Admin Librarian',
            'email' => 'librariangraduate@gmail.com',
            'password' => Hash::make('Admin@1234'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}