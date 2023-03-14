@extends('layouts.admin')
@section('title')
    Edit Category
@endsection
@section('content')
    <div class="d-flex justify-content-between shadow-lg p-3 mb-5 bg-body-tertiary rounded">
        <div class="d-flex align-items-center">
            <h1 class="mb-0 mr-3">Edit | <strong>{{$category->name}}</strong></h1>
        </div>

        <div class="d-flex">
            <a class="btn btn-primary mx1 my-2 rounded-pill" href="{{ route('categories.index') }}">All categories</a>
            <a class="btn btn-primary mx-1 my-2 rounded-pill" href="{{ route('categories.create') }}">Create category</a>
        </div>
    </div>
    <div class="row my-2">
        <div class="col-12">
            <form action="{{action('App\Http\Controllers\AdminCategoriesController@update', $category->id)}}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <div class="form-group mb-3">
                    <input name="name" type="text" class="form-control" id="floatingInputValue" placeholder="Name" value="{{$category->name}}">
                    @error('name')
                    <p class="text-danger fs-6">{{$message}}</p>
                    @enderror
                </div>
                <button type="submit" class="ml-auto btn btn-dark d-flex justify-content-end me-3">UPDATE</button>
            </form>
        </div>
    </div>
@endsection
