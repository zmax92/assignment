@extends('master')

@section('title', $title)

@section('content')
    <form action="/" method="post" class="create-form">
        @csrf
        <div class="row justify-content-center">
            <div class="col-sm-6 col-12 form-group">
                <label for="title">Title <span class="required">*</span></label>
                <input type="text" class="form-control w-100" id="title" name="title">
                @error('title')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-sm-6 col-12 form-group">
                <label for="author">Author <span class="required">*</span></label>
                <input type="text" class="form-control w-100" id="author" name="author">
                @error('title')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-sm-6 text-center">
                <input type="submit" class="create-btn" dusk="create-btn" value="Create">
            </div>
        </div>
    </form>
@endsection