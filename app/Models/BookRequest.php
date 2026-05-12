<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookRequest extends Model
{
    protected $fillable = [
        'student_id',
        'book_id',
        'request_date',
        'status',
        'admin_response'  // ← YEH LINE ADD KARO
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