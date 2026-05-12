<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'subject',
        'message',
        'admin_reply',
        'status',
        'student_id',
        'parent_id',
        'sender_type'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function parent()
    {
        return $this->belongsTo(ContactMessage::class, 'parent_id');
    }

    public function replies()
    {
        return $this->hasMany(ContactMessage::class, 'parent_id');
    }
}