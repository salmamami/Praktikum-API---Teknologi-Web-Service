<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookResource;
use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index()
    {
        return BookResource::collection(Book::with('category')->latest()->get());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:200',
            'author' => 'required|string|max:100',
            'price' => 'required|integer|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'isbn' => 'nullable|string|max:50|unique:books,isbn',
        ]);

        $book = Book::create($data);

        return (new BookResource($book->load('category')))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Book $book)
    {
        return new BookResource($book->load('category'));
    }

    public function update(Request $request, Book $book)
    {
        $data = $request->validate([
            'category_id' => 'sometimes|required|exists:categories,id',
            'title' => 'sometimes|required|string|max:200',
            'author' => 'sometimes|required|string|max:100',
            'price' => 'sometimes|required|integer|min:0',
            'stock' => 'sometimes|required|integer|min:0',
            'description' => 'nullable|string',
            'isbn' => 'nullable|string|max:50|unique:books,isbn,' . $book->id,
        ]);

        $book->update($data);

        return new BookResource($book->load('category'));
    }

    public function destroy(Book $book)
    {
        $book->delete();

        return response()->noContent();
    }
}