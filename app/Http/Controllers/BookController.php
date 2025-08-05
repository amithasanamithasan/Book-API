<?php

namespace App\Http\Controllers;
use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
public function index()
    {
        return Book::all()->map(function ($book) {
            return [
                'id' => $book->id,
                'title' => $book->title,
                'author' => $book->author,
                'published_year' => $book->published_year,
                'isbn' => $book->isbn,
                'description' => $book->description,
                'price' => $book->price,
                'picture' => $book->picture 
                    ? asset('storage/' . $book->picture) 
                    : null,
            ];
        });
    }

    public function show($id)
    {
        $book = Book::find($id);

    if (!$book) {
        return response()->json([
            'message' => 'Book not found',
        ], 404);
    }

    return response()->json([
        'data' => $book,
    ]);
}

      public function store(Request $request)
    {
        $validated = $request->validate([
            'picture' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'published_year' => 'nullable|digits:4|integer|min:1000|max:' . date('Y'),
            'isbn' => 'nullable|string|unique:books,isbn',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
        ]);

        // Save picture if exists
        if ($request->hasFile('picture')) {
            $fileName = time() . '_' . $request->picture->getClientOriginalName();
            $filePath = $request->picture->storeAs('books', $fileName, 'public');

            // Store only relative path in DB (without /storage/)
            $validated['picture'] = $filePath; // e.g. books/filename.png
        }

        $book = Book::create($validated);

        return response()->json([
            'message' => 'Book created successfully',
            'data' => [
                ...$book->toArray(),
                'picture' => $book->picture 
                    ? asset('storage/' . $book->picture) 
                    : null,
            ],
        ], 201);
    }

    public function update(Request $request, $id)
{
    $book = Book::findOrFail($id);

    $validated = $request->validate([
        'picture' => 'nullable|string|max:255',
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

public function updatepartial(Request $request,$id)
{
    $book = Book::findOrFail($id);

    $validated = $request->validate([
        'description' => 'nullable|string',
        'price' => 'nullable|numeric|min:0',
    ]);

    $book->update($validated);

    return response()->json([
        'message' => 'Book updated successfully',
        'data' => $book,
    ],200);
}


}
