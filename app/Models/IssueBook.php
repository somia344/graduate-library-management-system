<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Issuebook extends Model
{
    use HasFactory;
    
    protected $table = 'issue_books';  // Table name
    
    protected $fillable = [
        'student_id',
        'book_id',
        'issue_date',
        'return_date',
        'status',
        'fine',
        'notes'
    ];
    
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
    
    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}