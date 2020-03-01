<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;
use App\Book;

class BookController extends Controller
{
    public function index(Request $request){
        $params = $this->retrieveStructure($request);
        $books = $params['books'];
        $defaults = $params['defaults'];
        $urlQuery = $params['urlQuery'];
        $downloadParams = $params['downloadParams'];

        $markSort = $markDownload = '';
        // construct url params for sorting, when previously filtering was done
        if(!empty($urlQuery)){
            $urlQuery = implode('&', $urlQuery);
            $markSort = '&';
        }
        else{
            $urlQuery = '';
        }
        $urlQuery = '/?'.$urlQuery;

        // construct url params for sorting, when previously filtering was done
        if(!empty($downloadParams)){
            $downloadParams = implode('&', $downloadParams);
            $markDownload = '&';
        }
        else{
            $downloadParams = '';
        }
        $downloadParams = '/download?'.$downloadParams;

        return view('books.index', [
            'title' => 'Home page',
            'books' => $books,
            'defaults' => $defaults,
            'download' => [
                'titleOnly' => [
                    'csv' => $downloadParams.$markDownload.'fields=titleOnly&format=csv',
                    'xml' => $downloadParams.$markDownload.'fields=titleOnly&format=xml'
                ],
                'authorOnly' => [
                    'csv' => $downloadParams.$markDownload.'fields=authorOnly&format=csv',
                    'xml' => $downloadParams.$markDownload.'fields=authorOnly&format=xml'
                ],
                'all' => [
                    'csv' => $downloadParams.$markDownload.'format=csv',
                    'xml' => $downloadParams.$markDownload.'format=xml'
                ]
            ],
            'orderTitle' => [
                'desc' => $urlQuery.$markSort.'orderTitle=desc',
                'asc' => $urlQuery.$markSort.'orderTitle=asc'
            ],
            'orderAuthor' => [
                'desc' => $urlQuery.$markSort.'orderAuthor=desc',
                'asc' => $urlQuery.$markSort.'orderAuthor=asc'
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

    public function download(Request $request) {
        $params = $this->retrieveStructure($request);

        $books = $params['books'];
        $raws = $params['raws'];

        if(!empty($raws['format'])) {
            switch($raws['format']) {
                case 'csv':
                    $output = fopen('php://output','w') or die('Can\'t open php://output');
                    header('Content-Type:application/csv');
                    header('Content-Disposition:attachment;filename=Download.csv');
                    $header = ['Title','Author'];
                    $structureBooks = [];
                    if(!empty($raws['fields'])) {
                        switch($raws['fields']){
                            case 'titleOnly':
                                $header = ['Title'];
                                foreach($books as $book) {
                                    $structureBooks[] = [
                                        'title' => $book->title
                                    ];
                                }
                            break;
                            case 'authorOnly':
                                $header = ['Author'];
                                foreach($books as $book) {
                                    $structureBooks[] = [
                                        'author' => $book->author
                                    ];
                                }
                            break;
                        }
                    }
                    else{
                        foreach($books as $book) {
                            $structureBooks[] = [
                                'title' => $book->title,
                                'author' => $book->author
                            ];
                        }
                    }
                    fputcsv($output, $header);
                    foreach($structureBooks as $book) {
                        fputcsv($output, $book);
                    }
                    fclose($output) or die("Can't close php://output");
                break;
                case 'xml':
                    header('Content-type: text/xml');
                    header('Content-Disposition: attachment; filename="Download.xml"');
                    $xml = new \DomDocument('1.0', 'UTF-8');
                    $root = $xml->createElement('books');

                    foreach($books as $book){
                        $single_book = $xml->createElement('book');

                        if(!empty($raws['fields'])) {
                            switch($raws['fields']) {
                                case 'titleOnly':
                                    $only = $xml->createElement('title', $book->title);
                                break;
                                case 'authorOnly':
                                    $only = $xml->createElement('author', $book->author);
                                break;
                            }
                            $single_book->appendChild($only);
                        }
                        else{
                            $title = $xml->createElement('title', $book->title);
                            $author = $xml->createElement('author', $book->author);
                            $single_book->appendChild($title);
                            $single_book->appendChild($author);
                        }
                        $root->appendChild($single_book);
                    }

                    $xml->appendChild($root);
                    $xml->formatOutput = TRUE;
                    echo $xml->saveXML();
                break;
            }
        }
    }

    private function retrieveStructure(Request $request){
        $defaults = $urlQuery = $downloadParams = [];
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
                $downloadParams[] = $field.'='.$value;
            }
        }
        $books = $query->get();
        $books = $books->toArray();

        return [
            'books' => $books,
            'urlQuery' => $urlQuery,
            'downloadParams' => $downloadParams,
            'defaults' => $defaults,
            'raws' => $params,
        ];
    }
}
