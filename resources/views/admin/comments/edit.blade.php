@extends('layouts.admin')
@section('title')
    Comments
@endsection
@section('content')
    <div class="d-flex justify-content-between shadow-lg p-3 mb-5 bg-body-tertiary rounded">
        <div class="d-flex">
            <h1>| Edit | <strong>{{$comment->id}}</strong></h1>
            <a class="btn btn-primary mx-1 my-2 rounded-pill" href="{{ route('comments.index') }}">All Comments</a>
        </div>
    </div>
    @if(session('status'))
        <div class="alert alert-success">
            <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">x</a>
            <strong>Success!</strong>{{session('status')}}
        </div>
    @endif
    <div class="row my-2">
        <div class="col-12">
            <form action="{{action('App\Http\Controllers\AdminCommentsController@update', $comment->id)}}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <input type="hidden" name="post_id" value="{{$comment->post_id}}">
                <input type="hidden" name="user_id" value="{{$comment->user_id}}">
                <input type="hidden" name="parent_id" value="{{$comment->parent_id}}">

                <textarea name="body" class="form-control" placeholder="Leave a comment here" style="height: 100px">{{$comment->body}}</textarea>
                @error('body')
                <p class="text-danger fs-6">
                    {{$message}}
                </p>
                @enderror
                <button type="submit" class="ml-auto btn btn-dark d-flex justify-content-end me-3">UPDATE</button>
            </form>
        </div>
    </div>

@endsection
