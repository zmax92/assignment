<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;
use App\Book;

class BookController extends Controller
{
    public function index(Request $request){
        $defaults = $urlQuery = [];
        $query = DB::table('books');
        if(!empty($params = $request->all())){
            foreach($params as $field => $value){
                switch($field){
                    case 'title':
                    case 'author':
                        $query->where($field, 'LIKE', '%'.$value.'%');
                        // set defaults to persist filter values, after submit
                        $defaults[$field] = $value;
                        $urlQuery[] = $field.'='.$value;
                    break;
                    case 'orderAuthor':
                        $query->orderBy('author', $value);
                    break;
                    case 'orderTitle':
                        $query->orderBy('title', $value);
                    break;
                }
            }
        }
        $books = $query->get();
        $books = $books->toArray();

        $mark = '';
        // construct url params for sorting, when previously filtering was done
        if(!empty($urlQuery)){
            $urlQuery = implode('&', $urlQuery);
            $mark = '&';
        }
        else{
            $urlQuery = '';
        }
        return view('books.index', [
            'title' => 'Home page',
            'books' => $books,
            'defaults' => $defaults,
            'orderTitle' => [
                'desc' => '/?'.$urlQuery.$mark.'orderTitle=desc',
                'asc' => '/?'.$urlQuery.$mark.'orderTitle=asc'
            ],
            'orderAuthor' => [
                'desc' => '/?'.$urlQuery.$mark.'orderAuthor=desc',
                'asc' => '/?'.$urlQuery.$mark.'orderAuthor=asc'
            ]
        ]);
    }

    public function create(){
        return view('books.create', [
            'title' => 'Create book',
        ]);
    }

    public function store(Request $request){
        $valid = $request->validate([
            'title' => ['required'],
            'author' => ['required'],
        ]);

        Book::create($valid);

        $books = Book::all();

        return view('books.index', [
            'title' => 'Home page',
            'books' => $books,
            'orderTitle' => [
                'desc' => '/?orderTitle=desc',
                'asc' => '/?orderTitle=asc'
            ],
            'orderAuthor' => [
                'desc' => '/?orderAuthor=desc',
                'asc' => '/?orderAuthor=asc'
            ]
        ]);
    }

    public function destroy($bookId){
        $response = new response();
        $book = Book::find($bookId);

        if(!empty($book)){
            if($book->delete()){
                return $response->setStatusCode(200);
            }
        }
        return $response->setStatusCode(500);
    }

    public function update(Request $request, $bookId){
        $params = $request->all();
        $response = new response();
        $book = Book::find($bookId);
        if(!empty($book)){
            $book->author = $params['author'];
            if($book->save()){
                return $response->setStatusCode(200);
            }
        }
        return $response->setStatusCode(500);
    }
}
