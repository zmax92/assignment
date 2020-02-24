<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

        $mark = '';
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
        $request->validate([
            'title' => ['required'],
            'author' => ['required'],
        ]);

        Book::create($request->all());

        $books = Book::all();

        $urlQuery = $mark = '';
        return view('books.index', [
            'title' => 'Home page',
            'books' => $books,
            'defaults' => [],
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
}
