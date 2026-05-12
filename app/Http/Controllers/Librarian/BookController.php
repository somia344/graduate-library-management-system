<?php

namespace App\Http\Controllers\Librarian;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    /**
     * Display a listing of the books.
     */
    public function index()
    {
        $books = Book::orderBy('id', 'asc')->paginate(10);
        return view('librarian.manage-books.index', compact('books'));
    }

    /**
     * Show the form for creating a new book.
     */
    public function create()
    {
        return view('librarian.manage-books.create');
    }

    /**
     * Store a newly created book in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'isbn' => 'nullable|string|max:20',
            'quantity' => 'required|integer|min:1',
            'book_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $book = new Book();
        $book->title = $request->title;
        $book->author = $request->author;
        $book->category = $request->category;
        $book->isbn = $request->isbn;
        $book->quantity = $request->quantity;
        $book->available = $request->quantity;

        // Handle image upload
        if ($request->hasFile('book_image')) {
            $image = $request->file('book_image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/books'), $imageName);
            $book->book_image = 'uploads/books/' . $imageName;
        }

        $book->save();

        return redirect()->route('librarian.books.index')
            ->with('success', 'Book added successfully!');
    }

    /**
     * Display the specified book.
     */
    public function show($id)
    {
        $book = Book::findOrFail($id);
        return view('librarian.manage-books.show', compact('book'));
    }

    /**
     * Show the form for editing the specified book.
     */
    public function edit($id)
    {
        $book = Book::findOrFail($id);
        return view('librarian.manage-books.edit', compact('book'));
    }

    /**
     * Update the specified book in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'isbn' => 'nullable|string|max:20',
            'quantity' => 'required|integer|min:1',
            'book_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $book = Book::findOrFail($id);
        
        // Calculate new available quantity
        $oldQuantity = $book->quantity;
        $newQuantity = $request->quantity;
        $diff = $newQuantity - $oldQuantity;
        
        $book->title = $request->title;
        $book->author = $request->author;
        $book->category = $request->category;
        $book->isbn = $request->isbn;
        $book->quantity = $request->quantity;
        $book->available = $book->available + $diff;

        // Handle image upload
        if ($request->hasFile('book_image')) {
            // Delete old image if exists
            if ($book->book_image && file_exists(public_path($book->book_image))) {
                unlink(public_path($book->book_image));
            }
            
            $image = $request->file('book_image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/books'), $imageName);
            $book->book_image = 'uploads/books/' . $imageName;
        }

        $book->save();

        return redirect()->route('librarian.books.index')
            ->with('success', 'Book updated successfully!');
    }

    /**
     * Remove the specified book from storage.
     */
    public function destroy($id)
    {
        $book = Book::findOrFail($id);
        
        // Delete image if exists
        if ($book->book_image && file_exists(public_path($book->book_image))) {
            unlink(public_path($book->book_image));
        }
        
        $book->delete();

        return redirect()->route('librarian.books.index')
            ->with('success', 'Book deleted successfully!');
    }
}