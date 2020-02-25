@extends('master')

@section('title', $title)

@section('content')
    <form action="/" method="get" class="filter-form">
        <div class="row">
            <div class="col-sm-6 form-group">
                <label for="title">Title</label>
                <input type="text" class="form-control w-100" id="title" name="title" value="{{ ( !empty($defaults['title']) ? $defaults['title'] : '') }}">
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6 form-group">
                <label for="author">Author</label>
                <input type="text" class="form-control w-100" id="author" name="author" value="{{ ( !empty($defaults['author']) ? $defaults['author'] : '') }}">
            </div>
            <div class="col-sm-6 align-self-end align-text-bottom">
                <input type="submit" class="filter-btn" value="Filter">
            </div>
        </div>
    </form>
    <div class="create-wrapper">
        <a href="/create" class="create-btn" title="Create new book">Create +</a>
    </div>
    @if (!empty( $books ))
        <div class="row sort-row">
            <div class="col-sm-3 col-6">
                <span>Title</span>
                <a href="{{$orderTitle['desc']}}" title="Desc"><i class="material-icons align-text-bottom">keyboard_arrow_down</i></a>
                <a href="{{$orderTitle['asc']}}" title="Asc"><i class="material-icons align-text-bottom">keyboard_arrow_up</i></a>
            </div>
            <div class="col-sm-3 col-6">
                <span>Author</span>
                <a href="{{$orderAuthor['desc']}}" title="Desc"><i class="material-icons align-text-bottom">keyboard_arrow_down</i></a>
                <a href="{{$orderAuthor['asc']}}" title="Asc"><i class="material-icons align-text-bottom">keyboard_arrow_up</i></a>
            </div>
            <div class="col-sm-6 col-md-4 col-lg-3 col-12 ml-auto">
                <div class="row">
                    <div class="col-sm-12 col-12">
                        <strong class="export-btn" onclick="showHideDiv()">Export</strong>
                    </div>
                </div>
                <div class="row export-item">
                    <div class="col-sm-6 col-6">
                        <span>Title</span>
                    </div>
                    <div class="col-sm-6 col-6">
                        <a href="#" title="Export authors list XML">
                            <img src="http://icons.iconarchive.com/icons/hopstarter/adobe-cs4/16/File-Adobe-Dreamweaver-XML-01-icon.png" />
                        </a>
                        <a href="#" title="Export authors list CSV">
                            <img src="https://img.icons8.com/office/16/000000/export-csv.png" />
                        </a>
                    </div>
                </div>
                <div class="row export-item">
                    <div class="col-sm-6 col-6">
                        <span>Author</span>
                    </div>
                    <div class="col-sm-6 col-6">
                        <a href="#" title="Export authors list XML">
                            <img src="http://icons.iconarchive.com/icons/hopstarter/adobe-cs4/16/File-Adobe-Dreamweaver-XML-01-icon.png" />
                        </a>
                        <a href="#" title="Export authors list CSV">
                            <img src="https://img.icons8.com/office/16/000000/export-csv.png" />
                        </a>
                    </div>
                </div>
                <div class="row export-item">
                    <div class="col-sm-6 col-6">
                        <span>Title & Author</span>
                    </div>
                    <div class="col-sm-6 col-6">
                        <a href="#" title="Export authors list XML">
                            <img src="http://icons.iconarchive.com/icons/hopstarter/adobe-cs4/16/File-Adobe-Dreamweaver-XML-01-icon.png" />
                        </a>
                        <a href="#" title="Export authors list CSV">
                            <img src="https://img.icons8.com/office/16/000000/export-csv.png" />
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="w-100 table table-bordered table-hover table-sm">
                <thead class="thead-dark">
                    <tr>
                        <th>
                            <span>Title</span>
                        </th>
                        <th>
                            <span>Authors</span>
                        </th>
                        <th>
                            <span>Delete</span>
                        </th>
                    </tr>
                </thead>
                @foreach ($books as $book)
                    <tr>
                        <td>{{ $book->title }}</td>
                        <td>{{ $book->author }}</td>
                        <td>
                            <a href="#" class="delete-btn" onclick="deleteFunction('{{ $book->id }}', '{{ $book->title }}', '{{ $book->author }}')"><i class="material-icons">delete</i></a>
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    @else
        <div><strong>There are no books, please <a href="/create" class="create-btn" title="Create new book">create</a> one!</strong></div>
    @endif
@endsection