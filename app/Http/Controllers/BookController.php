<?php

namespace App\Http\Controllers;
use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index()
{
    return response()->json(Book::all());
}
     public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'published_year' => 'nullable|digits:4|integer|min:1000|max:' . date('Y'),
            'isbn' => 'nullable|string|unique:books,isbn',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
        ]);

        $book = Book::create($validated);

        return response()->json([
            'message' => 'Book created successfully',
            'data' => $book,
        ], 201);
        
    }
    public function update(Request $request, $id)
{
    $book = Book::findOrFail($id);

    $validated = $request->validate([
        'title' => 'sometimes|required|string|max:255',
        'author' => 'sometimes|required|string|max:255',
        'published_year' => 'nullable|digits:4|integer|min:1000|max:' . date('Y'),
        'isbn' => 'nullable|string|unique:books,isbn,' . $book->id,
        'description' => 'nullable|string',
        'price' => 'nullable|numeric|min:0',
    ]);

    $book->update($validated);

    return response()->json([
        'message' => 'Book updated successfully',
        'data' => $book,
    ],200);
}
public function destroy($id)
{
    $book = Book::findOrFail($id);
    $book->delete();

    return response()->json([
        'message' => 'Books are  deleted successfully',
    ],200);
}
}
