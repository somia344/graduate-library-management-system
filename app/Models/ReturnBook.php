<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnBook extends Model
{
    use HasFactory;

    protected $table = 'return_books';

    protected $fillable = [
        'issue_book_id',
        'return_date',
        'days_overdue',
        'fine_amount'
    ];

    protected $casts = [
        'return_date' => 'date'
    ];

    /**
     * Get the issue book record.
     */
    public function issueBook()
    {
        return $this->belongsTo(IssueBook::class, 'issue_book_id');
    }
}