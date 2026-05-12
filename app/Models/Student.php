<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Student extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
    'full_name',
    'father_name',
    'email',
    'phone_number',
    'department',      // ← ADD THIS
    'class',
    'roll_no',
    'registration_no', // ← ADD THIS
    'address',
    'password',
];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Relationship with issued books
    public function issuedBooks()
    {
        return $this->hasMany(IssueBook::class);
    }

    // Get currently issued books
    public function currentlyIssuedBooks()
    {
        return $this->issuedBooks()->where('status', 'issued');
    }

    // Get book requests
    public function bookRequests()
    {
        return $this->hasMany(BookRequest::class);
    }
}