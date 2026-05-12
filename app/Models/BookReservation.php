<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookReservation extends Model
{
    use HasFactory;

    protected $table = 'book_reservations';

    protected $fillable = [
        'student_id',
        'book_id',
        'reservation_date',
        'expiry_date',
        'position',
        'status',
        'notified_at'
    ];

    protected $casts = [
        'reservation_date' => 'date',
        'expiry_date' => 'date',
        'notified_at' => 'datetime'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function isActive()
    {
        return $this->status === 'active' && !$this->isExpired();
    }

    public function isExpired()
    {
        return $this->expiry_date && now()->gt($this->expiry_date);
    }
}