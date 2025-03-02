<?php

use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

function get_books_data(Collection $books, Request $request, array $set_in_result = []): array
{
    $query = $request->query();
    $search_by_book_title = !!array_key_exists("book_title", $query) ? mb_strtolower($query["book_title"], "UTF-8") : '';
    $search_by_book_category = !!array_key_exists("book_category", $query) ? $query["book_category"] : null;
    $search_by_book_author = !!array_key_exists("book_author", $query) ? $query["book_author"] : null;
    $search_by_book_year = !!array_key_exists("book_year", $query) ? $query["book_year"] : null;
    $search_by_book_status = !!array_key_exists("book_status", $query) ? $query["book_status"] : null;

    $books = Book::whereIn('id', array_map(
        function(Array $array) {return $array['id'];},
        array_filter($books->toArray(), function($book) use ($search_by_book_title) {
            return str_contains(mb_strtolower($book['title'], "UTF-8"), $search_by_book_title);
        })
    ));
    if ($search_by_book_category) {
        $books = $books->where('category_id', $search_by_book_category);
    }
    if ($search_by_book_author) {
        $books = $books->where('author_id', $search_by_book_author);
    }
    if ($search_by_book_year) {
        $books = $books->where('published_year', $search_by_book_year);
    }
    if ($search_by_book_status) {
        $books = $books->where('status', $search_by_book_status);
    }

    return array_merge($set_in_result, [
        'books' => $books->get(),
        'statuses' => [
            [
                'id' => 1,
                'title' => 'Черновик'
            ],
            [
                'id' => 2,
                'title' => 'Опубликовано'
            ],
            [
                'id' => 3,
                'title' => 'Снято с публикации'
            ],
        ],
        'categories' => Category::all(),
        'authors' => Author::all(),
    ]);
}
