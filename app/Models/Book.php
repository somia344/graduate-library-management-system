<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;
protected $fillable = [
    'title', 
    'author', 
    'category',  // ← category hona chahiye, category_id nahi
    'book_image', 
    'isbn', 
    'quantity', 
    'available'
];

    // Relationship with issued books
    public function issuedBooks()
    {
        return $this->hasMany(IssuedBook::class);
    }

    // Get currently issued books
    public function currentlyIssuedBooks()
    {
        return $this->issuedBooks()->where('status', 'issued');
    }

    // Check if book is available
    public function isAvailable()
    {
        return $this->available > 0;
    }

    // Decrement available quantity
    public function decrementAvailable()
    {
        if ($this->available > 0) {
            $this->decrement('available');
            return true;
        }
        return false;
    }

    // Increment available quantity
    public function incrementAvailable()
    {
        $this->increment('available');
    }
    public function reservations()
{
    return $this->hasMany(BookReservation::class);
}

public function activeReservations()
{
    return $this->hasMany(BookReservation::class)->where('status', 'pending');
}

public function isReservable()
{
    return $this->available == 0;
}

}