<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Librarian;
use App\Models\Category;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create default librarian
        Librarian::create([
            'name' => 'Admin Librarian',
            'email' => 'librariangraduate@gmail.com',
            'password' => Hash::make('Admin@1234')
        ]);
        
        // Create categories
        $categories = ['Fiction', 'Non-Fiction', 'Science', 'Technology', 'Mathematics', 'History', 'Literature', 'Art'];
        foreach ($categories as $category) {
            Category::create(['name' => $category]);
        }
    }
}